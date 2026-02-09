@extends('layouts.student')

@section('content')
<div class="max-w-4xl">
    <!-- Breadcrumb -->
    <div class="mb-8 flex items-center space-x-2 text-sm text-gray-600">
        <a href="{{ route('aluno.aulas') }}" class="text-blue-600 hover:text-blue-800 font-semibold">Aulas</a>
        <span>/</span>
        <a href="{{ route('conteudos.index', $aula) }}" class="text-blue-600 hover:text-blue-800 font-semibold">{{ $aula->titulo }}</a>
        <span>/</span>
        <span class="text-gray-900">{{ $conteudo->titulo }}</span>
    </div>

    <!-- Back Button -->
    <a href="{{ route('conteudos.index', $aula) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6 font-semibold transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Voltar para aula
    </a>

    <!-- Header -->
    <div class="mb-8 bg-white rounded-xl shadow-md p-8 border-l-4 border-blue-500">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $conteudo->titulo }}</h1>
                <p class="text-gray-600 text-lg">{{ $conteudo->descricao }}</p>
            </div>
            @switch($conteudo->tipo)
                @case('video')
                    <span class="inline-flex items-center bg-red-100 text-red-800 text-sm font-bold px-4 py-2 rounded-full">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path></svg>
                        Vídeo
                    </span>
                    @break
                @case('pdf')
                    <span class="inline-flex items-center bg-orange-100 text-orange-800 text-sm font-bold px-4 py-2 rounded-full">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path></svg>
                        PDF
                    </span>
                    @break
                @case('link')
                    <span class="inline-flex items-center bg-green-100 text-green-800 text-sm font-bold px-4 py-2 rounded-full">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM15.657 14.243a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM11 17a1 1 0 110-2v1a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 001 1zM5.757 5.757a1 1 0 00-1.414 1.414l.707.707a1 1 0 001.414-1.414l-.707-.707zM5 10a1 1 0 01-1-1V8a1 1 0 012 0v1a1 1 0 01-1 1zM5.757 14.243a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707z"></path></svg>
                        Link
                    </span>
                    @break
                @case('texto')
                    <span class="inline-flex items-center bg-purple-100 text-purple-800 text-sm font-bold px-4 py-2 rounded-full">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 11-2 0V5H5v12h6a1 1 0 110 2H4a1 1 0 01-1-1V4z"></path></svg>
                        Texto
                    </span>
                    @break
                @case('arquivo')
                @case('word')
                @case('excel')
                    <span class="inline-flex items-center bg-slate-100 text-slate-800 text-sm font-bold px-4 py-2 rounded-full">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h7l5-5V5a2 2 0 00-2-2H4zm8 9V4h-1V3h1a2 2 0 012 2v7h-2z"></path></svg>
                        Arquivo
                    </span>
                    @break
            @endswitch
        </div>
    </div>

    <!-- Content -->
    <div class="bg-white rounded-xl shadow-md p-8">
        @switch($conteudo->tipo)
            @case('video')
                <div class="aspect-video bg-gray-900 rounded-lg overflow-hidden mb-4">
                    @if($conteudo->url)
                        <iframe 
                            class="w-full h-full" 
                            src="{{ $conteudo->url }}" 
                            frameborder="0" 
                            allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                        </iframe>
                    @elseif($conteudo->arquivo)
                        <video controls class="w-full h-full" controlsList="nodownload noplaybackrate" disablePictureInPicture oncontextmenu="return false" src="{{ asset('storage/' . $conteudo->arquivo) }}"></video>
                    @else
                        <div class="flex items-center justify-center h-full bg-gray-800">
                            <div class="text-center">
                                <svg class="w-16 h-16 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                </svg>
                                <p class="text-gray-400 font-semibold">Vídeo não disponível</p>
                            </div>
                        </div>
                    @endif
                </div>
                @if($conteudo->arquivo)
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ asset('storage/' . $conteudo->arquivo) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-black transition font-semibold">
                            Abrir video
                        </a>
                    </div>
                @endif
                @break

            @case('pdf')
                @php
                    $arquivoUrl = $conteudo->arquivo ? asset('storage/' . $conteudo->arquivo) : null;
                    $pdfUrl = $arquivoUrl ?? $conteudo->url;
                @endphp
                @if($pdfUrl)
                    <div class="flex items-center justify-center p-8 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg border-2 border-orange-200 mb-4">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-orange-600 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                            </svg>
                            <div class="flex flex-wrap justify-center gap-3 mt-2">
                                <a href="{{ $pdfUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition font-semibold">
                                    Abrir PDF
                                </a>
                                <a href="{{ $pdfUrl }}" download class="inline-flex items-center bg-orange-100 text-orange-800 px-6 py-3 rounded-lg hover:bg-orange-200 transition font-semibold">
                                    Baixar PDF
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50 border-2 border-yellow-200 text-yellow-800 px-6 py-4 rounded-lg font-semibold">
                        <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"></path>
                        </svg>
                        PDF não disponível
                    </div>
                @endif
                @break

            @case('link')
                @if($conteudo->url)
                    <div class="flex items-center justify-center p-8 bg-gradient-to-br from-green-50 to-green-100 rounded-lg border-2 border-green-200 mb-4">
                        <div class="text-center max-w-2xl">
                            <svg class="w-16 h-16 text-green-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            <p class="text-gray-700 font-semibold mb-3">Link Externo</p>
                            <a href="{{ $conteudo->url }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="inline-flex items-center bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Abrir Link
                            </a>
                            <p class="text-gray-600 text-sm mt-3 break-all text-blue-600">{{ $conteudo->url }}</p>
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50 border-2 border-yellow-200 text-yellow-800 px-6 py-4 rounded-lg font-semibold">
                        Link não disponível
                    </div>
                @endif
                @break

            @case('texto')
                <div class="prose prose-lg max-w-none">
                    <div class="bg-gray-50 p-6 rounded-lg border-l-4 border-purple-500">
                        {!! nl2br(e($conteudo->url)) !!}
                    </div>
                </div>
                @break

            @case('arquivo')
            @case('word')
            @case('excel')
                @php
                    $arquivoUrl = $conteudo->arquivo ? asset('storage/' . $conteudo->arquivo) : null;
                    $fileUrl = $arquivoUrl ?? $conteudo->url;
                @endphp
                @if($fileUrl)
                    <div class="flex items-center justify-center p-8 bg-gradient-to-br from-slate-50 to-slate-100 rounded-lg border-2 border-slate-200 mb-4">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-slate-600 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h7l5-5V5a2 2 0 00-2-2H4zm8 9V4h-1V3h1a2 2 0 012 2v7h-2z"></path>
                            </svg>
                            <div class="flex flex-wrap justify-center gap-3">
                                <a href="{{ $fileUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center bg-slate-700 text-white px-6 py-3 rounded-lg hover:bg-slate-800 transition font-semibold">
                                    Abrir arquivo
                                </a>
                                <a href="{{ $fileUrl }}" download class="inline-flex items-center bg-slate-100 text-slate-800 px-6 py-3 rounded-lg hover:bg-slate-200 transition font-semibold">
                                    Baixar arquivo
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50 border-2 border-yellow-200 text-yellow-800 px-6 py-4 rounded-lg font-semibold">
                        Arquivo nao disponivel
                    </div>
                @endif
                @break

            @default
                <p class="text-gray-600 font-semibold">Tipo de conteúdo não reconhecido</p>
        @endswitch
    </div>
</div>
@endsection
