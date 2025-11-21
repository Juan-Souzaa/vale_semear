@extends('layouts.app')

@section('title', 'Gerenciar Permissões do Usuário')
@section('page-title', 'Gerenciar Permissões: ' . $user->name)
@section('page-subtitle', 'Atribuir roles e permissões ao usuário')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i>Informações do Usuário</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Nome:</dt>
                    <dd class="col-sm-9">{{ $user->name }}</dd>
                    
                    <dt class="col-sm-3">Email:</dt>
                    <dd class="col-sm-9">{{ $user->email }}</dd>
                    
                    <dt class="col-sm-3">Roles Atuais:</dt>
                    <dd class="col-sm-9">
                        @if($user->roles->count() > 0)
                            @foreach($user->roles as $role)
                                <span class="badge bg-primary me-1">{{ $role->name }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">Nenhuma role atribuída</span>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-people me-2"></i>Atribuir Roles</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('users.roles.assign', $user) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                @foreach($roles as $role)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}"
                                        {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                        {{ $role->name }}
                                        @if($role->name === 'Super Admin')
                                            <span class="badge bg-danger ms-2">Mestre</span>
                                        @endif
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Atualizar Roles
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-shield-check me-2"></i>Permissões Diretas</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('users.permissions.assign', $user) }}" method="POST">
                            @csrf
                            <div class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                                @foreach($permissions as $resource => $perms)
                                <div class="mb-3">
                                    <h6 class="text-capitalize mb-2">{{ $resource }}</h6>
                                    @foreach($perms as $permission)
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                                            {{ $user->hasDirectPermission($permission->name) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="bi bi-check-lg me-2"></i>Atualizar Permissões
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Voltar
            </a>
        </div>
    </div>
</div>
@endsection

