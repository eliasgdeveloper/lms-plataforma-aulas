<!-- Partial used for AJAX modal (only inner content) -->
<div class="p-6">
    <div class="mb-6">
        <a href="{{ route('conteudos.index', $aula) }}" class="text-blue-600 hover:text-blue-800 font-semibold">← Voltar para aula</a>
    </div>

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ $conteudo->titulo }}</h2>
        <p class="text-gray-600 mt-2">{{ $conteudo->descricao }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        @switch($conteudo->tipo)
            @case('video')
                <div class="aspect-video bg-gray-900 rounded-lg overflow-hidden">
                    @if($conteudo->url)
                        <iframe class="w-full h-full" src="{{ $conteudo->url }}" frameborder="0" allowfullscreen></iframe>
                    @else
                        <div class="flex items-center justify-center h-full">
                            <p class="text-gray-400">Vídeo não disponível</p>
                        </div>
                    @endif
                </div>
                @break

            @case('pdf')
                @if($conteudo->url)
                    <a href="{{ $conteudo->url }}" target="_blank" class="inline-flex items-center bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Baixar PDF</a>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">PDF não disponível</div>
                @endif
                @break

            @case('link')
                @if($conteudo->url)
                    <a href="{{ $conteudo->url }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800">Abrir link</a>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">Link não disponível</div>
                @endif
                @break

            @case('texto')
                <div class="prose prose-sm max-w-none">{!! nl2br(e($conteudo->url ?? 'Conteúdo não disponível')) !!}</div>
                @break

            @default
                <p class="text-gray-600">Tipo de conteúdo não reconhecido</p>
        @endswitch
    </div>
</div>
