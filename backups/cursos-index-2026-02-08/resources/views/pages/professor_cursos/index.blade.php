@extends('layouts.professor')

@section('title', 'Cursos do professor - LMS')

@push('meta')
<meta name="description" content="Gerencie cursos, edite conteudo, publique e acompanhe matrizes curriculares.">
<meta name="keywords" content="cursos, professor, LMS, criar curso, editar curso, grade curricular">
<link rel="canonical" href="{{ url('/professor/cursos') }}">
<meta property="og:title" content="Cursos do professor - LMS">
<meta property="og:description" content="Crie, edite e publique cursos com facilidade.">
<meta property="og:type" content="website">
@endpush

@section('professor-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/cursos/style.css') }}">
@endpush

<section class="courses-page" aria-label="Cursos para professores">
    <header class="courses-hero">
        <h1>Seus cursos</h1>
        <p>Crie, organize e publique cursos. Ajuste grade, libere previas e acompanhe o progresso.</p>
        <div class="course-card__actions" style="margin-top: 1rem;">
            <button class="btn btn-primary" type="button">+ Novo curso</button>
            <button class="btn btn-ghost" type="button">Importar conteudo</button>
        </div>
    </header>

    <div class="courses-grid">
        @foreach ($courses as $course)
            <x-course-card :course="$course" :showActions="true">
                <x-slot:actions>
                    <button class="btn btn-admin" type="button">Editar</button>
                    <button class="btn btn-admin" type="button">Ocultar</button>
                    <button class="btn btn-warning" type="button">Agendar</button>
                    <button class="btn btn-danger" type="button">Excluir</button>
                </x-slot:actions>
            </x-course-card>
        @endforeach
    </div>
</section>
@endsection
