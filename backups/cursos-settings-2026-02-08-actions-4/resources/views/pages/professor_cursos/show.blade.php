@extends('layouts.professor')

@section('title', 'Configuracoes do curso - Professor LMS')

@push('meta')
<meta name="description" content="Configure cursos com menus, participantes e conteudos organizados.">
<meta name="keywords" content="configuracoes curso, LMS, professor, participantes, conteudos">
<link rel="canonical" href="{{ url('/professor/cursos/' . $curso->id) }}">
<meta property="og:title" content="Configuracoes do curso - Professor LMS">
<meta property="og:description" content="Edite o curso, modulos e conteudos em um painel simples.">
<meta property="og:type" content="website">
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Course',
    'name' => $curso->titulo,
    'description' => $curso->descricao,
    'provider' => [
        '@type' => 'Organization',
        'name' => config('app.name', 'LMS'),
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('professor-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/cursos/style.css') }}">
<link rel="stylesheet" href="{{ asset('pages/cursos/settings.css') }}">
@endpush

@include('pages.cursos._settings')
@endsection
