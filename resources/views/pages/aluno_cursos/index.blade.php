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

@push('styles')
<link rel="stylesheet" href="{{ asset('pages/cursos/style.css') }}">
@endpush

@section('aluno-content')
    @include('pages.aluno_cursos._list', ['courses' => $courses])
@endsection
