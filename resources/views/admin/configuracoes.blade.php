@extends('layouts.admin')

@section('admin-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/admin_configuracoes/style.css') }}">
@endpush

<div class="page-header">
    <h1>Configurações da Plataforma</h1>
    <p>Ajuste as configurações gerais da plataforma</p>
</div>

<div class="config-container">
    <form class="config-form">
        <div class="form-group">
            <label>Nome da Plataforma</label>
            <input type="text" value="LMS Plataforma" required>
        </div>
        
        <div class="form-group">
            <label>Email de Contato</label>
            <input type="email" value="contato@lms.com" required>
        </div>
        
        <div class="form-group">
            <label>Modo Manutenção</label>
            <input type="checkbox">
        </div>
        
        <button type="submit" class="btn btn-primary">Salvar Configurações</button>
    </form>
</div>

@push('scripts')
<script src="{{ asset('pages/admin_configuracoes/script.js') }}" defer></script>
@endpush
@endsection
