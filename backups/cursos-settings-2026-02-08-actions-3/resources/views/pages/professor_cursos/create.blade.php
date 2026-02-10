@extends('layouts.professor')

@section('title', 'Criar curso - Professor LMS')

@push('meta')
<meta name="description" content="Crie cursos e modulos com ferramentas simples para professores no LMS.">
<meta name="keywords" content="criar curso, professor, LMS, modulos, licoes, matricula">
<link rel="canonical" href="{{ url('/professor/cursos/novo') }}">
<meta property="og:title" content="Criar curso - Professor LMS">
<meta property="og:description" content="Crie cursos completos com capas, grade e matriculas.">
<meta property="og:type" content="website">
@endpush

@section('professor-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/cursos/style.css') }}">
<link rel="stylesheet" href="{{ asset('pages/cursos/create.css') }}">
@endpush

@include('pages.cursos._form', [
    'contextTitle' => 'Criar novo curso (Professor)',
    'contextSubtitle' => 'Monte cursos praticos com grade simples e matriculas rapidas.',
    'formAction' => route('professor.cursos.store')
])
@endsection
