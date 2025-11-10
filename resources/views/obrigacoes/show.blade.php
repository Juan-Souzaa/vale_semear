@extends('layouts.app')

@section('title', $obrigacao->titulo)

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ $obrigacao->titulo }}
                </h1>
                <p class="text-muted">Detalhes da obrigação</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('obrigacoes.edit', $obrigacao) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Editar
                </a>
                <a href="{{ route('obrigacoes.index') }}" class="btn btn-outline-secondary">
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
                            $statusClass = match($obrigacao->status) {
                                'concluida' => 'bg-success',
                                'em_andamento' => 'bg-primary',
                                'vencida' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                            $statusLabel = match($obrigacao->status) {
                                'concluida' => 'Concluída',
                                'em_andamento' => 'Em Andamento',
                                'vencida' => 'Vencida',
                                'pendente' => 'Pendente',
                                default => $obrigacao->status
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tipo</label>
                    <p class="text-muted mb-0">{{ ucfirst($obrigacao->tipo) }}</p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Prioridade</label>
                    <div>
                        @php
                            $prioridadeClass = match($obrigacao->prioridade) {
                                'urgente' => 'bg-danger',
                                'alta' => 'bg-warning',
                                'media' => 'bg-info',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $prioridadeClass }}">{{ ucfirst($obrigacao->prioridade) }}</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Data de Vencimento</label>
                    <p class="text-muted mb-0">
                        {{ $obrigacao->data_vencimento->format('d/m/Y') }}
                        @php
                            $diasRestantes = now()->diffInDays($obrigacao->data_vencimento, false);
                            $isVencida = $obrigacao->data_vencimento < now() && $obrigacao->status != 'concluida';
                        @endphp
                        @if($isVencida)
                            <br><small class="text-danger">Vencida há {{ abs($diasRestantes) }} dia(s)</small>
                        @elseif($diasRestantes >= 0)
                            <br><small class="text-muted">{{ $diasRestantes }} dia(s) restante(s)</small>
                        @endif
                    </p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Data de Lembrete</label>
                    <p class="text-muted mb-0">
                        {{ $obrigacao->data_lembrete ? $obrigacao->data_lembrete->format('d/m/Y') : 'Não definida' }}
                    </p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Responsável</label>
                    <p class="text-muted mb-0">
                        {{ $obrigacao->responsavel ? $obrigacao->responsavel->name : 'Não definido' }}
                    </p>
                </div>

                @if($obrigacao->descricao)
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Descrição</label>
                    <p class="text-muted mb-0" style="white-space: pre-wrap;">{{ $obrigacao->descricao }}</p>
                </div>
                @endif

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Criada em</label>
                    <p class="text-muted mb-0">{{ $obrigacao->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Última atualização</label>
                    <p class="text-muted mb-0">{{ $obrigacao->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

