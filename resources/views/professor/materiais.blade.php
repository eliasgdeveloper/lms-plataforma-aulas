@extends('layouts.professor')

@section('professor-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/professor_materiais/style.css') }}">
@endpush

<div class="page-header">
    <h1>Postar Materiais</h1>
    <p>Compartilhe recursos com seus alunos</p>
</div>

<div class="materiais-container">
    <div class="material-form">
        <h3>Novo Material</h3>
        <form>
            <input type="text" placeholder="Título do material" required>
            <input type="file" placeholder="Selecione o arquivo" required>
            <button type="submit" class="btn btn-primary">Postar Material</button>
        </form>
    </div>
</div>

@push('scripts')
<script src="{{ asset('pages/professor_materiais/script.js') }}" defer></script>
@endpush
@endsection
