<?php

namespace App\Http\Controllers;

use App\Models\Reuniao;
use App\Models\User;
use App\Models\ParticipanteReuniao;
use App\Models\LembreteReuniao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReunioesController extends Controller
{
    public function index(Request $request)
    {
        $query = Reuniao::with(['organizador', 'participantes.user', 'lembretes']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $reunioes = $query->orderBy('data_hora', 'desc')->get();
        $proximasReunioes = Reuniao::with('lembretes')
            ->whereIn('status', ['agendada', 'confirmada'])
            ->where('data_hora', '>=', now())
            ->orderBy('data_hora', 'asc')
            ->limit(5)
            ->get();
        
        $reunioesPassadas = Reuniao::with('lembretes')
            ->where('status', 'concluida')
            ->orderBy('data_hora', 'desc')
            ->limit(5)
            ->get();
        
        return view('reunioes', compact('reunioes', 'proximasReunioes', 'reunioesPassadas'));
    }

    public function create()
    {
        $usuarios = User::all();
        return view('reunioes.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:diretoria,assembleia,comissao,outro',
            'data_hora' => 'required|date',
            'local' => 'nullable|string|max:255',
            'status' => 'required|in:agendada,confirmada,em_andamento,concluida,cancelada',
            'participantes' => 'nullable|array',
            'participantes.*' => 'exists:users,id',
        ]);

        $validated['organizador_id'] = Auth::id();
        $participantes = $validated['participantes'] ?? [];
        unset($validated['participantes']);

        $reuniao = Reuniao::create($validated);

        // Adicionar participantes
        foreach ($participantes as $userId) {
            ParticipanteReuniao::create([
                'reuniao_id' => $reuniao->id,
                'user_id' => $userId,
                'confirmado' => false,
                'presente' => false,
            ]);
        }

        return redirect()->route('reunioes.index')->with('success', 'Reunião criada com sucesso!');
    }

    public function show(Reuniao $reuniao)
    {
        $reuniao->load(['organizador', 'participantes.user', 'atas', 'decisoes', 'lembretes']);
        $usuarios = User::all();
        return view('reunioes.show', compact('reuniao', 'usuarios'));
    }

    public function edit(Reuniao $reuniao)
    {
        $usuarios = User::all();
        $participantesIds = $reuniao->participantes->pluck('user_id')->toArray();
        return view('reunioes.edit', compact('reuniao', 'usuarios', 'participantesIds'));
    }

    public function update(Request $request, Reuniao $reuniao)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:diretoria,assembleia,comissao,outro',
            'data_hora' => 'required|date',
            'local' => 'nullable|string|max:255',
            'status' => 'required|in:agendada,confirmada,em_andamento,concluida,cancelada',
            'participantes' => 'nullable|array',
            'participantes.*' => 'exists:users,id',
        ]);

        $participantes = $validated['participantes'] ?? [];
        unset($validated['participantes']);

        // Atualizar dados da reunião
        $reuniao->update($validated);

        // Atualizar participantes de forma inteligente
        $participantesAtuais = $reuniao->participantes->pluck('user_id')->toArray();
        $participantesNovos = $participantes;
        
        // Participantes a remover (estavam antes mas não estão mais)
        $participantesRemover = array_diff($participantesAtuais, $participantesNovos);
        if (!empty($participantesRemover)) {
            ParticipanteReuniao::where('reuniao_id', $reuniao->id)
                ->whereIn('user_id', $participantesRemover)
                ->delete();
        }
        
        // Participantes a adicionar (não estavam antes)
        $participantesAdicionar = array_diff($participantesNovos, $participantesAtuais);
        foreach ($participantesAdicionar as $userId) {
            ParticipanteReuniao::create([
                'reuniao_id' => $reuniao->id,
                'user_id' => $userId,
                'confirmado' => false,
                'presente' => false,
            ]);
        }

        return redirect()->route('reunioes.show', $reuniao)->with('success', 'Reunião atualizada com sucesso!');
    }

    public function destroy(Reuniao $reuniao)
    {
        $reuniao->delete();
        return redirect()->route('reunioes.index')->with('success', 'Reunião excluída com sucesso!');
    }

    public function adicionarParticipante(Request $request, Reuniao $reuniao)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        ParticipanteReuniao::firstOrCreate([
            'reuniao_id' => $reuniao->id,
            'user_id' => $request->user_id,
        ], [
            'confirmado' => false,
            'presente' => false,
        ]);

        return redirect()->back()->with('success', 'Participante adicionado com sucesso!');
    }

    public function removerParticipante(Reuniao $reuniao, User $user)
    {
        ParticipanteReuniao::where('reuniao_id', $reuniao->id)
            ->where('user_id', $user->id)
            ->delete();

        return redirect()->back()->with('success', 'Participante removido com sucesso!');
    }

    public function confirmarPresenca(Reuniao $reuniao, User $user)
    {
        $participante = ParticipanteReuniao::where('reuniao_id', $reuniao->id)
            ->where('user_id', $user->id)
            ->first();

        if ($participante) {
            $participante->update([
                'confirmado' => true,
                'presente' => true,
            ]);
        }

        return redirect()->back()->with('success', 'Presença confirmada!');
    }

    public function salvarLembretes(Request $request, Reuniao $reuniao)
    {
        $request->validate([
            'lembretes' => 'nullable|array',
            'lembretes.*' => 'in:1_dia,3_dias,1_semana',
        ]);

        // Remover lembretes existentes
        $reuniao->lembretes()->delete();

        // Criar novos lembretes
        if ($request->has('lembretes') && is_array($request->lembretes)) {
            foreach ($request->lembretes as $tipo) {
                LembreteReuniao::create([
                    'reuniao_id' => $reuniao->id,
                    'tipo' => $tipo,
                    'enviado' => false,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Lembretes configurados com sucesso!');
    }

    public function removerLembrete(Reuniao $reuniao, LembreteReuniao $lembrete)
    {
        if ($lembrete->reuniao_id === $reuniao->id) {
            $lembrete->delete();
            return redirect()->back()->with('success', 'Lembrete removido com sucesso!');
        }

        return redirect()->back()->with('error', 'Lembrete não encontrado.');
    }
}


