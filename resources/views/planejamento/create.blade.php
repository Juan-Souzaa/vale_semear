@extends('layouts.app')

@section('title', 'Nova Atividade')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="bi bi-plus-circle me-2"></i>Nova Atividade
                </h1>
                <p class="text-muted">Criar uma nova atividade no planejamento</p>
            </div>
            <a href="{{ route('planejamento.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <div class="metric-card">
            <form method="POST" action="{{ route('planejamento.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                               id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                  id="descricao" name="descricao" rows="3">{{ old('descricao') }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                        <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                            <option value="">Selecione...</option>
                            <option value="mutirao" {{ old('tipo') == 'mutirao' ? 'selected' : '' }}>Mutirão</option>
                            <option value="melhoria" {{ old('tipo') == 'melhoria' ? 'selected' : '' }}>Melhoria</option>
                            <option value="evento" {{ old('tipo') == 'evento' ? 'selected' : '' }}>Evento</option>
                            <option value="treinamento" {{ old('tipo') == 'treinamento' ? 'selected' : '' }}>Treinamento</option>
                            <option value="workshop" {{ old('tipo') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                            <option value="outro" {{ old('tipo') == 'outro' ? 'selected' : '' }}>Outro</option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="planejada" {{ old('status', 'planejada') == 'planejada' ? 'selected' : '' }}>Planejada</option>
                            <option value="em_andamento" {{ old('status') == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="concluida" {{ old('status') == 'concluida' ? 'selected' : '' }}>Concluída</option>
                            <option value="cancelada" {{ old('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="data_inicio" class="form-label">Data de Início <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('data_inicio') is-invalid @enderror" 
                               id="data_inicio" name="data_inicio" value="{{ old('data_inicio') }}" required>
                        @error('data_inicio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="data_fim" class="form-label">Data de Término</label>
                        <input type="date" class="form-control @error('data_fim') is-invalid @enderror" 
                               id="data_fim" name="data_fim" value="{{ old('data_fim') }}">
                        @error('data_fim')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="local" class="form-label">Local</label>
                        <input type="text" class="form-control @error('local') is-invalid @enderror" 
                               id="local" name="local" value="{{ old('local') }}">
                        @error('local')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="responsavel_id" class="form-label">Responsável</label>
                        <select class="form-select @error('responsavel_id') is-invalid @enderror" id="responsavel_id" name="responsavel_id">
                            <option value="">Selecione...</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ old('responsavel_id') == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('responsavel_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="orcamento" class="form-label">Orçamento (R$)</label>
                        <input type="number" step="0.01" class="form-control @error('orcamento') is-invalid @enderror" 
                               id="orcamento" name="orcamento" value="{{ old('orcamento') }}">
                        @error('orcamento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="observacoes" class="form-label">Observações</label>
                        <textarea class="form-control @error('observacoes') is-invalid @enderror" 
                                  id="observacoes" name="observacoes" rows="3">{{ old('observacoes') }}</textarea>
                        @error('observacoes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('planejamento.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i>Criar Atividade
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

