<?php

namespace App\Http\Controllers;

use App\Models\Atividade;
use App\Models\Reuniao;
use App\Models\Relatorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use League\Csv\Writer;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Response;

class RelatoriosController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('relatorios.view');
        
        $periodoInicio = $request->get('periodo_inicio', now()->startOfMonth()->format('Y-m-d'));
        $periodoFim = $request->get('periodo_fim', now()->endOfMonth()->format('Y-m-d'));
        
        // Relatório de Atividades
        $atividadesConcluidas = Atividade::where('status', 'concluida')
            ->whereBetween('data_inicio', [$periodoInicio, $periodoFim])
            ->count();
        $atividadesEmAndamento = Atividade::where('status', 'em_andamento')
            ->whereBetween('data_inicio', [$periodoInicio, $periodoFim])
            ->count();
        $totalAtividades = Atividade::whereBetween('data_inicio', [$periodoInicio, $periodoFim])->count();
        $taxaSucesso = $totalAtividades > 0 ? round(($atividadesConcluidas / $totalAtividades) * 100) : 0;
        
        // Relatório de Reuniões
        $reunioesRealizadas = Reuniao::where('status', 'concluida')
            ->whereBetween('data_hora', [$periodoInicio, $periodoFim])
            ->count();
        $participacaoMedia = Reuniao::where('status', 'concluida')
            ->whereBetween('data_hora', [$periodoInicio, $periodoFim])
            ->withCount('participantes')
            ->get()
            ->avg('participantes_count') ?? 0;
        $decisoesTomadas = \App\Models\Decisao::whereHas('reuniao', function($q) use ($periodoInicio, $periodoFim) {
            $q->whereBetween('data_hora', [$periodoInicio, $periodoFim]);
        })->count();
        
        // Relatório Financeiro
        $orcamentoUtilizado = Atividade::whereBetween('data_inicio', [$periodoInicio, $periodoFim])
            ->whereNotNull('orcamento')
            ->sum('orcamento');
        
        // Total de atividades para cálculo de taxa
        $totalAtividadesPeriodo = Atividade::whereBetween('data_inicio', [$periodoInicio, $periodoFim])->count();
        
        return view('relatorios', compact(
            'atividadesConcluidas',
            'atividadesEmAndamento',
            'taxaSucesso',
            'reunioesRealizadas',
            'participacaoMedia',
            'decisoesTomadas',
            'orcamentoUtilizado',
            'periodoInicio',
            'periodoFim',
            'totalAtividadesPeriodo'
        ));
    }

    public function exportar(Request $request, $tipo, $formato)
    {
        Gate::authorize('relatorios.export');
        
        $periodoInicio = $request->get('periodo_inicio', now()->startOfMonth()->format('Y-m-d'));
        $periodoFim = $request->get('periodo_fim', now()->endOfMonth()->format('Y-m-d'));
        
        if ($formato === 'csv') {
            return $this->exportarCSV($tipo, $periodoInicio, $periodoFim);
        } elseif ($formato === 'pdf') {
            return $this->exportarPDF($tipo, $periodoInicio, $periodoFim);
        }
        
        return redirect()->back()->with('error', 'Formato de exportação inválido.');
    }

    private function exportarCSV($tipo, $periodoInicio, $periodoFim)
    {
        $dados = $this->buscarDadosDetalhados($tipo, $periodoInicio, $periodoFim);
        
        // Criar CSV
        $csv = Writer::createFromString();
        $csv->setOutputBOM(Writer::BOM_UTF8);
        
        // Adicionar cabeçalho
        $csv->insertOne(['Relatório de ' . ucfirst($tipo)]);
        $csv->insertOne(['Período: ' . \Carbon\Carbon::parse($periodoInicio)->format('d/m/Y') . ' a ' . \Carbon\Carbon::parse($periodoFim)->format('d/m/Y')]);
        $csv->insertOne([]);
        
        if (!empty($dados['resumo'])) {
            $csv->insertOne(['Resumo']);
            foreach ($dados['resumo'] as $chave => $valor) {
                $csv->insertOne([$chave, $valor]);
            }
            $csv->insertOne([]);
        }
        
        if (!empty($dados['detalhes'])) {
            $csv->insertOne(['Detalhes']);
            // Inserir cabeçalhos das colunas
            if (!empty($dados['detalhes'])) {
                $primeiraLinha = reset($dados['detalhes']);
                $csv->insertOne(array_keys($primeiraLinha));
                // Inserir dados
                foreach ($dados['detalhes'] as $linha) {
                    $csv->insertOne(array_values($linha));
                }
            }
        }
        
        // Salvar relatório no banco
        Relatorio::create([
            'tipo' => $tipo,
            'periodo_inicio' => $periodoInicio,
            'periodo_fim' => $periodoFim,
            'dados' => $dados,
            'gerado_por_id' => Auth::id(),
        ]);
        
        $filename = 'relatorio_' . $tipo . '_' . $periodoInicio . '_' . $periodoFim . '.csv';
        
        return Response::make($csv->toString(), 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function exportarPDF($tipo, $periodoInicio, $periodoFim)
    {
        $dados = $this->buscarDadosDetalhados($tipo, $periodoInicio, $periodoFim);
        
        // Configurar DomPDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        
        // Renderizar view
        $html = view('relatorios.pdf.' . $tipo, [
            'dados' => $dados,
            'periodoInicio' => $periodoInicio,
            'periodoFim' => $periodoFim,
            'tipo' => $tipo,
            'usuario' => Auth::user(),
        ])->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Salvar relatório no banco
        Relatorio::create([
            'tipo' => $tipo,
            'periodo_inicio' => $periodoInicio,
            'periodo_fim' => $periodoFim,
            'dados' => $dados,
            'gerado_por_id' => Auth::id(),
        ]);
        
        $filename = 'relatorio_' . $tipo . '_' . $periodoInicio . '_' . $periodoFim . '.pdf';
        
        return Response::make($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function buscarDadosDetalhados($tipo, $periodoInicio, $periodoFim)
    {
        $dados = [
            'resumo' => [],
            'detalhes' => [],
        ];
        
        if ($tipo === 'atividades') {
            $atividades = Atividade::whereBetween('data_inicio', [$periodoInicio, $periodoFim])
                ->with('responsavel')
                ->orderBy('data_inicio', 'asc')
                ->get();
            
            $atividadesConcluidas = $atividades->where('status', 'concluida')->count();
            $atividadesEmAndamento = $atividades->where('status', 'em_andamento')->count();
            $totalAtividades = $atividades->count();
            $taxaSucesso = $totalAtividades > 0 ? round(($atividadesConcluidas / $totalAtividades) * 100) : 0;
            
            $dados['resumo'] = [
                'Total de Atividades' => $totalAtividades,
                'Atividades Concluídas' => $atividadesConcluidas,
                'Atividades Em Andamento' => $atividadesEmAndamento,
                'Taxa de Sucesso' => $taxaSucesso . '%',
            ];
            
            $dados['detalhes'] = $atividades->map(function($atividade) {
                return [
                    'Título' => $atividade->titulo,
                    'Tipo' => ucfirst($atividade->tipo),
                    'Status' => ucfirst($atividade->status),
                    'Data Início' => $atividade->data_inicio->format('d/m/Y'),
                    'Data Fim' => $atividade->data_fim ? $atividade->data_fim->format('d/m/Y') : 'Não definida',
                    'Responsável' => $atividade->responsavel ? $atividade->responsavel->name : 'Não definido',
                    'Local' => $atividade->local ?? 'Não definido',
                    'Orçamento' => $atividade->orcamento ? 'R$ ' . number_format($atividade->orcamento, 2, ',', '.') : 'Não definido',
                ];
            })->toArray();
            
        } elseif ($tipo === 'reunioes') {
            $reunioes = Reuniao::where('status', 'concluida')
                ->whereBetween('data_hora', [$periodoInicio, $periodoFim])
                ->with(['organizador', 'participantes'])
                ->orderBy('data_hora', 'desc')
                ->get();
            
            $totalReunioes = $reunioes->count();
            $participacaoMedia = $reunioes->avg(function($reuniao) {
                return $reuniao->participantes->count();
            }) ?? 0;
            $decisoesTomadas = \App\Models\Decisao::whereHas('reuniao', function($q) use ($periodoInicio, $periodoFim) {
                $q->whereBetween('data_hora', [$periodoInicio, $periodoFim]);
            })->count();
            
            $dados['resumo'] = [
                'Reuniões Realizadas' => $totalReunioes,
                'Participação Média' => round($participacaoMedia, 1) . ' pessoas',
                'Decisões Tomadas' => $decisoesTomadas,
            ];
            
            $dados['detalhes'] = $reunioes->map(function($reuniao) {
                return [
                    'Título' => $reuniao->titulo,
                    'Tipo' => ucfirst($reuniao->tipo),
                    'Data/Hora' => $reuniao->data_hora->format('d/m/Y H:i'),
                    'Local' => $reuniao->local ?? 'Não definido',
                    'Organizador' => $reuniao->organizador->name,
                    'Nº Participantes' => $reuniao->participantes->count(),
                ];
            })->toArray();
            
        } elseif ($tipo === 'financeiro') {
            $atividades = Atividade::whereBetween('data_inicio', [$periodoInicio, $periodoFim])
                ->whereNotNull('orcamento')
                ->with('responsavel')
                ->orderBy('orcamento', 'desc')
                ->get();
            
            $orcamentoUtilizado = $atividades->sum('orcamento');
            $porTipo = $atividades->groupBy('tipo')->map(function($grupo) {
                return $grupo->sum('orcamento');
            });
            
            $dados['resumo'] = [
                'Orçamento Total Utilizado' => 'R$ ' . number_format($orcamentoUtilizado, 2, ',', '.'),
                'Total de Atividades com Orçamento' => $atividades->count(),
            ];
            
            foreach ($porTipo as $tipo => $valor) {
                $dados['resumo']['Orçamento - ' . ucfirst($tipo)] = 'R$ ' . number_format($valor, 2, ',', '.');
            }
            
            $dados['detalhes'] = $atividades->map(function($atividade) {
                return [
                    'Atividade' => $atividade->titulo,
                    'Tipo' => ucfirst($atividade->tipo),
                    'Status' => ucfirst($atividade->status),
                    'Orçamento' => 'R$ ' . number_format($atividade->orcamento, 2, ',', '.'),
                    'Data Início' => $atividade->data_inicio->format('d/m/Y'),
                    'Responsável' => $atividade->responsavel ? $atividade->responsavel->name : 'Não definido',
                ];
            })->toArray();
        }
        
        return $dados;
    }
}


