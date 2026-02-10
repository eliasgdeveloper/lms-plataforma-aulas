@extends('layouts.admin')

@section('admin-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/admin_usuarios/style.css') }}">
@endpush

<div class="page-header">
    <h1>Gerenciar Usuários</h1>
    <p>Adicione, edite ou remova usuários da plataforma</p>
</div>

<div class="usuarios-container">
    <button class="btn btn-primary btn-add">+ Novo Usuário</button>
    
    <table class="usuarios-table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Papel</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>João Silva</td>
                <td>joao@example.com</td>
                <td>Aluno</td>
                <td><span class="badge badge-success">Ativo</span></td>
                <td><button class="btn-sm btn-edit">Editar</button></td>
            </tr>
        </tbody>
    </table>
</div>

@push('scripts')
<script src="{{ asset('pages/admin_usuarios/script.js') }}" defer></script>
@endpush
@endsection
