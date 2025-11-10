@extends('layouts.app')

@section('title', 'Decisão: ' . $decisao->titulo)

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
                    <i class="bi bi-check-circle me-2"></i>{{ $decisao->titulo }}
                </h1>
                <p class="text-muted">Decisão da reunião: {{ $decisao->reuniao->titulo }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('reunioes.show', $decisao->reuniao) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Voltar
                </a>
                <a href="{{ route('decisoes.edit', $decisao) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Editar
                </a>
            </div>
        </div>

        <!-- Informações da Decisão -->
        <div class="metric-card mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status</label>
                    <div>
                        <span class="badge bg-{{ $decisao->status == 'concluida' ? 'success' : ($decisao->status == 'em_andamento' ? 'primary' : 'secondary') }}">
                            {{ ucfirst($decisao->status) }}
                        </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Prazo</label>
                    <p class="text-muted mb-0">
                        {{ $decisao->prazo ? $decisao->prazo->format('d/m/Y') : 'Não definido' }}
                    </p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Responsável</label>
                    <p class="text-muted mb-0">{{ $decisao->responsavel?->name ?? 'Não definido' }}</p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Reunião</label>
                    <p class="text-muted mb-0">
                        <a href="{{ route('reunioes.show', $decisao->reuniao) }}">{{ $decisao->reuniao->titulo }}</a>
                    </p>
                    <small class="text-muted">{{ $decisao->reuniao->data_hora->format('d/m/Y H:i') }}</small>
                </div>

                @if($decisao->ata)
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Ata Relacionada</label>
                    <p class="text-muted mb-0">
                        <a href="{{ route('atas.show', $decisao->ata) }}">{{ $decisao->ata->numero ?? 'Ata #' . $decisao->ata->id }}</a>
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Descrição da Decisão -->
        @if($decisao->descricao)
        <div class="metric-card mb-4">
            <h3 class="metric-label mb-3">Descrição</h3>
            <div class="p-3 bg-light rounded">
                <div style="white-space: pre-wrap;">{{ $decisao->descricao }}</div>
            </div>
        </div>
        @endif

        <!-- Tarefas Relacionadas -->
        @if($decisao->tarefas && $decisao->tarefas->count() > 0)
        <div class="metric-card mb-4">
            <h3 class="metric-label mb-3">Tarefas Relacionadas ({{ $decisao->tarefas->count() }})</h3>
            @foreach($decisao->tarefas as $tarefa)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <strong>{{ $tarefa->titulo }}</strong>
                        <small class="text-muted d-block">
                            Status: {{ ucfirst($tarefa->status) }}
                            @if($tarefa->data_vencimento)
                                - Vencimento: {{ $tarefa->data_vencimento->format('d/m/Y') }}
                            @endif
                        </small>
                    </div>
                    <span class="badge bg-{{ $tarefa->status == 'concluida' ? 'success' : ($tarefa->status == 'em_andamento' ? 'primary' : 'secondary') }}">
                        {{ ucfirst($tarefa->status) }}
                    </span>
                </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection

