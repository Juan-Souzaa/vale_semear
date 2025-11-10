@extends('layouts.app')

@section('title', 'Registrar')
@section('page-title', 'Criar Conta')
@section('page-subtitle', 'Preencha os dados para começar')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="metric-card">
            <!-- Header -->
            <div class="text-center mb-4">
                <div class="logo-icon mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    GA
                </div>
                <h2 class="metric-label">Criar Conta</h2>
                <p class="metric-description">Preencha os dados para começar</p>
            </div>

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nome Completo</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder="Seu nome completo"
                               required 
                               autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="seu@email.com"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Mínimo 8 caracteres"
                               required 
                               minlength="8">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-text">
                        <i class="bi bi-info-circle me-1"></i>
                        A senha deve ter pelo menos 8 caracteres
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Digite a senha novamente"
                               required 
                               minlength="8">
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" 
                           class="form-check-input" 
                           id="terms" 
                           required>
                    <label class="form-check-label" for="terms">
                        Eu concordo com os 
                        <a href="#" class="text-decoration-none">Termos de Uso</a> 
                        e 
                        <a href="#" class="text-decoration-none">Política de Privacidade</a>
                    </label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-person-plus me-2"></i>Criar Conta
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <hr class="my-4">

            <!-- Login Link -->
            <div class="text-center">
                <p class="mb-0">
                    Já tem uma conta? 
                    <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                        Entre aqui
                    </a>
                </p>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="text-center mt-4">
            <p class="metric-description">
                <i class="bi bi-shield-check me-1"></i>
                Criar uma conta é gratuito e leva apenas alguns segundos
            </p>
        </div>
    </div>
</div>
@endsection
