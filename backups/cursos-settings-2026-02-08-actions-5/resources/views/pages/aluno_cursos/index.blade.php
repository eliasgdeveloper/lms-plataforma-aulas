@extends('layouts.aluno')

@section('title', 'Cursos disponiveis - LMS')

@push('meta')
<meta name="description" content="Cursos de tecnologia e TI com preview gratis, grade curricular e matricula imediata.">
<meta name="keywords" content="cursos, LMS, JavaScript, Python, HTML, CSS, PHP, SQL, Corel Draw, Pacote Office, sistemas operacionais">
<link rel="canonical" href="{{ url('/aluno/cursos') }}">
<meta property="og:title" content="Cursos disponiveis - LMS">
<meta property="og:description" content="Escolha seu curso, veja a previa gratis e a grade completa.">
<meta property="og:type" content="website">
@endpush

@section('aluno-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/cursos/style.css') }}">
@endpush

<section class="courses-page" aria-label="Cursos para alunos">
    <header class="courses-hero">
        <h1>Cursos disponiveis</h1>
        <p>Escolha seu curso, veja a previa gratis, analise a grade e inicie sua jornada no LMS.</p>
    </header>

    <div class="courses-grid">
        @foreach ($courses as $course)
            <x-course-card :course="$course" />
        @endforeach
    </div>
</section>
@endsection
