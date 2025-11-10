@extends('layouts.app')

@section('title', 'Editar Obrigação')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="bi bi-pencil me-2"></i>Editar Obrigação
                </h1>
                <p class="text-muted">Editar informações da obrigação</p>
            </div>
            <a href="{{ route('obrigacoes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <div class="metric-card">
            <form id="editForm" method="POST" action="{{ route('obrigacoes.update', $obrigacao) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                               id="titulo" name="titulo" value="{{ old('titulo', $obrigacao->titulo) }}" required>
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                  id="descricao" name="descricao" rows="3">{{ old('descricao', $obrigacao->descricao) }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                        <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                            <option value="">Selecione...</option>
                            <option value="legal" {{ old('tipo', $obrigacao->tipo) == 'legal' ? 'selected' : '' }}>Legal</option>
                            <option value="administrativa" {{ old('tipo', $obrigacao->tipo) == 'administrativa' ? 'selected' : '' }}>Administrativa</option>
                            <option value="financeira" {{ old('tipo', $obrigacao->tipo) == 'financeira' ? 'selected' : '' }}>Financeira</option>
                            <option value="outro" {{ old('tipo', $obrigacao->tipo) == 'outro' ? 'selected' : '' }}>Outro</option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="data_vencimento" class="form-label">Data de Vencimento <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('data_vencimento') is-invalid @enderror" 
                               id="data_vencimento" name="data_vencimento" value="{{ old('data_vencimento', $obrigacao->data_vencimento->format('Y-m-d')) }}" required>
                        @error('data_vencimento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="data_lembrete" class="form-label">Data de Lembrete</label>
                        <input type="date" class="form-control @error('data_lembrete') is-invalid @enderror" 
                               id="data_lembrete" name="data_lembrete" value="{{ old('data_lembrete', $obrigacao->data_lembrete?->format('Y-m-d')) }}">
                        <small class="text-muted">Data para enviar lembrete (deve ser antes ou igual à data de vencimento)</small>
                        @error('data_lembrete')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="prioridade" class="form-label">Prioridade <span class="text-danger">*</span></label>
                        <select class="form-select @error('prioridade') is-invalid @enderror" id="prioridade" name="prioridade" required>
                            <option value="baixa" {{ old('prioridade', $obrigacao->prioridade) == 'baixa' ? 'selected' : '' }}>Baixa</option>
                            <option value="media" {{ old('prioridade', $obrigacao->prioridade) == 'media' ? 'selected' : '' }}>Média</option>
                            <option value="alta" {{ old('prioridade', $obrigacao->prioridade) == 'alta' ? 'selected' : '' }}>Alta</option>
                            <option value="urgente" {{ old('prioridade', $obrigacao->prioridade) == 'urgente' ? 'selected' : '' }}>Urgente</option>
                        </select>
                        @error('prioridade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="pendente" {{ old('status', $obrigacao->status) == 'pendente' ? 'selected' : '' }}>Pendente</option>
                            <option value="em_andamento" {{ old('status', $obrigacao->status) == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="concluida" {{ old('status', $obrigacao->status) == 'concluida' ? 'selected' : '' }}>Concluída</option>
                            <option value="vencida" {{ old('status', $obrigacao->status) == 'vencida' ? 'selected' : '' }}>Vencida</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="responsavel_id" class="form-label">Responsável</label>
                        <select class="form-select @error('responsavel_id') is-invalid @enderror" id="responsavel_id" name="responsavel_id">
                            <option value="">Selecione...</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ old('responsavel_id', $obrigacao->responsavel_id) == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('responsavel_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <button type="button" class="btn btn-outline-danger" id="btnExcluir">
                            <i class="bi bi-trash me-2"></i>Excluir
                        </button>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('obrigacoes.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Salvar Alterações
                        </button>
                    </div>
                </div>
            </form>
            
            <form id="deleteForm" method="POST" action="{{ route('obrigacoes.destroy', $obrigacao) }}" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnExcluir = document.getElementById('btnExcluir');
    const deleteForm = document.getElementById('deleteForm');
    
    if (btnExcluir && deleteForm) {
        btnExcluir.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Esta ação não pode ser desfeita!',
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
        });
    }
});
</script>
@endpush
@endsection

