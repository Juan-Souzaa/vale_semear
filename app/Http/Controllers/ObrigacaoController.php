<?php

namespace App\Http\Controllers;

use App\Models\Obrigacao;
use App\Models\User;
use Illuminate\Http\Request;

class ObrigacaoController extends Controller
{
    public function index(Request $request)
    {
        $query = Obrigacao::with('responsavel');
        
        if ($request->filled('busca')) {
            $query->where('titulo', 'like', '%' . $request->busca . '%');
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('prioridade')) {
            $query->where('prioridade', $request->prioridade);
        }
        
        $obrigacoes = $query->orderBy('data_vencimento', 'asc')->get();
        $usuarios = User::all();
        
        return view('obrigacoes.index', compact('obrigacoes', 'usuarios'));
    }

    public function create()
    {
        $usuarios = User::all();
        return view('obrigacoes.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:legal,administrativa,financeira,outro',
            'data_vencimento' => 'required|date',
            'data_lembrete' => 'nullable|date|before_or_equal:data_vencimento',
            'status' => 'required|in:pendente,em_andamento,concluida,vencida',
            'prioridade' => 'required|in:baixa,media,alta,urgente',
            'responsavel_id' => 'nullable|exists:users,id',
        ]);

        Obrigacao::create($validated);

        return redirect()->route('obrigacoes.index')->with('success', 'Obrigação criada com sucesso!');
    }

    public function show(Obrigacao $obrigacao)
    {
        $obrigacao->load('responsavel');
        return view('obrigacoes.show', compact('obrigacao'));
    }

    public function edit(Obrigacao $obrigacao)
    {
        $usuarios = User::all();
        return view('obrigacoes.edit', compact('obrigacao', 'usuarios'));
    }

    public function update(Request $request, Obrigacao $obrigacao)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:legal,administrativa,financeira,outro',
            'data_vencimento' => 'required|date',
            'data_lembrete' => 'nullable|date|before_or_equal:data_vencimento',
            'status' => 'required|in:pendente,em_andamento,concluida,vencida',
            'prioridade' => 'required|in:baixa,media,alta,urgente',
            'responsavel_id' => 'nullable|exists:users,id',
        ]);

        $obrigacao->update($validated);

        return redirect()->route('obrigacoes.index')->with('success', 'Obrigação atualizada com sucesso!');
    }

    public function destroy(Obrigacao $obrigacao)
    {
        $obrigacao->delete();
        return redirect()->route('obrigacoes.index')->with('success', 'Obrigação excluída com sucesso!');
    }
}
