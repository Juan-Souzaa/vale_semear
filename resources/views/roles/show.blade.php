@extends('layouts.app')

@section('title', 'Detalhes da Role')
@section('page-title', 'Detalhes da Role: ' . $role->name)
@section('page-subtitle', 'Visualizar detalhes e permissões da role')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informações da Role</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Nome:</dt>
                    <dd class="col-sm-9">
                        <strong>{{ $role->name }}</strong>
                        @if($role->name === 'Super Admin')
                            <span class="badge bg-danger ms-2">Mestre</span>
                        @endif
                    </dd>
                    
                    <dt class="col-sm-3">Usuários:</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-secondary">{{ $role->users->count() }} usuários</span>
                    </dd>
                </dl>
            </div>
        </div>

        @if($role->users->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-people me-2"></i>Usuários com esta Role</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @foreach($role->users as $user)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $user->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                        @if(auth()->user()->hasPermissionTo('usuarios.update'))
                        <a href="{{ route('users.roles', $user) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-gear me-1"></i>Gerenciar
                        </a>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-shield-check me-2"></i>Permissões</h5>
                @if(auth()->user()->hasPermissionTo('permissoes.update') && $role->name !== 'Super Admin')
                <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-pencil me-2"></i>Editar
                </a>
                @endif
            </div>
            <div class="card-body">
                @if($role->permissions->count() > 0)
                    <div class="list-group">
                        @foreach($permissions as $resource => $perms)
                        <div class="list-group-item">
                            <h6 class="text-capitalize mb-2">{{ $resource }}</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($perms as $permission)
                                    @if($role->permissions->contains($permission->id))
                                    <span class="badge bg-success">{{ $permission->name }}</span>
                                    @else
                                    <span class="badge bg-secondary text-muted">{{ $permission->name }}</span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Esta role não possui permissões atribuídas.</p>
                @endif
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Voltar
            </a>
        </div>
    </div>
</div>
@endsection

