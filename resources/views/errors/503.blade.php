@extends('errors.layout')

@section('title', 'Em manutenção')
@section('code', '503')
@section('heading', 'Estamos afinando a plataforma')
@section('message')
    Manutenção rápida em andamento. Volte em alguns minutos —
    cursos, progresso e Arenas seguem no mesmo lugar.
@endsection

@section('actions')
    <a class="error-btn error-btn--primary" href="{{ url('/') }}">Tentar o início</a>
    <a class="error-btn error-btn--ghost" href="javascript:location.reload()">Atualizar</a>
@endsection
