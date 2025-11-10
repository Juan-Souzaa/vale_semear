<?php

namespace App\Http\Controllers;

use App\Models\Ata;
use App\Models\Reuniao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AtaController extends Controller
{
    public function create(Reuniao $reuniao)
    {
        return view('atas.create', compact('reuniao'));
    }

    public function store(Request $request, Reuniao $reuniao)
    {
        $validated = $request->validate([
            'numero' => 'nullable|string|max:255',
            'conteudo' => 'required|string',
            'aprovada' => 'boolean',
            'data_aprovacao' => 'nullable|date',
        ]);

        $validated['reuniao_id'] = $reuniao->id;
        $validated['criado_por_id'] = Auth::id();
        $validated['aprovada'] = $request->has('aprovada');

        Ata::create($validated);

        return redirect()->route('reunioes.index')->with('success', 'Ata criada com sucesso!');
    }

    public function show(Ata $ata)
    {
        $ata->load(['reuniao', 'criadoPor', 'decisoes']);
        return view('atas.show', compact('ata'));
    }

    public function edit(Ata $ata)
    {
        $ata->load(['reuniao']);
        return view('atas.edit', compact('ata'));
    }

    public function update(Request $request, Ata $ata)
    {
        $validated = $request->validate([
            'numero' => 'nullable|string|max:255',
            'conteudo' => 'required|string',
            'aprovada' => 'boolean',
            'data_aprovacao' => 'nullable|date',
        ]);

        $validated['aprovada'] = $request->has('aprovada');
        
        // Se foi aprovada agora, definir data de aprovação
        if ($validated['aprovada'] && !$ata->aprovada) {
            $validated['data_aprovacao'] = $validated['data_aprovacao'] ?? now();
        }

        $ata->update($validated);

        return redirect()->route('atas.show', $ata)->with('success', 'Ata atualizada com sucesso!');
    }

    public function aprovar(Ata $ata)
    {
        $ata->update([
            'aprovada' => true,
            'data_aprovacao' => now(),
        ]);

        return redirect()->back()->with('success', 'Ata aprovada com sucesso!');
    }
}
