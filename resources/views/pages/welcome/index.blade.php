@extends('layouts.landing')

@section('title', 'Início | ' . config('app.name'))

@section('content')
<main class="shell landing-home-shell">
    @include('partials.codar-entrada-copy')
</main>

@include('partials.codar-entrada-more')
@endsection
