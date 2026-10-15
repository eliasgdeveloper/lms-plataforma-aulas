@extends('errors.layout')

@section('title', 'Página não encontrada')
@section('code', '404')
@section('heading', 'Essa página saiu de férias')
@section('message')
    O endereço pode ter mudado, sido removido, ou ter um erro de digitação.
    Use um dos atalhos abaixo e continue de onde parou.
@endsection

@section('actions')
    @include('errors._actions')
@endsection
