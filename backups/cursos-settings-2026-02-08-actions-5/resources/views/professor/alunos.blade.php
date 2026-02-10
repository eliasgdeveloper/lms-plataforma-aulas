@extends('layouts.professor')

@section('professor-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/professor_alunos/style.css') }}">
@endpush

<div class="page-header">
    <h1>Acompanhar Alunos</h1>
    <p>Veja o progresso e desempenho dos seus alunos</p>
</div>

<div class="alunos-container">
    <table class="alunos-table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Desempenho</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>João Silva</td>
                <td>joao@example.com</td>
                <td>8.5/10</td>
                <td><span class="badge badge-success">Ativo</span></td>
            </tr>
        </tbody>
    </table>
</div>

@push('scripts')
<script src="{{ asset('pages/professor_alunos/script.js') }}" defer></script>
@endpush
@endsection
