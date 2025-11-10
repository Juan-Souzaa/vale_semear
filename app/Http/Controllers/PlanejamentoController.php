<?php

namespace App\Http\Controllers;

use App\Models\Atividade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanejamentoController extends Controller
{
    public function index(Request $request)
    {
        $query = Atividade::with('responsavel');
        
        // Filtros
        if ($request->filled('busca')) {
            $query->where('titulo', 'like', '%' . $request->busca . '%')
                  ->orWhere('descricao', 'like', '%' . $request->busca . '%');
        }
        
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Para o calendário, buscar todas as atividades
        $atividades = $query->orderBy('data_inicio', 'asc')->get();
        
        // Agrupar por mês para o calendário
        $mes = $request->get('mes', now()->month);
        $ano = $request->get('ano', now()->year);
        
        $atividadesMes = $atividades->filter(function($atividade) use ($mes, $ano) {
            return $atividade->data_inicio->month == $mes && $atividade->data_inicio->year == $ano;
        });
        
        $usuarios = User::all();
        
        return view('planejamento', compact('atividades', 'atividadesMes', 'mes', 'ano', 'usuarios'));
    }

    public function create()
    {
        $usuarios = User::all();
        return view('planejamento.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:mutirao,melhoria,evento,treinamento,workshop,outro',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'status' => 'required|in:planejada,em_andamento,concluida,cancelada',
            'local' => 'nullable|string|max:255',
            'responsavel_id' => 'nullable|exists:users,id',
            'orcamento' => 'nullable|numeric|min:0',
            'observacoes' => 'nullable|string',
        ]);

        Atividade::create($validated);

        return redirect()->route('planejamento.index')->with('success', 'Atividade criada com sucesso!');
    }

    public function show(Atividade $planejamento)
    {
        $atividade = $planejamento;
        $atividade->load('responsavel');
        return view('planejamento.show', compact('atividade'));
    }

    public function edit(Atividade $planejamento)
    {
        $atividade = $planejamento;
        $usuarios = User::all();
        return view('planejamento.edit', compact('atividade', 'usuarios'));
    }

    public function update(Request $request, Atividade $planejamento)
    {
        $atividade = $planejamento;
        
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:mutirao,melhoria,evento,treinamento,workshop,outro',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'status' => 'required|in:planejada,em_andamento,concluida,cancelada',
            'local' => 'nullable|string|max:255',
            'responsavel_id' => 'nullable|exists:users,id',
            'orcamento' => 'nullable|numeric|min:0',
            'observacoes' => 'nullable|string',
        ]);

        $atividade->update($validated);

        return redirect()->route('planejamento.index')->with('success', 'Atividade atualizada com sucesso!');
    }

    public function destroy(Atividade $planejamento)
    {
        $atividade = $planejamento;
        $atividade->delete();
        return redirect()->route('planejamento.index')->with('success', 'Atividade excluída com sucesso!');
    }
}


