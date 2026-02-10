@extends('layouts.student')

@section('content')
<div class="max-w-6xl">
    <!-- Breadcrumb -->
    <div class="mb-8 flex items-center space-x-2 text-sm text-gray-600">
        <a href="{{ route('aluno.aulas') }}" class="text-blue-600 hover:text-blue-800 font-semibold">Aulas</a>
        <span>/</span>
        <span class="text-gray-900">{{ $aula->titulo }}</span>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-3">{{ $aula->titulo }}</h1>
        <p class="text-gray-600 text-lg max-w-3xl">{{ $aula->descricao }}</p>
    </div>

    <!-- Conteúdos Grid -->
    @if($conteudos->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($conteudos as $conteudo)
                <a href="{{ route('conteudos.show', $conteudo) }}" data-url="{{ route('conteudos.show', $conteudo) }}" class="conteudo-link group bg-white rounded-xl shadow-md hover:shadow-lg transition-all transform hover:scale-105 overflow-hidden border-l-4 border-blue-500">
                    <div class="p-6">
                        <!-- Tipo Badge -->
                        <div class="mb-4 inline-block">
                            @switch($conteudo->tipo)
                                @case('video')
                                    <span class="inline-flex items-center bg-red-100 text-red-800 text-xs font-bold px-3 py-1 rounded-full">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path></svg>
                                        Vídeo
                                    </span>
                                    @break
                                @case('pdf')
                                    <span class="inline-flex items-center bg-orange-100 text-orange-800 text-xs font-bold px-3 py-1 rounded-full">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path></svg>
                                        PDF
                                    </span>
                                    @break
                                @case('link')
                                    <span class="inline-flex items-center bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM15.657 14.243a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM11 17a1 1 0 110-2v1a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 001 1zM5.757 5.757a1 1 0 00-1.414 1.414l.707.707a1 1 0 001.414-1.414l-.707-.707zM5 10a1 1 0 01-1-1V8a1 1 0 012 0v1a1 1 0 01-1 1zM5.757 14.243a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707z"></path></svg>
                                        Link
                                    </span>
                                    @break
                                @case('texto')
                                    <span class="inline-flex items-center bg-purple-100 text-purple-800 text-xs font-bold px-3 py-1 rounded-full">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 11-2 0V5H5v12h6a1 1 0 110 2H4a1 1 0 01-1-1V4z"></path></svg>
                                        Texto
                                    </span>
                                    @break
                            @endswitch
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition">{{ $conteudo->titulo }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $conteudo->descricao }}</p>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <span class="text-xs text-gray-500">Clique para abrir</span>
                            <svg class="w-5 h-5 text-blue-600 group-hover:translate-x-1 transition">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-6 text-center">
            <svg class="w-12 h-12 text-yellow-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 6v2M9 5a3 3 0 016 0m0 0a3 3 0 016 0m0 0a3 3 0 00-6 0m0 0a3 3 0 00-6 0"></path>
            </svg>
            <p class="text-yellow-800 font-semibold">Nenhum conteúdo disponível nesta aula</p>
        </div>
    @endif
</div>

<!-- Modal container -->
<div id="conteudo-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full overflow-auto" role="dialog" aria-modal="true">
        <div id="conteudo-modal-inner"></div>
        <div class="p-4 text-right">
            <button id="conteudo-modal-close" class="inline-flex items-center bg-gray-100 px-4 py-2 rounded hover:bg-gray-200">Fechar</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function openModal(html, url) {
        const modal = document.getElementById('conteudo-modal');
        const inner = document.getElementById('conteudo-modal-inner');
        inner.innerHTML = html;
        modal.classList.remove('hidden');
        history.pushState({modal: true}, '', url);
    }

    function closeModal() {
        const modal = document.getElementById('conteudo-modal');
        modal.classList.add('hidden');
        document.getElementById('conteudo-modal-inner').innerHTML = '';
        if (history.state && history.state.modal) {
            history.back();
        }
    }

    document.querySelectorAll('.conteudo-link').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            const url = el.getAttribute('data-url');
            fetch(url, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                .then(r => r.text())
                .then(html => openModal(html, url))
                .catch(() => alert('Erro ao carregar conteúdo'));
        });
    });

    document.getElementById('conteudo-modal-close').addEventListener('click', closeModal);

    window.addEventListener('popstate', function (e) {
        // if modal open and we pop state, close it
        const modal = document.getElementById('conteudo-modal');
        if (!modal.classList.contains('hidden')) {
            closeModal();
        }
    });
});
</script>
@endpush
@endsection
