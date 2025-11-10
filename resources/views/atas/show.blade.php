@extends('layouts.app')

@section('title', 'Ata #' . ($ata->numero ?? $ata->id))

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="bi bi-file-text me-2"></i>Ata #{{ $ata->numero ?? $ata->id }}
                </h1>
                <p class="text-muted">Reunião: {{ $ata->reuniao->titulo }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('reunioes.show', $ata->reuniao) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Voltar
                </a>
                <a href="{{ route('atas.edit', $ata) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Editar
                </a>
                @if(!$ata->aprovada)
                    <form method="POST" action="{{ route('atas.aprovar', $ata) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>Aprovar Ata
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Informações da Ata -->
        <div class="metric-card mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status</label>
                    <div>
                        @if($ata->aprovada)
                            <span class="badge bg-success">Aprovada</span>
                            @if($ata->data_aprovacao)
                                <small class="text-muted d-block">Aprovada em: {{ $ata->data_aprovacao->format('d/m/Y') }}</small>
                            @endif
                        @else
                            <span class="badge bg-warning">Pendente de Aprovação</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Criada por</label>
                    <p class="text-muted mb-0">{{ $ata->criadoPor?->name ?? 'Não definido' }}</p>
                    <small class="text-muted">{{ $ata->created_at->format('d/m/Y H:i') }}</small>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold">Reunião</label>
                    <p class="text-muted mb-0">
                        <a href="{{ route('reunioes.show', $ata->reuniao) }}">{{ $ata->reuniao->titulo }}</a>
                    </p>
                    <small class="text-muted">{{ $ata->reuniao->data_hora->format('d/m/Y H:i') }}</small>
                </div>
            </div>
        </div>

        <!-- Conteúdo da Ata -->
        <div class="metric-card mb-4">
            <h3 class="metric-label mb-3">Conteúdo da Ata</h3>
            <div class="p-3 bg-light rounded">
                <div style="white-space: pre-wrap;">{{ $ata->conteudo }}</div>
            </div>
        </div>

        <!-- Decisões Relacionadas -->
        @if($ata->decisoes->count() > 0)
        <div class="metric-card mb-4">
            <h3 class="metric-label mb-3">Decisões Relacionadas ({{ $ata->decisoes->count() }})</h3>
            @foreach($ata->decisoes as $decisao)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <strong>{{ $decisao->titulo }}</strong>
                        <small class="text-muted d-block">
                            Status: {{ ucfirst($decisao->status) }}
                            @if($decisao->prazo)
                                - Prazo: {{ $decisao->prazo->format('d/m/Y') }}
                            @endif
                        </small>
                    </div>
                    <span class="badge bg-{{ $decisao->status == 'concluida' ? 'success' : ($decisao->status == 'em_andamento' ? 'primary' : 'secondary') }}">
                        {{ ucfirst($decisao->status) }}
                    </span>
                </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection

