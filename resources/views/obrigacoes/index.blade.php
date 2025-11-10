@extends('layouts.app')

@section('title', 'Obrigações')
@section('page-title', 'Obrigações')
@section('page-subtitle', 'Gestão de obrigações e compromissos da associação')

@section('header-actions')
<a href="{{ route('obrigacoes.create') }}" class="btn btn-primary me-2">
    <i class="bi bi-plus-lg me-2"></i>Nova Obrigação
</a>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Barra de Busca e Filtros -->
<form method="GET" action="{{ route('obrigacoes.index') }}" class="row mb-4">
    <div class="col-md-5">
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" name="busca" class="form-control" placeholder="Buscar obrigações..." value="{{ request('busca') }}">
        </div>
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">Todos os status</option>
            <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
            <option value="em_andamento" {{ request('status') == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
            <option value="concluida" {{ request('status') == 'concluida' ? 'selected' : '' }}>Concluída</option>
            <option value="vencida" {{ request('status') == 'vencida' ? 'selected' : '' }}>Vencida</option>
        </select>
    </div>
    <div class="col-md-2">
        <select name="prioridade" class="form-select">
            <option value="">Todas as prioridades</option>
            <option value="baixa" {{ request('prioridade') == 'baixa' ? 'selected' : '' }}>Baixa</option>
            <option value="media" {{ request('prioridade') == 'media' ? 'selected' : '' }}>Média</option>
            <option value="alta" {{ request('prioridade') == 'alta' ? 'selected' : '' }}>Alta</option>
            <option value="urgente" {{ request('prioridade') == 'urgente' ? 'selected' : '' }}>Urgente</option>
        </select>
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-outline-primary w-100">
            <i class="bi bi-funnel me-2"></i>Filtrar
        </button>
    </div>
</form>

<!-- Lista de Obrigações -->
<div class="metric-card">
    <h3 class="metric-label mb-3">Obrigações ({{ $obrigacoes->count() }})</h3>
    
    @if($obrigacoes->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th class="d-none d-md-table-cell">Tipo</th>
                        <th>Vencimento</th>
                        <th class="d-none d-lg-table-cell">Prioridade</th>
                        <th class="d-none d-sm-table-cell">Status</th>
                        <th class="d-none d-xl-table-cell">Responsável</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($obrigacoes as $obrigacao)
                        @php
                            $diasRestantes = now()->diffInDays($obrigacao->data_vencimento, false);
                            $isVencida = $obrigacao->data_vencimento < now() && $obrigacao->status != 'concluida';
                        @endphp
                        <tr class="{{ $isVencida ? 'table-danger' : ($diasRestantes <= 3 && $diasRestantes >= 0 ? 'table-warning' : '') }}">
                            <td>
                                <strong>{{ $obrigacao->titulo }}</strong>
                                @if($obrigacao->descricao)
                                    <br><small class="text-muted d-none d-md-inline">{{ Str::limit($obrigacao->descricao, 50) }}</small>
                                @endif
                                <div class="d-md-none mt-1">
                                    <span class="badge bg-secondary">{{ ucfirst($obrigacao->tipo) }}</span>
                                    @php
                                        $prioridadeClass = match($obrigacao->prioridade) {
                                            'urgente' => 'danger',
                                            'alta' => 'warning',
                                            'media' => 'info',
                                            default => 'secondary'
                                        };
                                        $statusClass = match($obrigacao->status) {
                                            'concluida' => 'success',
                                            'em_andamento' => 'primary',
                                            'vencida' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $prioridadeClass }} ms-1">{{ ucfirst($obrigacao->prioridade) }}</span>
                                    <span class="badge bg-{{ $statusClass }} ms-1">{{ ucfirst($obrigacao->status) }}</span>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <span class="badge bg-secondary">{{ ucfirst($obrigacao->tipo) }}</span>
                            </td>
                            <td>
                                {{ $obrigacao->data_vencimento->format('d/m/Y') }}
                                @if($isVencida)
                                    <br><small class="text-danger">Vencida há {{ abs($diasRestantes) }} dia(s)</small>
                                @elseif($diasRestantes >= 0)
                                    <br><small class="text-muted">{{ $diasRestantes }} dia(s) restante(s)</small>
                                @endif
                            </td>
                            <td class="d-none d-lg-table-cell">
                                @php
                                    $prioridadeClass = match($obrigacao->prioridade) {
                                        'urgente' => 'danger',
                                        'alta' => 'warning',
                                        'media' => 'info',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $prioridadeClass }}">{{ ucfirst($obrigacao->prioridade) }}</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                @php
                                    $statusClass = match($obrigacao->status) {
                                        'concluida' => 'success',
                                        'em_andamento' => 'primary',
                                        'vencida' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($obrigacao->status) }}</span>
                            </td>
                            <td class="d-none d-xl-table-cell">
                                {{ $obrigacao->responsavel?->name ?? 'Não definido' }}
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="{{ route('obrigacoes.show', $obrigacao) }}" class="btn btn-sm btn-outline-primary" title="Ver detalhes">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('obrigacoes.edit', $obrigacao) }}" class="btn btn-sm btn-outline-secondary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger btnExcluir" data-id="{{ $obrigacao->id }}" data-titulo="{{ $obrigacao->titulo }}" title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <form id="deleteForm{{ $obrigacao->id }}" method="POST" action="{{ route('obrigacoes.destroy', $obrigacao) }}" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #9ca3af;"></i>
            <p class="text-muted mt-3">Nenhuma obrigação encontrada.</p>
            <a href="{{ route('obrigacoes.create') }}" class="btn btn-primary mt-2">
                <i class="bi bi-plus-lg me-2"></i>Criar Primeira Obrigação
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const botoesExcluir = document.querySelectorAll('.btnExcluir');
    
    botoesExcluir.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const obrigacaoId = this.getAttribute('data-id');
            const obrigacaoTitulo = this.getAttribute('data-titulo');
            const deleteForm = document.getElementById('deleteForm' + obrigacaoId);
            
            if (deleteForm) {
                Swal.fire({
                    title: 'Tem certeza?',
                    html: `Deseja realmente excluir a obrigação <strong>"${obrigacaoTitulo}"</strong>?<br>Esta ação não pode ser desfeita!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteForm.submit();
                    }
                });
            }
        });
    });
});
</script>
@endpush
@endsection

