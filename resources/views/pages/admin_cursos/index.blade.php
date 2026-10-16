@extends('layouts.admin')

@section('title', 'Cursos do admin - LMS')

@push('meta')
<meta name="description" content="Administre cursos no LMS com controle de publicacao, grade e matriculas.">
<meta name="keywords" content="admin cursos, LMS, editar curso, publicar curso, matriz curricular">
<link rel="canonical" href="{{ url('/admin/cursos') }}">
<meta property="og:title" content="Cursos do admin - LMS">
<meta property="og:description" content="Gerencie cursos, professores e status com seguranca.">
<meta property="og:type" content="website">
@endpush

@section('admin-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/cursos/style.css') }}">
@endpush

<section class="courses-page" aria-label="Cursos para administracao">
    <header class="courses-hero">
        <h1>Gerenciar cursos</h1>
        <p>Crie, publique, organize e monitore cursos com controle total de status e agenda.</p>
        <div class="course-card__actions" style="margin-top: 1rem;">
            <a class="btn btn-primary" href="{{ route('admin.cursos.create') }}">+ Novo curso</a>
            <a class="btn btn-secondary" href="{{ route('admin.cursos.report') }}">Relatorio rapido</a>
            <a class="btn btn-ghost" href="{{ route('admin.cursos.overview') }}">Visao geral</a>
        </div>
    </header>

    <div class="courses-grid">
        @foreach ($courses as $course)
            <x-course-card
                :course="$course"
                :cardUrl="$course['id'] ? route('admin.cursos.show', $course['id']) : null"
                :showButtons="false"
            />
        @endforeach
    </div>
</section>
@endsection
