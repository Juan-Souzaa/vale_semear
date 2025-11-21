@extends('layouts.app')

@section('title', 'Permissões')
@section('page-title', 'Permissões')
@section('page-subtitle', 'Gerenciar permissões do sistema')

@section('header-actions')
{{-- Criação de novas permissões desabilitada - permissões devem ser criadas via seeder --}}
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

<div class="row">
    @foreach($permissions as $resource => $perms)
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 text-capitalize">{{ $resource }}</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @foreach($perms as $permission)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $permission->name }}</strong>
                        </div>
                        {{-- Todas as permissões são apenas para visualização, pois são essenciais --}}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

