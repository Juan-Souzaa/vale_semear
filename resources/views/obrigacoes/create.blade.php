@extends('layouts.app')

@section('title', 'Nova Obrigação')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="bi bi-plus-circle me-2"></i>Nova Obrigação
                </h1>
                <p class="text-muted">Criar uma nova obrigação</p>
            </div>
            <a href="{{ route('obrigacoes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <div class="metric-card">
            <form method="POST" action="{{ route('obrigacoes.store') }}">
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
                            <option value="legal" {{ old('tipo') == 'legal' ? 'selected' : '' }}>Legal</option>
                            <option value="administrativa" {{ old('tipo') == 'administrativa' ? 'selected' : '' }}>Administrativa</option>
                            <option value="financeira" {{ old('tipo') == 'financeira' ? 'selected' : '' }}>Financeira</option>
                            <option value="outro" {{ old('tipo') == 'outro' ? 'selected' : '' }}>Outro</option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="data_vencimento" class="form-label">Data de Vencimento <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('data_vencimento') is-invalid @enderror" 
                               id="data_vencimento" name="data_vencimento" value="{{ old('data_vencimento') }}" required>
                        @error('data_vencimento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="data_lembrete" class="form-label">Data de Lembrete</label>
                        <input type="date" class="form-control @error('data_lembrete') is-invalid @enderror" 
                               id="data_lembrete" name="data_lembrete" value="{{ old('data_lembrete') }}">
                        <small class="text-muted">Data para enviar lembrete (deve ser antes ou igual à data de vencimento)</small>
                        @error('data_lembrete')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="prioridade" class="form-label">Prioridade <span class="text-danger">*</span></label>
                        <select class="form-select @error('prioridade') is-invalid @enderror" id="prioridade" name="prioridade" required>
                            <option value="baixa" {{ old('prioridade', 'media') == 'baixa' ? 'selected' : '' }}>Baixa</option>
                            <option value="media" {{ old('prioridade', 'media') == 'media' ? 'selected' : '' }}>Média</option>
                            <option value="alta" {{ old('prioridade') == 'alta' ? 'selected' : '' }}>Alta</option>
                            <option value="urgente" {{ old('prioridade') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                        </select>
                        @error('prioridade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="pendente" {{ old('status', 'pendente') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                            <option value="em_andamento" {{ old('status') == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="concluida" {{ old('status') == 'concluida' ? 'selected' : '' }}>Concluída</option>
                            <option value="vencida" {{ old('status') == 'vencida' ? 'selected' : '' }}>Vencida</option>
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
                                <option value="{{ $usuario->id }}" {{ old('responsavel_id') == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('responsavel_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('obrigacoes.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i>Criar Obrigação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

