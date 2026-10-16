@extends('errors.layout')

@section('title', 'Sessão expirada')
@section('code', '419')
@section('heading', 'Sua sessão pediu um café')
@section('message')
    Por segurança, o formulário expirou. Entre novamente (ou atualize a página)
    e continue — seus dados de progresso não somem por causa disso.
@endsection

@section('actions')
    @include('errors._actions', [
        'extra' => '<a class="error-btn error-btn--ghost" href="'.e(url()->previous() ?: url('/')).'">Tentar de novo</a>',
    ])
@endsection
