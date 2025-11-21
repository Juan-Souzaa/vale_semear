@extends('layouts.app')

@section('title', 'Planejamento')
@section('page-title', 'Planejamento')
@section('page-subtitle', 'Centralização do planejamento e gestão das atividades')

@section('header-actions')
<div class="d-flex align-items-center gap-2">
    @include('components.help-icon', ['key' => 'atividades.index'])
    <a href="{{ route('planejamento.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Nova Atividade
    </a>
</div>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Barra de Busca e Filtros -->
<form method="GET" action="{{ route('planejamento.index') }}" class="row mb-4">
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" name="busca" class="form-control" placeholder="Buscar atividades..." value="{{ request('busca') }}">
        </div>
    </div>
    <div class="col-md-2">
        <select name="tipo" class="form-select">
            <option value="">Todos os tipos</option>
            <option value="mutirao" {{ request('tipo') == 'mutirao' ? 'selected' : '' }}>Mutirão</option>
            <option value="melhoria" {{ request('tipo') == 'melhoria' ? 'selected' : '' }}>Melhoria</option>
            <option value="evento" {{ request('tipo') == 'evento' ? 'selected' : '' }}>Evento</option>
            <option value="treinamento" {{ request('tipo') == 'treinamento' ? 'selected' : '' }}>Treinamento</option>
            <option value="workshop" {{ request('tipo') == 'workshop' ? 'selected' : '' }}>Workshop</option>
        </select>
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">Todos os status</option>
            <option value="planejada" {{ request('status') == 'planejada' ? 'selected' : '' }}>Planejada</option>
            <option value="em_andamento" {{ request('status') == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
            <option value="concluida" {{ request('status') == 'concluida' ? 'selected' : '' }}>Concluída</option>
            <option value="cancelada" {{ request('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-outline-primary w-100">
            <i class="bi bi-funnel me-2"></i>Filtrar
        </button>
    </div>
</form>

<!-- Calendário -->
<div class="metric-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            @php
                $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                $mesAtual = $meses[$mes - 1];
            @endphp
            <h3 class="metric-label mb-1">Agenda de Atividades - {{ $mesAtual }} {{ $ano }}</h3>
            <p class="metric-description">Visualização mensal das atividades planejadas</p>
        </div>
        <div class="btn-group" role="group">
            <a href="{{ route('planejamento.index', ['mes' => $mes == 1 ? 12 : $mes - 1, 'ano' => $mes == 1 ? $ano - 1 : $ano] + request()->except(['mes', 'ano'])) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-chevron-left"></i>
            </a>
            <a href="{{ route('planejamento.index', ['mes' => $mes == 12 ? 1 : $mes + 1, 'ano' => $mes == 12 ? $ano + 1 : $ano] + request()->except(['mes', 'ano'])) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>

    <!-- Calendário Grid -->
    <div class="calendar-grid">
        <!-- Cabeçalho dos dias da semana -->
        <div class="calendar-header">
            <div class="calendar-day-header">Dom</div>
            <div class="calendar-day-header">Seg</div>
            <div class="calendar-day-header">Ter</div>
            <div class="calendar-day-header">Qua</div>
            <div class="calendar-day-header">Qui</div>
            <div class="calendar-day-header">Sex</div>
            <div class="calendar-day-header">Sáb</div>
        </div>

        <!-- Grid do calendário -->
        <div class="calendar-body">
            @php
                $primeiroDia = \Carbon\Carbon::create($ano, $mes, 1);
                $ultimoDia = $primeiroDia->copy()->endOfMonth();
                $diaInicioSemana = $primeiroDia->dayOfWeek;
                $diasNoMes = $ultimoDia->day;
                
                // Agrupar atividades por dia
                $atividadesPorDia = [];
                foreach($atividadesMes as $atividade) {
                    $dia = $atividade->data_inicio->day;
                    if(!isset($atividadesPorDia[$dia])) {
                        $atividadesPorDia[$dia] = [];
                    }
                    $atividadesPorDia[$dia][] = $atividade;
                }
            @endphp
            
            @for($semana = 0; $semana < 6; $semana++)
                <div class="calendar-week">
                    @for($diaSemana = 0; $diaSemana < 7; $diaSemana++)
                        @php
                            $diaNumero = ($semana * 7) + $diaSemana - $diaInicioSemana + 1;
                            $isDiaValido = $diaNumero >= 1 && $diaNumero <= $diasNoMes;
                            $atividadesDoDia = $isDiaValido && isset($atividadesPorDia[$diaNumero]) ? $atividadesPorDia[$diaNumero] : [];
                        @endphp
                        <div class="calendar-day {{ !$isDiaValido ? 'empty' : '' }}">
                            @if($isDiaValido)
                                <div class="day-number">{{ $diaNumero }}</div>
                                @foreach($atividadesDoDia as $atividade)
                                    @php
                                        $tipoClass = match($atividade->tipo) {
                                            'mutirao' => 'mutirao',
                                            'melhoria' => 'melhoria',
                                            'evento' => 'evento',
                                            'treinamento' => 'treinamento',
                                            'workshop' => 'workshop',
                                            default => 'outro'
                                        };
                                    @endphp
                                    <a href="{{ route('planejamento.show', $atividade->id) }}" class="activity-block {{ $tipoClass }}" title="{{ $atividade->titulo }}">
                                        {{ Str::limit($atividade->titulo, 15) }}
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    @endfor
                </div>
            @endfor
        </div>
    </div>
</div>

<style>
.calendar-grid {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
}

.calendar-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    background-color: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.calendar-day-header {
    padding: 0.75rem;
    text-align: center;
    font-weight: 600;
    color: #6b7280;
    font-size: 0.875rem;
}

.calendar-body {
    display: flex;
    flex-direction: column;
}

.calendar-week {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    border-bottom: 1px solid #e5e7eb;
}

.calendar-week:last-child {
    border-bottom: none;
}

.calendar-day {
    min-height: 100px;
    padding: 0.5rem;
    border-right: 1px solid #e5e7eb;
    position: relative;
    background-color: white;
}

.calendar-day:last-child {
    border-right: none;
}

.calendar-day.empty {
    background-color: #f9fafb;
}

.day-number {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.activity-block {
    display: block;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
    color: white;
    margin-bottom: 0.25rem;
    cursor: pointer;
    transition: opacity 0.2s;
    text-decoration: none;
}

.activity-block:hover {
    opacity: 0.8;
    color: white;
}

.activity-block.mutirao {
    background-color: #16a34a;
}

.activity-block.melhoria {
    background-color: #3b82f6;
}

.activity-block.evento {
    background-color: #8b5cf6;
}

.activity-block.treinamento {
    background-color: #f59e0b;
}

.activity-block.workshop {
    background-color: #10b981;
}

.activity-block.outro {
    background-color: #6b7280;
}
</style>
@endsection
