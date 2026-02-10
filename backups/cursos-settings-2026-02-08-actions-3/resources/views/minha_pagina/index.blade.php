@extends('layouts.page')

@section('title', 'Minha Página')

@push('styles')
<link rel="stylesheet" href="{{ asset('pages/minha_pagina/style.css') }}">
@endpush

@section('content')
<div class="page-hero max-w-4xl mx-auto p-6">
    <h1 class="text-3xl font-bold">Minha Página</h1>
    <p class="mt-4 text-gray-600">Este é um exemplo de página criada com o gerador.</p>
</div>
@endsection

@push('scripts')
<script src="{{ asset('pages/minha_pagina/script.js') }}" defer></script>
@endpush
