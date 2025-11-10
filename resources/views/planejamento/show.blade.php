@extends('layouts.app')

@section('title', $atividade->titulo)

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="bi bi-file-text me-2"></i>{{ $atividade->titulo }}
                </h1>
                <p class="text-muted">Detalhes da atividade</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('planejamento.edit', $atividade->id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Editar
                </a>
                <a href="{{ route('planejamento.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Voltar
                </a>
            </div>
        </div>

        <div class="metric-card mb-4">
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Status</label>
                    <div>
                        @php
                            $statusClass = match($atividade->status) {
                                'concluida' => 'bg-success',
                                'em_andamento' => 'bg-primary',
                                'planejada' => 'bg-secondary',
                                'cancelada' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                            $statusLabel = match($atividade->status) {
                                'concluida' => 'Concluída',
                                'em_andamento' => 'Em Andamento',
                                'planejada' => 'Planejada',
                                'cancelada' => 'Cancelada',
                                default => $atividade->status
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tipo</label>
                    <p class="text-muted mb-0">{{ ucfirst($atividade->tipo) }}</p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Responsável</label>
                    <p class="text-muted mb-0">
                        {{ $atividade->responsavel ? $atividade->responsavel->name : 'Não definido' }}
                    </p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Data de Início</label>
                    <p class="text-muted mb-0">{{ $atividade->data_inicio->format('d/m/Y') }}</p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Data de Término</label>
                    <p class="text-muted mb-0">
                        {{ $atividade->data_fim ? $atividade->data_fim->format('d/m/Y') : 'Não definida' }}
                    </p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Local</label>
                    <p class="text-muted mb-0">{{ $atividade->local ?? 'Não definido' }}</p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Orçamento</label>
                    <p class="text-muted mb-0">
                        {{ $atividade->orcamento ? 'R$ ' . number_format($atividade->orcamento, 2, ',', '.') : 'Não definido' }}
                    </p>
                </div>

                @if($atividade->descricao)
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Descrição</label>
                    <p class="text-muted mb-0">{{ $atividade->descricao }}</p>
                </div>
                @endif

                @if($atividade->observacoes)
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Observações</label>
                    <p class="text-muted mb-0">{{ $atividade->observacoes }}</p>
                </div>
                @endif

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Criada em</label>
                    <p class="text-muted mb-0">{{ $atividade->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Última atualização</label>
                    <p class="text-muted mb-0">{{ $atividade->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

