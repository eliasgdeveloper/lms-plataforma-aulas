@extends('layouts.admin')

@section('admin-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/admin_cursos/style.css') }}">
@endpush

<div class="page-header">
    <h1>Gerenciar Cursos</h1>
    <p>Crie e organize os cursos disponíveis na plataforma</p>
</div>

<div class="cursos-container">
    <button class="btn btn-primary btn-add">+ Novo Curso</button>
    
    <table class="cursos-table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Professor</th>
                <th>Alunos</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Programação Web</td>
                <td>Prof. Maria</td>
                <td>25</td>
                <td><span class="badge badge-success">Ativo</span></td>
                <td><button class="btn-sm btn-edit">Editar</button></td>
            </tr>
        </tbody>
    </table>
</div>

@push('scripts')
<script src="{{ asset('pages/admin_cursos/script.js') }}" defer></script>
@endpush
@endsection
