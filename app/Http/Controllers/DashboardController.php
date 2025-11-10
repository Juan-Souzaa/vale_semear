<?php

namespace App\Http\Controllers;

use App\Models\Atividade;
use App\Models\Obrigacao;
use App\Models\Reuniao;
use App\Models\Tarefa;
use App\Models\LembreteReuniao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Métricas principais
        $totalAtividades = Atividade::count();
        $atividadesMesPassado = Atividade::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $diferencaAtividades = $totalAtividades - $atividadesMesPassado;

        $reunioesAgendadas = Reuniao::whereIn('status', ['agendada', 'confirmada'])->count();
        $reunioesEstaSemana = Reuniao::whereIn('status', ['agendada', 'confirmada'])
            ->whereBetween('data_hora', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $tarefasPendentes = Tarefa::whereIn('status', ['pendente', 'em_andamento'])->count();
        $tarefasPrazoProximo = Tarefa::whereIn('status', ['pendente', 'em_andamento'])
            ->where('data_vencimento', '<=', now()->addDays(7))
            ->where('data_vencimento', '>=', now())
            ->count();

        // Taxa de conclusão de atividades
        $atividadesConcluidas = Atividade::where('status', 'concluida')->count();
        $taxaConclusao = $totalAtividades > 0 
            ? round(($atividadesConcluidas / $totalAtividades) * 100) 
            : 0;
        
        $atividadesConcluidasMesPassado = Atividade::where('status', 'concluida')
            ->whereMonth('updated_at', now()->subMonth()->month)
            ->whereYear('updated_at', now()->subMonth()->year)
            ->count();
        $totalAtividadesMesPassado = Atividade::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $taxaConclusaoMesPassado = $totalAtividadesMesPassado > 0 
            ? round(($atividadesConcluidasMesPassado / $totalAtividadesMesPassado) * 100) 
            : 0;
        $diferencaTaxa = $taxaConclusao - $taxaConclusaoMesPassado;

        // Atividades recentes (últimas 5)
        $atividadesRecentes = Atividade::with('responsavel')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Próximas obrigações com alertas
        $obrigacoesProximas = Obrigacao::where('status', '!=', 'concluida')
            ->orderBy('data_vencimento', 'asc')
            ->limit(4)
            ->get();

        return view('dashboard', compact(
            'totalAtividades',
            'diferencaAtividades',
            'reunioesAgendadas',
            'reunioesEstaSemana',
            'tarefasPendentes',
            'tarefasPrazoProximo',
            'taxaConclusao',
            'diferencaTaxa',
            'atividadesRecentes',
            'obrigacoesProximas'
        ));
    }

    public function getNotificacoes()
    {
        try {
            $notificacoes = [];

            // Obrigações vencidas ou próximas do vencimento (7 dias)
            try {
                $obrigacoesVencidas = Obrigacao::where('status', '!=', 'concluida')
                    ->where('data_vencimento', '<=', now()->addDays(7))
                    ->where('data_vencimento', '>=', now()->subDays(1))
                    ->orderBy('data_vencimento', 'asc')
                    ->get();

                foreach ($obrigacoesVencidas as $obrigacao) {
                    $diasRestantes = now()->diffInDays($obrigacao->data_vencimento, false);
                    $tipo = $diasRestantes < 0 ? 'danger' : ($diasRestantes <= 3 ? 'warning' : 'info');
                    $mensagem = $diasRestantes < 0 
                        ? "Vencida há " . abs($diasRestantes) . " dia(s)" 
                        : "Vence em {$diasRestantes} dia(s)";

                    $notificacoes[] = [
                        'tipo' => $tipo,
                        'titulo' => 'Obrigação: ' . $obrigacao->titulo,
                        'mensagem' => $mensagem,
                        'link' => route('obrigacoes.show', $obrigacao->id),
                    ];
                }
            } catch (\Exception $e) {
                // Ignora erro se tabela não existir
            }

            // Reuniões hoje e amanhã
            try {
                $reunioesProximas = Reuniao::whereIn('status', ['agendada', 'confirmada'])
                    ->whereBetween('data_hora', [now()->startOfDay(), now()->addDay()->endOfDay()])
                    ->orderBy('data_hora', 'asc')
                    ->get();

                foreach ($reunioesProximas as $reuniao) {
                    $hoje = $reuniao->data_hora->isToday();
                    $titulo = $hoje ? 'Reunião hoje' : 'Reunião amanhã';
                    
                    $notificacoes[] = [
                        'tipo' => $hoje ? 'warning' : 'info',
                        'titulo' => $titulo . ': ' . $reuniao->titulo,
                        'mensagem' => $reuniao->data_hora->format('d/m/Y H:i') . ($reuniao->local ? ' - ' . $reuniao->local : ''),
                        'link' => route('reunioes.show', $reuniao->id),
                    ];
                }
            } catch (\Exception $e) {
                // Ignora erro se tabela não existir
            }

            // Atividades com prazo próximo (3 dias)
            try {
                $atividadesPrazoProximo = Atividade::whereIn('status', ['pendente', 'em_andamento'])
                    ->whereNotNull('data_vencimento')
                    ->where('data_vencimento', '<=', now()->addDays(3))
                    ->where('data_vencimento', '>=', now())
                    ->orderBy('data_vencimento', 'asc')
                    ->get();

                foreach ($atividadesPrazoProximo as $atividade) {
                    $diasRestantes = now()->diffInDays($atividade->data_vencimento, false);
                    
                    $notificacoes[] = [
                        'tipo' => $diasRestantes <= 1 ? 'warning' : 'info',
                        'titulo' => 'Atividade: ' . $atividade->titulo,
                        'mensagem' => "Prazo em {$diasRestantes} dia(s)",
                        'link' => route('planejamento.show', $atividade->id),
                    ];
                }
            } catch (\Exception $e) {
                // Ignora erro se tabela não existir
            }

            // Lembretes de reuniões configurados (próximos 7 dias)
            try {
                if (Schema::hasTable('lembretes_reuniao')) {
                    $lembretesProximos = LembreteReuniao::with('reuniao')
                        ->where('enviado', false)
                        ->whereHas('reuniao', function($query) {
                            $query->whereIn('status', ['agendada', 'confirmada'])
                                ->where('data_hora', '>=', now())
                                ->where('data_hora', '<=', now()->addDays(7));
                        })
                        ->get()
                        ->filter(function($lembrete) {
                            if (!$lembrete->reuniao) {
                                return false;
                            }
                            $reuniao = $lembrete->reuniao;
                            // Calcula diferença em dias (considerando apenas a data, não a hora)
                            $dataReuniao = $reuniao->data_hora->startOfDay();
                            $dataAtual = now()->startOfDay();
                            $diasRestantes = $dataAtual->diffInDays($dataReuniao, false);
                            
                            // Verifica se está no dia correto para o lembrete
                            return match($lembrete->tipo) {
                                '1_dia' => $diasRestantes >= 0 && $diasRestantes <= 1,
                                '3_dias' => $diasRestantes >= 2 && $diasRestantes <= 3,
                                '1_semana' => $diasRestantes >= 6 && $diasRestantes <= 7,
                                default => false
                            };
                        });

                    foreach ($lembretesProximos as $lembrete) {
                        if ($lembrete->reuniao) {
                            $reuniao = $lembrete->reuniao;
                            $dataReuniao = $reuniao->data_hora->startOfDay();
                            $dataAtual = now()->startOfDay();
                            $diasRestantes = $dataAtual->diffInDays($dataReuniao, false);
                            
                            $notificacoes[] = [
                                'tipo' => $diasRestantes <= 1 ? 'warning' : 'info',
                                'titulo' => 'Lembrete: ' . $reuniao->titulo,
                                'mensagem' => "Reunião em {$diasRestantes} dia(s) - Lembrete: {$lembrete->tipo_label}",
                                'link' => route('reunioes.show', $reuniao->id),
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Erro ao buscar lembretes: ' . $e->getMessage());
                // Ignora erro se tabela não existir
            }

            return response()->json([
                'notificacoes' => $notificacoes,
                'total' => count($notificacoes),
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar notificações: ' . $e->getMessage());
            return response()->json([
                'notificacoes' => [],
                'total' => 0,
                'error' => 'Erro ao carregar notificações'
            ], 500);
        }
    }
}
