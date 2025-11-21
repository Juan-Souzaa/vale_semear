@extends('layouts.app')

@section('title', 'Reuniões')
@section('page-title', 'Gestão de Reuniões')
@section('page-subtitle', 'Agenda, convites, atas e decisões')

@section('header-actions')
<div class="d-flex align-items-center gap-2">
    @include('components.help-icon', ['key' => 'reunioes.index'])
    <a href="{{ route('reunioes.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Nova Reunião
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

<div class="row g-4">
    <!-- Próximas Reuniões -->
    <div class="col-lg-8">
        <div class="metric-card">
            <h3 class="metric-label mb-3">Próximas Reuniões</h3>
            
            @forelse($proximasReunioes as $reuniao)
                @php
                    $iconColor = match($reuniao->status) {
                        'confirmada' => 'green',
                        'agendada' => 'blue',
                        'em_andamento' => 'orange',
                        default => 'gray'
                    };
                    $statusClass = match($reuniao->status) {
                        'confirmada' => 'confirmed',
                        'agendada' => 'scheduled',
                        'em_andamento' => 'progress',
                        default => 'scheduled'
                    };
                    $statusLabel = match($reuniao->status) {
                        'confirmada' => 'Confirmada',
                        'agendada' => 'Agendada',
                        'em_andamento' => 'Em Andamento',
                        default => ucfirst($reuniao->status)
                    };
                @endphp
                <div class="meeting-item">
                    <div class="meeting-icon {{ $iconColor }}">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="meeting-content">
                        <h4 class="meeting-title">{{ $reuniao->titulo }}</h4>
                        <p class="meeting-details">{{ $reuniao->data_hora->format('d/m/Y - H:i') }}</p>
                        <p class="meeting-location">{{ $reuniao->local ?? 'Local não definido' }}</p>
                    </div>
                    <div class="meeting-actions">
                        <span class="meeting-status {{ $statusClass }}">{{ $statusLabel }}</span>
                        <a href="{{ route('reunioes.show', $reuniao) }}" class="meeting-link">Ver Detalhes</a>
                    </div>
                </div>
            @empty
                <p class="text-muted">Nenhuma reunião agendada</p>
            @endforelse
        </div>

        <!-- Histórico de Reuniões -->
        <div class="metric-card mt-4">
            <h3 class="metric-label mb-3">Histórico de Reuniões</h3>
            <p class="metric-description mb-4">Atas e decisões das reuniões anteriores</p>
            
            @forelse($reunioesPassadas as $reuniao)
                <div class="meeting-item">
                    <div class="meeting-icon gray">
                        <i class="bi bi-file-text"></i>
                    </div>
                    <div class="meeting-content">
                        <h4 class="meeting-title">{{ $reuniao->titulo }}</h4>
                        <p class="meeting-details">{{ $reuniao->data_hora->format('d/m/Y') }}</p>
                    </div>
                    <div class="meeting-actions">
                        <span class="meeting-status approved">Concluída</span>
                        <a href="{{ route('reunioes.show', $reuniao) }}" class="meeting-link">Visualizar</a>
                    </div>
                </div>
            @empty
                <p class="text-muted">Nenhuma reunião passada</p>
            @endforelse
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="col-lg-4">
        <div class="metric-card">
            <h3 class="metric-label mb-3">Ações Rápidas</h3>
            
            <div class="d-grid gap-2">
                <a href="{{ route('reunioes.create') }}" class="btn btn-outline-primary">
                    <i class="bi bi-calendar3 me-2"></i>Agendar Reunião
                </a>
                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#criarAtaModal">
                    <i class="bi bi-file-text me-2"></i>Criar Ata
                </button>
                <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#enviarConvitesModal">
                    <i class="bi bi-people me-2"></i>Enviar Convites
                </button>
                <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#definirLembretesModal">
                    <i class="bi bi-bell me-2"></i>Definir Lembretes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Criar Ata -->
