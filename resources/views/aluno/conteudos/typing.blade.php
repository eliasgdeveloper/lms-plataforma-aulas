@extends('layouts.aluno_curso')

@section('title', 'Treino de digitação - ' . $aula->titulo)

@push('styles')
<link rel="stylesheet" href="{{ asset('pages/aluno_curso_conteudo/style.css') }}?v=20261015g">
@endpush

@section('content')
<div class="course-shell">
    <aside class="course-sidebar">
        <div class="course-sidebar__header">
            <h1>Tópicos</h1>
            <p>{{ $curso->titulo }}</p>
            <a href="{{ route('aluno.cursos.show', $curso) }}" class="mt-3 inline-flex font-semibold text-indigo-700 hover:text-indigo-900">
                ← Voltar ao curso
            </a>
        </div>

        <div class="course-modules">
            @forelse ($modules as $module)
                <div class="module">
                    <div class="module__header">
                        <span>{{ $module['title'] }}</span>
                    </div>
                    <ul class="module__items">
                        @foreach ($module['items'] as $item)
                            <li>
                                <a
                                    class="item item--link {{ $item['id'] === $conteudo->id ? 'is-active' : '' }}"
                                    href="{{ $item['url'] }}"
                                >
                                    <span class="item__icon">{{ strtoupper(substr($item['type'] ?? 'C', 0, 1)) }}</span>
                                    <span class="item__label">{{ $item['title'] }}</span>
                                    <span class="item__status">Abrir</span>
                                </a>
                                @if (!empty($item['typing_url']))
                                    @php($isCurrentTyping = (int) $item['typing_content_id'] === (int) $conteudo->id)
                                    <a
                                        class="item item--link item--typing {{ $isCurrentTyping ? 'is-active' : '' }}"
                                        href="{{ $item['typing_url'] }}"
                                        @if ($isCurrentTyping) aria-current="page" @endif
                                    >
                                        <span class="item__icon" aria-hidden="true">T</span>
                                        <span class="item__label">Treino de digitação</span>
                                        <span class="item__status">{{ $isCurrentTyping ? 'Atual' : 'Abrir' }}</span>
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @empty
                <div class="module"><div class="module__header">Nenhum conteúdo encontrado</div></div>
            @endforelse
        </div>
    </aside>

    <section class="course-content">
        <div class="content-header">
            <div>
                <h1>Treino de digitação</h1>
                <p>{{ $aula->titulo }}</p>
            </div>
        </div>
        @include('aluno.conteudos._php-typing-exercise', [
            'typingExercise' => $typingExercise,
            'conteudo' => $conteudo,
            'aula' => $aula,
        ])
    </section>
</div>
@endsection
