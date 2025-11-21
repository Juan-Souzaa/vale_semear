@extends('layouts.app')

@section('title', 'Acesso Negado')
@section('page-title', 'Acesso Negado')
@section('page-subtitle', 'Você não tem permissão para acessar este recurso')

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card text-center">
            <div class="card-body py-5">
                <div class="mb-4">
                    <i class="bi bi-shield-x" style="font-size: 5rem; color: #ef4444;"></i>
                </div>
                <h2 class="mb-3">403 - Acesso Negado</h2>
                <p class="text-muted mb-4">
                    Você não tem permissão para acessar este recurso.
                </p>
                <p class="text-muted mb-4">
                    Se você acredita que deveria ter acesso, entre em contato com o administrador do sistema.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="bi bi-house me-2"></i>Voltar ao Dashboard
                    </a>
                    <a href="javascript:history.back()" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

