@extends('layouts.professor')

@section('professor-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/professor_aulas/style.css') }}">
@endpush

<div class="page-header">
    <h1>Criar Aula</h1>
    <p>Crie e gerenie suas aulas aqui</p>
</div>

<div class="aulas-container">
    <div class="aula-form">
        <h3>Nova Aula</h3>
        <form>
            <input type="text" placeholder="Título da aula" required>
            <textarea placeholder="Descrição da aula" required></textarea>
            <button type="submit" class="btn btn-primary">Criar Aula</button>
        </form>
    </div>
</div>

@push('scripts')
<script src="{{ asset('pages/professor_aulas/script.js') }}" defer></script>
@endpush
@endsection
