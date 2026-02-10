@extends('layouts.admin')

@section('title', 'Relatorio rapido - Admin LMS')

@push('meta')
<meta name="description" content="Relatorio rapido de cursos com status e indicadores principais.">
<meta name="keywords" content="relatorio cursos, admin, LMS, status">
<link rel="canonical" href="{{ url('/admin/cursos/relatorio') }}">
<meta property="og:title" content="Relatorio rapido - Admin LMS">
<meta property="og:description" content="Visao rapida de cursos ativos, ocultos e agendados.">
<meta property="og:type" content="website">
@endpush

@section('admin-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/cursos/style.css') }}">
@endpush

<section class="courses-page" aria-label="Relatorio rapido de cursos">
    <header class="courses-hero">
        <h1>Relatorio rapido</h1>
        <p>Indicadores gerais para acompanhar o status dos cursos.</p>
        <div class="course-card__actions" style="margin-top: 1rem;">
            <a class="btn btn-ghost" href="{{ route('admin.cursos') }}">Voltar</a>
        </div>
    </header>

    <div class="courses-grid">
        <article class="course-card">
            <h2>Total de cursos</h2>
            <p class="course-card__description">{{ $total }}</p>
        </article>
        <article class="course-card">
            <h2>Ativos</h2>
            <p class="course-card__description">{{ $ativos }}</p>
        </article>
        <article class="course-card">
            <h2>Ocultos</h2>
            <p class="course-card__description">{{ $ocultos }}</p>
        </article>
        <article class="course-card">
            <h2>Agendados</h2>
            <p class="course-card__description">{{ $agendados }}</p>
        </article>
        <article class="course-card">
            <h2>Rascunhos</h2>
            <p class="course-card__description">{{ $rascunhos }}</p>
        </article>
    </div>
</section>
@endsection