<div class="modal fade" id="criarAtaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Criar Ata</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Selecione uma reunião para criar a ata:</p>
                @php
                    $todasReunioes = $proximasReunioes->concat($reunioesPassadas);
                @endphp
                @if($todasReunioes->count() > 0)
                    <div class="list-group">
                        @foreach($todasReunioes as $reuniao)
                            <a href="{{ route('atas.create', $reuniao) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $reuniao->titulo }}</h6>
                                    <small>{{ $reuniao->data_hora->format('d/m/Y') }}</small>
                                </div>
                                <p class="mb-1 text-muted small">{{ $reuniao->tipo }}</p>
                            </a>
                        @endforeach
                    </div>
                    <p class="text-muted small mt-2">Clique em uma reunião para criar a ata.</p>
                @else
                    <p class="text-muted">Nenhuma reunião disponível. <a href="{{ route('reunioes.create') }}">Crie uma reunião primeiro</a>.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Enviar Convites -->
<div class="modal fade" id="enviarConvitesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enviar Convites</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Selecione uma reunião para enviar convites:</p>
                @if($proximasReunioes->count() > 0)
                    <div class="list-group">
                        @foreach($proximasReunioes as $reuniao)
                            <a href="{{ route('reunioes.show', $reuniao) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $reuniao->titulo }}</h6>
                                    <small>{{ $reuniao->data_hora->format('d/m/Y H:i') }}</small>
                                </div>
                                <p class="mb-1 text-muted small">{{ $reuniao->participantes->count() }} participante(s)</p>
                            </a>
                        @endforeach
                    </div>
                    <p class="text-muted small mt-2">Clique em uma reunião para gerenciar participantes e enviar convites.</p>
                @else
                    <p class="text-muted">Nenhuma reunião agendada. <a href="{{ route('reunioes.create') }}">Agende uma reunião primeiro</a>.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Definir Lembretes -->
<div class="modal fade" id="definirLembretesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Definir Lembretes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Selecione uma reunião para definir lembretes:</p>
                @if($proximasReunioes->count() > 0)
                    <div class="list-group">
                        @foreach($proximasReunioes as $reuniao)
                            <a href="{{ route('reunioes.show', $reuniao) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $reuniao->titulo }}</h6>
                                    <small>{{ $reuniao->data_hora->format('d/m/Y H:i') }}</small>
                                </div>
                                <p class="mb-1 text-muted small">Local: {{ $reuniao->local ?? 'Não definido' }}</p>
                                @if($reuniao->lembretes && $reuniao->lembretes->count() > 0)
                                    <small class="text-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        {{ $reuniao->lembretes->count() }} lembrete(s) configurado(s)
                                    </small>
                                @endif
                            </a>
                        @endforeach
                    </div>
                    <p class="text-muted small mt-2">Clique em uma reunião para configurar lembretes na página de detalhes.</p>
                @else
                    <p class="text-muted">Nenhuma reunião agendada. <a href="{{ route('reunioes.create') }}">Agende uma reunião primeiro</a>.</p>
                @endif
            </div>
        </div>
    </div>

<style>
.meeting-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.meeting-item:last-child {
    border-bottom: none;
}

.meeting-icon {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
}

.meeting-icon.blue { background-color: #3b82f6; }
.meeting-icon.green { background-color: #10b981; }
.meeting-icon.orange { background-color: #f59e0b; }
.meeting-icon.gray { background-color: #6b7280; }

.meeting-content {
    flex: 1;
}

.meeting-title {
    font-weight: 600;
    color: var(--dark-color);
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
}

.meeting-details {
    color: #6b7280;
    font-size: 0.875rem;
    margin: 0 0 0.25rem 0;
}

.meeting-location {
    color: #9ca3af;
    font-size: 0.75rem;
    margin: 0;
}

.meeting-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.5rem;
}

.meeting-status {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.meeting-status.confirmed {
    background-color: #dcfce7;
    color: #16a34a;
}

.meeting-status.scheduled {
    background-color: #f3f4f6;
    color: #6b7280;
}

.meeting-status.approved {
    background-color: #f3f4f6;
    color: #6b7280;
}

.meeting-link {
    color: var(--primary-color);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
}

.meeting-link:hover {
    text-decoration: underline;
}
</style>
@endsection
