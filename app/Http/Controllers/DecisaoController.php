<?php

namespace App\Http\Controllers;

use App\Models\Decisao;
use App\Models\Reuniao;
use App\Models\User;
use Illuminate\Http\Request;

class DecisaoController extends Controller
{
    public function create(Reuniao $reuniao)
    {
        $usuarios = User::all();
        $atas = $reuniao->atas;
        return view('decisoes.create', compact('reuniao', 'usuarios', 'atas'));
    }

    public function store(Request $request, Reuniao $reuniao)
    {
        $validated = $request->validate([
            'ata_id' => 'nullable|exists:atas,id',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'status' => 'required|in:pendente,em_andamento,concluida',
            'prazo' => 'nullable|date',
            'responsavel_id' => 'nullable|exists:users,id',
        ]);

        $validated['reuniao_id'] = $reuniao->id;

        $decisao = Decisao::create($validated);

        return redirect()->route('reunioes.show', $reuniao)->with('success', 'Decisão criada com sucesso!');
    }

    public function show(Decisao $decisao)
    {
        $decisao->load(['reuniao', 'ata', 'responsavel', 'tarefas']);
        return view('decisoes.show', compact('decisao'));
    }

    public function edit(Decisao $decisao)
    {
        $usuarios = User::all();
        $atas = $decisao->reuniao->atas;
        return view('decisoes.edit', compact('decisao', 'usuarios', 'atas'));
    }

    public function update(Request $request, Decisao $decisao)
    {
        $validated = $request->validate([
            'ata_id' => 'nullable|exists:atas,id',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'status' => 'required|in:pendente,em_andamento,concluida',
            'prazo' => 'nullable|date',
            'responsavel_id' => 'nullable|exists:users,id',
        ]);

        $decisao->update($validated);

        return redirect()->route('decisoes.show', $decisao)->with('success', 'Decisão atualizada com sucesso!');
    }

    public function destroy(Decisao $decisao)
    {
        $decisao->delete();
        return redirect()->back()->with('success', 'Decisão excluída com sucesso!');
    }
}
