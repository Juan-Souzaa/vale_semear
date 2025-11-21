@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Visão geral das atividades da associação')

@section('header-actions')
@include('components.help-icon', ['key' => 'dashboard'])
@endsection

@section('content')
<!-- Métricas Principais -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="metric-card">
            <div class="icon blue">
                <i class="bi bi-file-text"></i>
            </div>
            <h3 class="metric-label">Atividades Planejadas</h3>
            <div class="metric-value">{{ $totalAtividades }}</div>
            <p class="metric-description">
                @if($diferencaAtividades > 0)
                    +{{ $diferencaAtividades }} desde o mês passado
                @elseif($diferencaAtividades < 0)
                    {{ $diferencaAtividades }} desde o mês passado
                @else
                    Sem alteração desde o mês passado
                @endif
            </p>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="metric-card">
            <div class="icon green">
                <i class="bi bi-people"></i>
            </div>
            <h3 class="metric-label">Reuniões Agendadas</h3>
            <div class="metric-value">{{ $reunioesAgendadas }}</div>
            <p class="metric-description">{{ $reunioesEstaSemana }} esta semana</p>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="metric-card">
            <div class="icon orange">
                <i class="bi bi-list-check"></i>
            </div>
            <h3 class="metric-label">Tarefas Pendentes</h3>
            <div class="metric-value">{{ $tarefasPendentes }}</div>
            <p class="metric-description">{{ $tarefasPrazoProximo }} com prazo próximo</p>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="metric-card">
            <div class="icon purple">
                <i class="bi bi-graph-up"></i>
            </div>
            <h3 class="metric-label">Taxa de Conclusão</h3>
            <div class="metric-value">{{ $taxaConclusao }}%</div>
            <p class="metric-description">
                @if($diferencaTaxa > 0)
                    +{{ $diferencaTaxa }}% desde o mês passado
                @elseif($diferencaTaxa < 0)
                    {{ $diferencaTaxa }}% desde o mês passado
                @else
                    Sem alteração desde o mês passado
                @endif
            </p>
        </div>
    </div>
</div>

<!-- Seções Principais -->
<div class="row g-4">
    <!-- Atividades Recentes -->
    <div class="col-lg-6">
        <div class="metric-card">
            <h3 class="metric-label mb-3">Atividades Recentes</h3>
            <p class="metric-description mb-4">Últimas atividades realizadas</p>
            
            @forelse($atividadesRecentes as $atividade)
                <div class="activity-item">
                    @php
                        $dotColor = match($atividade->status) {
                            'concluida' => 'green',
                            'em_andamento' => 'blue',
                            'planejada' => 'yellow',
                            default => 'gray'
                        };
                        $statusLabel = match($atividade->status) {
                            'concluida' => 'Concluída',
                            'em_andamento' => 'Em Andamento',
                            'planejada' => 'Planejada',
                            'cancelada' => 'Cancelada',
                            default => $atividade->status
                        };
                        $statusClass = match($atividade->status) {
                            'concluida' => 'status-completed',
                            'em_andamento' => 'status-progress',
                            'planejada' => 'status-scheduled',
                            default => 'status-scheduled'
                        };
                    @endphp
                    <div class="activity-dot {{ $dotColor }}"></div>
                    <div class="activity-content">
                        <h4 class="activity-title">{{ $atividade->titulo }}</h4>
                        <p class="activity-description">
                            @if($atividade->status === 'concluida')
                                Concluída em {{ $atividade->updated_at->format('d/m/Y - H:i') }}
                            @elseif($atividade->status === 'em_andamento')
                                Em andamento - {{ $atividade->local ?? 'Sem local definido' }}
                            @else
                                Agendada para {{ $atividade->data_inicio->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>
                    <span class="activity-status {{ $statusClass }}">{{ $statusLabel }}</span>
                </div>
            @empty
                <p class="text-muted">Nenhuma atividade recente</p>
            @endforelse
        </div>
    </div>
    
    <!-- Próximas Obrigações -->
    <div class="col-lg-6">
        <div class="metric-card">
            <h3 class="metric-label mb-3">Próximas Obrigações</h3>
            <p class="metric-description mb-4">Alertas e convocações importantes</p>
            
            @forelse($obrigacoesProximas as $obrigacao)
                @php
                    $diasRestantes = now()->diffInDays($obrigacao->data_vencimento, false);
                    $iconColor = 'blue';
                    $urgencyClass = 'urgency-scheduled';
                    $urgencyLabel = 'Programada';
                    
                    if($obrigacao->isVencida() || $diasRestantes < 0) {
                        $iconColor = 'red';
                        $urgencyClass = 'urgency-urgent';
                        $urgencyLabel = 'Vencida';
                    } elseif($obrigacao->isUrgente() || $diasRestantes <= 3) {
                        $iconColor = 'red';
                        $urgencyClass = 'urgency-urgent';
                        $urgencyLabel = 'Urgente';
                    } elseif($diasRestantes <= 15) {
                        $iconColor = 'yellow';
                        $urgencyClass = 'urgency-attention';
                        $urgencyLabel = 'Atenção';
                    }
                @endphp
                <div class="obligation-item">
                    <div class="obligation-icon {{ $iconColor }}">
                        <i class="bi bi-bell"></i>
                    </div>
                    <div class="obligation-content">
                        <h4 class="obligation-title">{{ $obrigacao->titulo }}</h4>
                        <p class="obligation-description">
                            @if($diasRestantes < 0)
                                Vencida há {{ abs($diasRestantes) }} dias - {{ $obrigacao->data_vencimento->format('d/m/Y') }}
                            @elseif($diasRestantes == 0)
                                Vence hoje - {{ $obrigacao->data_vencimento->format('d/m/Y') }}
                            @else
                                Vence em {{ $diasRestantes }} dias - {{ $obrigacao->data_vencimento->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>
                    <span class="obligation-urgency {{ $urgencyClass }}">{{ $urgencyLabel }}</span>
                </div>
            @empty
                <p class="text-muted">Nenhuma obrigação próxima</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
