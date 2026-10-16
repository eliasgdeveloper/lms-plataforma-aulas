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
            <a class="btn btn-primary" href="{{ route('professor.cursos.create') }}">+ Novo curso</a>
            <button class="btn btn-ghost" type="button">Importar conteudo</button>
        </div>
    </header>

    <div class="courses-grid">
        @foreach ($courses as $course)
            <x-course-card
                :course="$course"
                :cardUrl="$course['id'] ? route('professor.cursos.show', $course['id']) : null"
                :showButtons="false"
            />
        @endforeach
    </div>
</section>
@endsection
