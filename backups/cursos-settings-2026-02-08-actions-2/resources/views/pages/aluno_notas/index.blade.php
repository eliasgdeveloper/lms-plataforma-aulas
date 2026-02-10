@extends('layouts.aluno')

@section('aluno-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/aluno_notas/style.css') }}">
@endpush

<div class="page-header">
    <h1>Suas Notas e Desempenho</h1>
    <p>Acompanhe seu progresso e notas nas aulas</p>
</div>

<div class="notas-summary">
    <div class="summary-card">
        <span class="label">Média Geral</span>
        <span class="value">8.5</span>
    </div>
    <div class="summary-card">
        <span class="label">Aulas Cursadas</span>
        <span class="value">12</span>
    </div>
    <div class="summary-card">
        <span class="label">Tarefas Concluídas</span>
        <span class="value">45</span>
    </div>
</div>

<div class="notas-table-container">
    <table class="notas-table">
        <thead>
            <tr>
                <th>Aula</th>
                <th>Nota</th>
                <th>Status</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Introdução ao Laravel</td>
                <td>9.0</td>
                <td><span class="badge badge-success">Aprovado</span></td>
                <td>2026-02-07</td>
            </tr>
            <tr>
                <td>PHP Avançado</td>
                <td>8.5</td>
                <td><span class="badge badge-success">Aprovado</span></td>
                <td>2026-02-06</td>
            </tr>
            <tr>
                <td>Banco de Dados</td>
                <td>7.5</td>
                <td><span class="badge badge-secondary">Pendente</span></td>
                <td>2026-02-05</td>
            </tr>
        </tbody>
    </table>
</div>

@push('scripts')
<script src="{{ asset('pages/aluno_notas/script.js') }}" defer></script>
@endpush
@endsection
