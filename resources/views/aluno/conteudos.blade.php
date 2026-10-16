@extends('layouts.aluno')

@section('aluno-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/aluno_conteudos/style.css') }}">
@endpush

<div class="content-page">
    <div class="page-header">
        <h1>{{ $aula->titulo ?? 'Conteúdos da Aula' }}</h1>
        <p>{{ $aula->descricao ?? 'Acesse os conteúdos da aula' }}</p>
    </div>

    <div class="conteudo-wrapper">
        <aside class="conteudo-sidebar">
            <h3>Conteúdos</h3>
            <ul class="conteudo-list">
                @forelse($aula->conteudos ?? [] as $conteudo)
                    <li class="conteudo-item">
                        <a href="{{ route('conteudos.show', $conteudo->id) }}" class="conteudo-link">
                            {{ $conteudo->titulo }}
                        </a>
                    </li>
                @empty
                    <li class="conteudo-item empty">Nenhum conteúdo disponível</li>
                @endforelse
            </ul>
        </aside>

        <main class="conteudo-main">
            <div class="conteudo-card">
                <h2>Introdução ao Tema</h2>
                <p>Nesta aula você aprenderá os conceitos fundamentais sobre o tema proposto.</p>
                <p>Acompanhe com atenção e não hesite em consultar o material de apoio.</p>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script src="{{ asset('pages/aluno_conteudos/script.js') }}" defer></script>
@endpush
@endsection    <main class="flex-1 p-6">
        <h1 class="text-2xl font-bold mb-4">{{ $conteudoSelecionado->titulo }}</h1>
        <p class="mb-4 text-gray-700">{{ $conteudoSelecionado->descricao }}</p>

        @if($conteudoSelecionado->tipo === 'video')
            <video controls class="w-full rounded shadow">
                <source src="{{ asset('storage/'.$conteudoSelecionado->arquivo) }}" type="video/mp4">
                Seu navegador não suporta vídeo.
            </video>
        @elseif($conteudoSelecionado->tipo === 'pdf')
            <iframe src="{{ asset('storage/'.$conteudoSelecionado->arquivo) }}" 
                    class="w-full h-96 border rounded"></iframe>
        @elseif($conteudoSelecionado->tipo === 'link')
            <a href="{{ $conteudoSelecionado->url }}" target="_blank" 
               class="text-blue-600 underline">Acessar recurso externo</a>
        @endif
    </main>
</div>
@endsection
