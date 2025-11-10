@extends('layouts.app')

@section('title', 'Editar Reunião')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="bi bi-pencil me-2"></i>Editar Reunião
                </h1>
                <p class="text-muted">Editar informações da reunião</p>
            </div>
            <a href="{{ route('reunioes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <div class="metric-card">
            <form method="POST" action="{{ route('reunioes.update', $reuniao) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                               id="titulo" name="titulo" value="{{ old('titulo', $reuniao->titulo) }}" required>
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                  id="descricao" name="descricao" rows="3">{{ old('descricao', $reuniao->descricao) }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                        <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                            <option value="">Selecione...</option>
                            <option value="diretoria" {{ old('tipo', $reuniao->tipo) == 'diretoria' ? 'selected' : '' }}>Diretoria</option>
                            <option value="assembleia" {{ old('tipo', $reuniao->tipo) == 'assembleia' ? 'selected' : '' }}>Assembleia</option>
                            <option value="comissao" {{ old('tipo', $reuniao->tipo) == 'comissao' ? 'selected' : '' }}>Comissão</option>
                            <option value="outro" {{ old('tipo', $reuniao->tipo) == 'outro' ? 'selected' : '' }}>Outro</option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="agendada" {{ old('status', $reuniao->status) == 'agendada' ? 'selected' : '' }}>Agendada</option>
                            <option value="confirmada" {{ old('status', $reuniao->status) == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                            <option value="em_andamento" {{ old('status', $reuniao->status) == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="concluida" {{ old('status', $reuniao->status) == 'concluida' ? 'selected' : '' }}>Concluída</option>
                            <option value="cancelada" {{ old('status', $reuniao->status) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="data_hora" class="form-label">Data e Hora <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('data_hora') is-invalid @enderror" 
                               id="data_hora" name="data_hora" 
                               value="{{ old('data_hora', $reuniao->data_hora->format('Y-m-d\TH:i')) }}" required>
                        @error('data_hora')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="local" class="form-label">Local</label>
                        <input type="text" class="form-control @error('local') is-invalid @enderror" 
                               id="local" name="local" value="{{ old('local', $reuniao->local) }}">
                        @error('local')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="participantes" class="form-label">Participantes</label>
                        <select class="form-select @error('participantes') is-invalid @enderror" 
                                id="participantes" name="participantes[]" multiple size="5">
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ in_array($usuario->id, old('participantes', $participantesIds)) ? 'selected' : '' }}>
                                    {{ $usuario->name }} ({{ $usuario->email }})
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Segure Ctrl (Windows) ou Cmd (Mac) para selecionar múltiplos participantes</small>
                        @error('participantes')
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
                        <a href="{{ route('reunioes.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Salvar Alterações
                        </button>
                    </div>
                </div>
            </form>
            
            <form id="deleteForm" method="POST" action="{{ route('reunioes.destroy', $reuniao) }}" style="display: none;">
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

