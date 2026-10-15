@extends('errors.layout')

@section('title', 'Erro interno')
@section('code', '500')
@section('heading', 'Algo travou do nosso lado')
@section('message')
    Não foi você. Um erro inesperado aconteceu no servidor.
    Tente novamente em instantes. Se persistir, avise a equipe Codar.
@endsection

@section('actions')
    @include('errors._actions')
@endsection
