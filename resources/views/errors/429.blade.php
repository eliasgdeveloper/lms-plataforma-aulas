@extends('errors.layout')

@section('title', 'Muitas tentativas')
@section('code', '429')
@section('heading', 'Devagar com o acelerador')
@section('message')
    Houve muitas requisições em pouco tempo. Espere alguns segundos e tente outra vez —
    a plataforma continua aqui.
@endsection

@section('actions')
    @include('errors._actions')
@endsection
