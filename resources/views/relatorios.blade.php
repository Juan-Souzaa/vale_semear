@extends('layouts.app')

@section('title', 'Relatórios')
@section('page-title', 'Relatórios')
@section('page-subtitle', 'Geração de relatórios com dados das atividades realizadas')

@section('header-actions')
@include('components.help-icon', ['key' => 'relatorios.index'])
@endsection

@section('content')

<!-- Filtro de Período -->
<div class="row mb-4">
    <div class="col-12">
        <div class="metric-card">
            <h3 class="metric-label mb-3">Filtro de Período</h3>
            <form method="GET" action="{{ route('relatorios') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="periodo_inicio" class="form-label">Data Início</label>
                    <input type="date" class="form-control" id="periodo_inicio" name="periodo_inicio" 
                           value="{{ $periodoInicio }}" required>
                </div>
                <div class="col-md-4">
                    <label for="periodo_fim" class="form-label">Data Fim</label>
                    <input type="date" class="form-control" id="periodo_fim" name="periodo_fim" 
                           value="{{ $periodoFim }}" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-2"></i>Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Seção de Relatórios -->
<div class="row g-4 mb-5 report-cards-row">
    <div class="col-12">
        <h3 class="metric-label mb-3">Relatórios - Período: {{ \Carbon\Carbon::parse($periodoInicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($periodoFim)->format('d/m/Y') }}</h3>
        <p class="metric-description mb-4">Geração de relatórios com dados das atividades realizadas</p>
    </div>

    <!-- Relatório de Atividades -->
    <div class="col-md-4">
        <div class="metric-card d-flex flex-column h-100">
            <h4 class="metric-label mb-2">Relatório de Atividades</h4>
            <p class="metric-description mb-3">Resumo das atividades realizadas no período</p>
            
            <div class="report-metrics flex-grow-1">
                <div class="report-metric">
                    <span class="metric-label">Atividades Concluídas:</span>
                    <span class="metric-value">{{ $atividadesConcluidas }}</span>
                </div>
                <div class="report-metric">
                    <span class="metric-label">Em Andamento:</span>
                    <span class="metric-value">{{ $atividadesEmAndamento }}</span>
                </div>
                <div class="report-metric">
                    <span class="metric-label">Taxa de Sucesso:</span>
                    <span class="metric-value">{{ $taxaSucesso }}%</span>
                </div>
            </div>
            
            <div class="d-grid gap-2 mt-auto">
                <a href="{{ route('relatorios.exportar', ['tipo' => 'atividades', 'formato' => 'csv']) }}?periodo_inicio={{ $periodoInicio }}&periodo_fim={{ $periodoFim }}" 
                   class="btn btn-outline-secondary">
                    <i class="bi bi-filetype-csv me-2"></i>Exportar CSV
                </a>
                <a href="{{ route('relatorios.exportar', ['tipo' => 'atividades', 'formato' => 'pdf']) }}?periodo_inicio={{ $periodoInicio }}&periodo_fim={{ $periodoFim }}" 
                   class="btn btn-outline-danger">
                    <i class="bi bi-filetype-pdf me-2"></i>Exportar PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Relatório de Reuniões -->
    <div class="col-md-4">
        <div class="metric-card d-flex flex-column h-100">
            <h4 class="metric-label mb-2">Relatório de Reuniões</h4>
            <p class="metric-description mb-3">Estatísticas das reuniões realizadas</p>
            
            <div class="report-metrics flex-grow-1">
                <div class="report-metric">
                    <span class="metric-label">Reuniões Realizadas:</span>
                    <span class="metric-value">{{ $reunioesRealizadas }}</span>
                </div>
                <div class="report-metric">
                    <span class="metric-label">Participação Média:</span>
                    <span class="metric-value">{{ round($participacaoMedia) }} pessoas</span>
                </div>
                <div class="report-metric">
                    <span class="metric-label">Decisões Tomadas:</span>
                    <span class="metric-value">{{ $decisoesTomadas }}</span>
                </div>
            </div>
            
            <div class="d-grid gap-2 mt-auto">
                <a href="{{ route('relatorios.exportar', ['tipo' => 'reunioes', 'formato' => 'csv']) }}?periodo_inicio={{ $periodoInicio }}&periodo_fim={{ $periodoFim }}" 
                   class="btn btn-outline-secondary">
                    <i class="bi bi-filetype-csv me-2"></i>Exportar CSV
                </a>
                <a href="{{ route('relatorios.exportar', ['tipo' => 'reunioes', 'formato' => 'pdf']) }}?periodo_inicio={{ $periodoInicio }}&periodo_fim={{ $periodoFim }}" 
                   class="btn btn-outline-danger">
                    <i class="bi bi-filetype-pdf me-2"></i>Exportar PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Relatório Financeiro -->
    <div class="col-md-4">
        <div class="metric-card d-flex flex-column h-100">
            <h4 class="metric-label mb-2">Relatório Financeiro</h4>
            <p class="metric-description mb-3">Resumo dos gastos com atividades</p>
            
            <div class="report-metrics flex-grow-1">
                <div class="report-metric">
                    <span class="metric-label">Orçamento Utilizado:</span>
                    <span class="metric-value">R$ {{ number_format($orcamentoUtilizado, 2, ',', '.') }}</span>
                </div>
            </div>
            
            <div class="d-grid gap-2 mt-auto">
                <a href="{{ route('relatorios.exportar', ['tipo' => 'financeiro', 'formato' => 'csv']) }}?periodo_inicio={{ $periodoInicio }}&periodo_fim={{ $periodoFim }}" 
                   class="btn btn-outline-secondary">
                    <i class="bi bi-filetype-csv me-2"></i>Exportar CSV
                </a>
                <a href="{{ route('relatorios.exportar', ['tipo' => 'financeiro', 'formato' => 'pdf']) }}?periodo_inicio={{ $periodoInicio }}&periodo_fim={{ $periodoFim }}" 
                   class="btn btn-outline-danger">
                    <i class="bi bi-filetype-pdf me-2"></i>Exportar PDF
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Seção de Melhorias e Boas Práticas -->
<div class="row">
    <div class="col-12">
        <h3 class="metric-label mb-3">Melhorias e Boas Práticas</h3>
        <p class="metric-description mb-4">Registro de melhorias identificadas e boas práticas implementadas</p>
    </div>

    <!-- Boa Prática Identificada -->
    <div class="col-12 mb-3">
        <div class="improvement-card good-practice">
            <div class="improvement-content">
                <p class="improvement-text">
                    <strong>Boa Prática Identificada:</strong><br>
                    Implementação de sistema de lembretes automáticos reduziu em 40% o número de ausências em reuniões.
                </p>
                <p class="improvement-date">Registrado em: 15/06/2025</p>
            </div>
        </div>
    </div>

    <!-- Melhoria Implementada -->
    <div class="col-12 mb-3">
        <div class="improvement-card improvement">
            <div class="improvement-content">
                <p class="improvement-text">
                    <strong>Melhoria Implementada:</strong><br>
                    Novo formato de atas com seções padronizadas melhorou a clareza das decisões tomadas.
                </p>
                <p class="improvement-date">Registrado em: 10/06/2025</p>
            </div>
        </div>
    </div>

    <!-- Oportunidade de Melhoria -->
    <div class="col-12">
        <div class="improvement-card opportunity">
            <div class="improvement-content">
                <p class="improvement-text">
                    <strong>Oportunidade de Melhoria:</strong><br>
                    Integração com sistema de e-mail para envio automático de convites e lembretes.
                </p>
                <p class="improvement-date">Identificado em: 08/06/2025</p>
            </div>
        </div>
    </div>
</div>

<style>
.report-cards-row .col-md-4 {
    display: flex;
}

.report-cards-row .metric-card {
    width: 100%;
}

.report-metrics {
    margin-bottom: 1rem;
    flex-grow: 1;
}

.report-metric {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.report-metric:last-child {
    border-bottom: none;
}

.report-metric .metric-label {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
}

.report-metric .metric-value {
    font-weight: 600;
    color: var(--dark-color);
    font-size: 0.875rem;
}

.improvement-card {
    border-radius: 8px;
    padding: 1.5rem;
    border-left: 4px solid;
}

.improvement-card.good-practice {
    background-color: #f0fdf4;
    border-left-color: #10b981;
}

.improvement-card.improvement {
    background-color: #eff6ff;
    border-left-color: #3b82f6;
}

.improvement-card.opportunity {
    background-color: #fffbeb;
    border-left-color: #f59e0b;
}

.improvement-content {
    margin: 0;
}

.improvement-text {
    color: var(--dark-color);
    margin: 0 0 0.75rem 0;
    line-height: 1.5;
}

.improvement-date {
    color: #6b7280;
    font-size: 0.875rem;
    margin: 0;
}
</style>

@endsection
