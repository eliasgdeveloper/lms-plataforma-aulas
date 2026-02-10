@extends('layouts.admin')

@section('title', 'Visao geral - Admin LMS')

@push('meta')
<meta name="description" content="Visao geral de cursos com resumo rapido e proximes passos.">
<meta name="keywords" content="visao geral cursos, admin, LMS">
<link rel="canonical" href="{{ url('/admin/cursos/visao-geral') }}">
<meta property="og:title" content="Visao geral - Admin LMS">
<meta property="og:description" content="Resumo executivo dos cursos e proximas acoes.">
<meta property="og:type" content="website">
@endpush

@section('admin-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/cursos/style.css') }}">
@endpush

<section class="courses-page" aria-label="Visao geral dos cursos">
    <header class="courses-hero">
        <h1>Visao geral</h1>
        <p>Resumo executivo para apoiar as decisoes do admin.</p>
        <div class="course-card__actions" style="margin-top: 1rem;">
            <a class="btn btn-ghost" href="{{ route('admin.cursos') }}">Voltar</a>
        </div>
    </header>

    <div class="courses-grid">
        <article class="course-card">
            <h2>Status atual</h2>
            <p class="course-card__description">{{ $ativos }} cursos ativos e {{ $agendados }} agendados.</p>
        </article>
        <article class="course-card">
            <h2>Oportunidades</h2>
            <p class="course-card__description">{{ $rascunhos }} rascunhos aguardam revisao.</p>
        </article>
        <article class="course-card">
            <h2>Visibilidade</h2>
            <p class="course-card__description">{{ $ocultos }} cursos ocultos para ajustes.</p>
        </article>
    </div>
</section>
@endsection
