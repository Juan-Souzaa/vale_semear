@extends('layouts.app')

@section('title', 'Editar Ata')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="bi bi-pencil me-2"></i>Editar Ata
                </h1>
                <p class="text-muted">Editar ata da reunião: {{ $ata->reuniao->titulo }}</p>
            </div>
            <a href="{{ route('atas.show', $ata) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <div class="metric-card">
            <form method="POST" action="{{ route('atas.update', $ata) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="numero" class="form-label">Número da Ata</label>
                        <input type="text" class="form-control @error('numero') is-invalid @enderror" 
                               id="numero" name="numero" value="{{ old('numero', $ata->numero) }}" placeholder="Ex: 001/2025">
                        @error('numero')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Reunião</label>
                        <input type="text" class="form-control" value="{{ $ata->reuniao->titulo }}" disabled>
                        <small class="text-muted">{{ $ata->reuniao->data_hora->format('d/m/Y H:i') }}</small>
                    </div>

                    <div class="col-md-12">
                        <label for="conteudo" class="form-label">Conteúdo da Ata <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('conteudo') is-invalid @enderror" 
                                  id="conteudo" name="conteudo" rows="15" required>{{ old('conteudo', $ata->conteudo) }}</textarea>
                        <small class="text-muted">Descreva os assuntos discutidos, decisões tomadas e encaminhamentos.</small>
                        @error('conteudo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="aprovada" name="aprovada" value="1" {{ old('aprovada', $ata->aprovada) ? 'checked' : '' }}>
                            <label class="form-check-label" for="aprovada">
                                Marcar como aprovada
                            </label>
                        </div>
                    </div>

                    @if($ata->aprovada)
                    <div class="col-md-12">
                        <label for="data_aprovacao" class="form-label">Data de Aprovação</label>
                        <input type="date" class="form-control @error('data_aprovacao') is-invalid @enderror" 
                               id="data_aprovacao" name="data_aprovacao" value="{{ old('data_aprovacao', $ata->data_aprovacao?->format('Y-m-d')) }}">
                        @error('data_aprovacao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('atas.show', $ata) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i>Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

