@extends('errors.layout')

@section('title', 'Acesso negado')
@section('code', '403')
@section('heading', 'Essa porta ainda não é a sua')
@section('message')
    Você está autenticado, mas esta área pede outro perfil (aluno, professor ou admin).
    Não é o fim do mundo — vamos te levar de volta para o lugar certo.
@endsection

@section('actions')
    @include('errors._actions')
@endsection
