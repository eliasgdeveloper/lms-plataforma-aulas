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
        @php
            $arquivoUrl = $conteudo->arquivo ? asset('storage/' . $conteudo->arquivo) : null;
        @endphp
        @switch($conteudo->tipo)
            @case('video')
                <div class="aspect-video bg-gray-900 rounded-lg overflow-hidden">
                    @if($conteudo->url)
                        <iframe class="w-full h-full" src="{{ $conteudo->url }}" frameborder="0" allowfullscreen></iframe>
                    @elseif($arquivoUrl)
                        <video controls class="w-full h-full" controlsList="nodownload noplaybackrate" disablePictureInPicture oncontextmenu="return false" src="{{ $arquivoUrl }}"></video>
                    @else
                        <div class="flex items-center justify-center h-full">
                            <p class="text-gray-400">Vídeo não disponível</p>
                        </div>
                    @endif
                </div>
                @if($arquivoUrl)
                    <div class="mt-4 flex flex-wrap gap-3">
                        <a href="{{ $arquivoUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center bg-gray-900 text-white px-4 py-2 rounded hover:bg-black">Abrir video</a>
                    </div>
                @endif
                @break

            @case('pdf')
                @php
                    $pdfUrl = $arquivoUrl ?? $conteudo->url;
                @endphp
                @if($pdfUrl)
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ $pdfUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Abrir PDF</a>
                        <a href="{{ $pdfUrl }}" download class="inline-flex items-center bg-indigo-100 text-indigo-800 px-4 py-2 rounded hover:bg-indigo-200">Baixar PDF</a>
                    </div>
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

            @case('arquivo')
            @case('word')
            @case('excel')
                @php
                    $fileUrl = $arquivoUrl ?? $conteudo->url;
                @endphp
                @if($fileUrl)
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ $fileUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center bg-gray-900 text-white px-4 py-2 rounded hover:bg-black">Abrir arquivo</a>
                        <a href="{{ $fileUrl }}" download class="inline-flex items-center bg-gray-100 text-gray-900 px-4 py-2 rounded hover:bg-gray-200">Baixar arquivo</a>
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">Arquivo não disponível</div>
                @endif
                @break

            @default
                <p class="text-gray-600">Tipo de conteúdo não reconhecido</p>
        @endswitch
    </div>
</div>
