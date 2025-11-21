@extends('layouts.app')

@section('title', 'Criar Ata')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-file-text me-2"></i>Criar Ata
                    </h1>
                    <p class="text-muted">Criar ata para: {{ $reuniao->titulo }}</p>
                </div>
                @include('components.help-icon', ['key' => 'atas.create'])
            </div>
            <a href="{{ route('reunioes.show', $reuniao) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <div class="metric-card">
            <form method="POST" action="{{ route('atas.store', $reuniao) }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="numero" class="form-label">Número da Ata</label>
                        <input type="text" class="form-control @error('numero') is-invalid @enderror" 
                               id="numero" name="numero" value="{{ old('numero') }}" placeholder="Ex: 001/2025">
                        @error('numero')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Reunião</label>
                        <input type="text" class="form-control" value="{{ $reuniao->titulo }}" disabled>
                        <small class="text-muted">{{ $reuniao->data_hora->format('d/m/Y H:i') }}</small>
                    </div>

                    <div class="col-md-12">
                        <label for="conteudo" class="form-label">Conteúdo da Ata <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('conteudo') is-invalid @enderror" 
                                  id="conteudo" name="conteudo" rows="15" required>{{ old('conteudo') }}</textarea>
                        <small class="text-muted">Descreva os assuntos discutidos, decisões tomadas e encaminhamentos.</small>
                        @error('conteudo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="aprovada" name="aprovada" value="1" {{ old('aprovada') ? 'checked' : '' }}>
                            <label class="form-check-label" for="aprovada">
                                Marcar como aprovada
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('reunioes.show', $reuniao) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i>Criar Ata
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

