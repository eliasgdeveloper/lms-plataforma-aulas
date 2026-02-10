@extends('layouts.professor')

@section('title', 'Editar curso - Professor LMS')

@push('meta')
<meta name="description" content="Edite cursos no LMS com ajustes de conteudo e status.">
<meta name="keywords" content="editar curso, professor, LMS, configuracoes">
<link rel="canonical" href="{{ url('/professor/cursos/' . $curso->id . '/editar') }}">
<meta property="og:title" content="Editar curso - Professor LMS">
<meta property="og:description" content="Atualize informacoes do curso e publique alteracoes.">
<meta property="og:type" content="website">
@endpush

@section('professor-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/cursos/style.css') }}">
<link rel="stylesheet" href="{{ asset('pages/cursos/create.css') }}">
@endpush

@include('pages.cursos._form', [
    'contextTitle' => 'Editar curso (Professor)',
    'contextSubtitle' => 'Ajuste os dados principais e mantenha o curso atualizado.',
    'formAction' => route('professor.cursos.update', $curso),
    'formMethod' => 'PUT',
    'curso' => $curso,
])
@endsection
