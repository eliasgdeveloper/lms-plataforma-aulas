@extends('layouts.aluno_curso')

@section('title', 'Curso - ' . $curso->titulo)

@push('styles')
<link rel="stylesheet" href="{{ asset('pages/aluno_curso_conteudo/style.css') }}">
@endpush

@section('content')
<div class="course-shell">
    <aside class="course-sidebar">
        <div class="course-sidebar__header">
            <h1>Topicos</h1>
            <p>{{ $curso->titulo }}</p>
            <div class="course-progress">
                <div class="course-progress__bar">
                    <span id="overall-progress-bar" style="width: {{ $overallProgress }}%"></span>
                </div>
                <div class="course-progress__meta" id="overall-progress-meta">
                    <span id="overall-progress-text">{{ $overallProgress }}</span>% concluido •
                    <span id="overall-completed-count">{{ $completedCount }}</span>/<span id="overall-total-count">{{ $totalCount }}</span> itens
                </div>
            </div>
        </div>

        <div class="course-modules">
            @forelse ($modules as $module)
                <div class="module" data-module-id="{{ $module['id'] }}">
                    <button class="module__header" type="button">
                        <span>{{ $module['title'] }}</span>
                        <span class="module__progress" data-module-progress="{{ $module['id'] }}">{{ $module['progress'] }}%</span>
                    </button>
                    <ul class="module__items">
                        @foreach ($module['items'] as $item)
                            <li>
                                <button class="item" type="button"
                                    data-content-id="{{ $item['id'] }}"
                                    data-module-id="{{ $module['id'] }}"
                                    data-title="{{ $item['title'] }}"
                                    data-description="{{ $item['description'] ?? '' }}"
                                    data-type="{{ $item['type'] }}"
                                    data-url="{{ $item['url'] ?? '' }}"
                                    data-file-url="{{ $item['file_url'] ?? '' }}"
                                    data-progress="{{ $item['progress'] }}"
                                    data-allow-download="{{ !empty($item['allow_download_student']) ? 1 : 0 }}">
                                    <span class="item__icon">{{ strtoupper(substr($item['type'] ?? 'C', 0, 1)) }}</span>
                                    <span class="item__label">{{ $item['title'] }}</span>
                                    <span class="item__status">{{ $item['progress'] }}%</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @empty
                <div class="module">
                    <div class="module__header">
                        <span>Nenhum conteudo encontrado</span>
                        <span class="module__progress">0%</span>
                    </div>
                </div>
            @endforelse
        </div>
    </aside>

    <section class="course-content">
        <div class="content-header">
            <div>
                <h2 id="content-title">Selecione um conteudo</h2>
                <p id="content-description">Clique em um topico do menu para abrir o conteudo.</p>
            </div>
            <div class="content-metrics">
                <div class="metric">
                    <span class="metric__label">Progresso geral</span>
                    <span class="metric__value" id="overall-progress-metric">{{ $overallProgress }}%</span>
                </div>
                <div class="metric">
                    <span class="metric__label">Pontos</span>
                    <span class="metric__value" id="overall-points">{{ $points }}</span>
                </div>
            </div>
        </div>

        <div class="content-preview" id="content-preview">
            <div class="content-placeholder">
                Carregue um video ou aula para visualizar aqui.
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('pages/aluno_curso_conteudo/script.js') }}" defer></script>
@endpush
