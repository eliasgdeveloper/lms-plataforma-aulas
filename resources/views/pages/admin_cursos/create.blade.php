@extends('layouts.admin')

@section('title', 'Criar curso - Admin LMS')

@push('meta')
<meta name="description" content="Crie cursos no LMS com configuracao rapida, capas, SEO e matriculas.">
<meta name="keywords" content="criar curso, admin, LMS, matrizes, modulos, licoes, SEO">
<link rel="canonical" href="{{ url('/admin/cursos/novo') }}">
<meta property="og:title" content="Criar curso - Admin LMS">
<meta property="og:description" content="Monte cursos completos com preview, grade e matricula.">
<meta property="og:type" content="website">
@endpush

@section('admin-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/cursos/style.css') }}">
<link rel="stylesheet" href="{{ asset('pages/cursos/create.css') }}">
@endpush

@include('pages.cursos._form', [
    'contextTitle' => 'Criar novo curso (Admin)',
    'contextSubtitle' => 'Controle total para publicar, ocultar, agendar e gerenciar matriculas.',
    'formAction' => route('admin.cursos.store')
])
@endsection
