@extends('layouts.app')

@section('title', 'Roles e Permissões')
@section('page-title', 'Roles e Permissões')
@section('page-subtitle', 'Gerenciar roles e permissões do sistema')

@section('header-actions')
@if(auth()->user()->hasPermissionTo('permissoes.manage'))
<a href="{{ route('roles.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-2"></i>Nova Role
</a>
@endif
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row mb-4">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-people me-2"></i>Roles</h5>
            </div>
            <div class="card-body d-flex flex-column">
                <div class="table-responsive flex-grow-1">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Permissões</th>
                                <th>Usuários</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                            <tr>
                                <td>
                                    <strong>{{ $role->name }}</strong>
                                    @if($role->name === 'Super Admin')
                                        <span class="badge bg-danger ms-2">Mestre</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $role->permissions->count() }} permissões</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $role->users->count() }} usuários</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('roles.show', $role) }}" class="btn btn-outline-info" title="Ver detalhes">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if(auth()->user()->hasPermissionTo('permissoes.update') && $role->name !== 'Super Admin')
                                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @endif
                                        @if(auth()->user()->hasPermissionTo('permissoes.delete'))
                                        @if($role->name !== 'Super Admin')
                                        <button type="button" class="btn btn-outline-danger btnDeleteRole" data-role-id="{{ $role->id }}" title="Excluir">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <form id="deleteRoleForm{{ $role->id }}" action="{{ route('roles.destroy', $role) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Nenhuma role encontrada.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-shield-check me-2"></i>Permissões</h5>
            </div>
            <div class="card-body d-flex flex-column">
                <div class="list-group flex-grow-1" style="overflow-y: auto;">
                    @foreach($permissions as $resource => $perms)
                    <div class="list-group-item">
                        <h6 class="mb-2 text-capitalize">{{ $resource }}</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($perms as $permission)
                            <span class="badge bg-primary">{{ $permission->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3">
                    <a href="{{ route('permissions.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-list me-2"></i>Ver todas as permissões
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->hasPermissionTo('usuarios.update'))
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person-gear me-2"></i>Gerenciar Usuários</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Atribua roles e permissões diretamente aos usuários</p>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users ?? [] as $user)
                            <tr>
                                <td><strong>{{ $user->name }}</strong></td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-primary me-1">{{ $role->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Nenhuma role</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('users.roles', $user) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-gear me-1"></i>Gerenciar Permissões
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Nenhum usuário encontrado.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.btnDeleteRole');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const roleId = this.getAttribute('data-role-id');
            const deleteForm = document.getElementById('deleteRoleForm' + roleId);
            
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Esta ação não pode ser desfeita!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteForm.submit();
                }
            });
        });
    });
});
</script>
@endpush

