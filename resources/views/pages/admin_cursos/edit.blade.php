@extends('layouts.admin')

@section('title', 'Editar curso - Admin LMS')

@push('meta')
<meta name="description" content="Edite cursos no LMS com controle de status, SEO e configuracoes principais.">
<meta name="keywords" content="editar curso, admin, LMS, configuracoes, SEO">
<link rel="canonical" href="{{ url('/admin/cursos/' . $curso->id . '/editar') }}">
<meta property="og:title" content="Editar curso - Admin LMS">
<meta property="og:description" content="Atualize dados do curso, status e informacoes principais.">
<meta property="og:type" content="website">
@endpush

@section('admin-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/cursos/style.css') }}">
<link rel="stylesheet" href="{{ asset('pages/cursos/create.css') }}">
@endpush

@include('pages.cursos._form', [
    'contextTitle' => 'Editar curso (Admin)',
    'contextSubtitle' => 'Atualize informacoes, status e ajustes principais do curso.',
    'formAction' => route('admin.cursos.update', $curso),
    'formMethod' => 'PUT',
    'curso' => $curso,
])
@endsection
