@extends('layouts.app')

@section('title', 'Entrar')
@section('page-title', 'Entrar')
@section('page-subtitle', 'Acesse sua conta para continuar')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="metric-card">
            <!-- Header -->
            <div class="text-center mb-4">
                <div class="logo-icon mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    GA
                </div>
                <h2 class="metric-label">Entrar</h2>
                <p class="metric-description">Acesse sua conta para continuar</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                @csrf

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
                               required 
                               autofocus>
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
                               placeholder="Sua senha"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" 
                           class="form-check-input" 
                           id="remember" 
                           name="remember" 
                           {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Lembrar de mim
                    </label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <hr class="my-4">

            <!-- Register Link -->
            <div class="text-center">
                <p class="mb-0">
                    Não tem uma conta? 
                    <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">
                        Registre-se aqui
                    </a>
                </p>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="text-center mt-4">
            <p class="metric-description">
                <i class="bi bi-shield-check me-1"></i>
                Suas informações estão seguras e protegidas
            </p>
        </div>
    </div>
</div>
@endsection
