@extends('layouts.app')

@section('title', 'Editar Decisão')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="bi bi-pencil me-2"></i>Editar Decisão
                </h1>
                <p class="text-muted">Editar decisão da reunião: {{ $decisao->reuniao->titulo }}</p>
            </div>
            <a href="{{ route('decisoes.show', $decisao) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <div class="metric-card">
            <form method="POST" action="{{ route('decisoes.update', $decisao) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                               id="titulo" name="titulo" value="{{ old('titulo', $decisao->titulo) }}" required>
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                  id="descricao" name="descricao" rows="5">{{ old('descricao', $decisao->descricao) }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="ata_id" class="form-label">Ata Relacionada</label>
                        <select class="form-select @error('ata_id') is-invalid @enderror" id="ata_id" name="ata_id">
                            <option value="">Nenhuma</option>
                            @foreach($atas as $ata)
                                <option value="{{ $ata->id }}" {{ old('ata_id', $decisao->ata_id) == $ata->id ? 'selected' : '' }}>
                                    {{ $ata->numero ?? 'Ata #' . $ata->id }}
                                </option>
                            @endforeach
                        </select>
                        @error('ata_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="pendente" {{ old('status', $decisao->status) == 'pendente' ? 'selected' : '' }}>Pendente</option>
                            <option value="em_andamento" {{ old('status', $decisao->status) == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="concluida" {{ old('status', $decisao->status) == 'concluida' ? 'selected' : '' }}>Concluída</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="prazo" class="form-label">Prazo</label>
                        <input type="date" class="form-control @error('prazo') is-invalid @enderror" 
                               id="prazo" name="prazo" value="{{ old('prazo', $decisao->prazo?->format('Y-m-d')) }}">
                        @error('prazo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="responsavel_id" class="form-label">Responsável</label>
                        <select class="form-select @error('responsavel_id') is-invalid @enderror" id="responsavel_id" name="responsavel_id">
                            <option value="">Não definido</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ old('responsavel_id', $decisao->responsavel_id) == $usuario->id ? 'selected' : '' }}>
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
                        <a href="{{ route('decisoes.show', $decisao) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Salvar Alterações
                        </button>
                    </div>
                </div>
            </form>
            
            <form id="deleteForm" method="POST" action="{{ route('decisoes.destroy', $decisao) }}" style="display: none;">
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

