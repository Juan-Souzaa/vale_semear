@extends('layouts.app')

@section('title', 'Nova Reunião')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-plus-circle me-2"></i>Nova Reunião
                    </h1>
                    <p class="text-muted">Criar uma nova reunião</p>
                </div>
                @include('components.help-icon', ['key' => 'reunioes.create'])
            </div>
            <a href="{{ route('reunioes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <div class="metric-card">
            <form method="POST" action="{{ route('reunioes.store') }}">
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
                            <option value="diretoria" {{ old('tipo') == 'diretoria' ? 'selected' : '' }}>Diretoria</option>
                            <option value="assembleia" {{ old('tipo') == 'assembleia' ? 'selected' : '' }}>Assembleia</option>
                            <option value="comissao" {{ old('tipo') == 'comissao' ? 'selected' : '' }}>Comissão</option>
                            <option value="outro" {{ old('tipo') == 'outro' ? 'selected' : '' }}>Outro</option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="agendada" {{ old('status', 'agendada') == 'agendada' ? 'selected' : '' }}>Agendada</option>
                            <option value="confirmada" {{ old('status') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                            <option value="em_andamento" {{ old('status') == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="concluida" {{ old('status') == 'concluida' ? 'selected' : '' }}>Concluída</option>
                            <option value="cancelada" {{ old('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="data_hora" class="form-label">Data e Hora <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('data_hora') is-invalid @enderror" 
                               id="data_hora" name="data_hora" value="{{ old('data_hora') }}" required>
                        @error('data_hora')
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

                    <div class="col-md-12">
                        <label for="participantes" class="form-label">Participantes</label>
                        <select class="form-select @error('participantes') is-invalid @enderror" 
                                id="participantes" name="participantes[]" multiple size="5">
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ in_array($usuario->id, old('participantes', [])) ? 'selected' : '' }}>
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

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('reunioes.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i>Criar Reunião
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

