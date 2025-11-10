@extends('layouts.app')

@section('title', 'SEMEAR')
@section('page-title', 'SEMEAR')
@section('page-subtitle', 'Bem-vindo ao sistema de gestão da associação')

@section('content')
<!-- Redirecionar para Dashboard -->
<script>
    window.location.href = "{{ route('dashboard') }}";
</script>
@endsection
