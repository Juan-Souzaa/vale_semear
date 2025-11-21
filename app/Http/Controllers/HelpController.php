<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelpController extends Controller
{
    /**
     * Retorna o conteúdo de ajuda para uma chave específica
     */
    public function show($key)
    {
        // Verificar se o usuário está autenticado
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Não autorizado'
            ], 401);
        }

        // Buscar conteúdo de ajuda
        $helpContent = config("help.{$key}");
        
        // Se não encontrar com ponto, tentar buscar diretamente do array
        if (!$helpContent || !is_array($helpContent)) {
            $allHelp = config('help', []);
            $helpContent = $allHelp[$key] ?? null;
        }

        if (!$helpContent || !is_array($helpContent)) {
            return response()->json([
                'error' => "Conteúdo de ajuda não encontrado para: {$key}"
            ], 404);
        }

        return response()->json([
            'title' => $helpContent['title'] ?? 'Ajuda',
            'content' => $helpContent['content'] ?? '',
            'icon' => $helpContent['icon'] ?? 'question-circle'
        ]);
    }
}

