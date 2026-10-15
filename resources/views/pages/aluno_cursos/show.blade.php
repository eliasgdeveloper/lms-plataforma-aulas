@extends('layouts.aluno_curso')

@section('title', 'Curso - ' . $curso->titulo)

@push('styles')
<link rel="stylesheet" href="{{ asset('pages/aluno_curso_conteudo/style.css') }}?v=20261004d">
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
            <div class="course-docs" style="margin-top:0.75rem;display:flex;flex-direction:column;gap:0.35rem;">
                @if ($curso->matrizUrl())
                    <a href="{{ $curso->matrizUrl() }}" target="_blank" rel="noopener">Matriz do curso (PDF)</a>
                @endif
                @if ($curso->checklistUrl())
                    <a href="{{ $curso->checklistUrl() }}" target="_blank" rel="noopener">Checklist detalhado (PDF)</a>
                @endif
                <small>{{ $curso->duracaoLabel() }} · {{ $curso->nivelLabel() }}</small>
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
                        @if (!empty($module['sections']))
                            @foreach ($module['sections'] as $sectionIndex => $section)
                                <li class="module__section-group {{ $sectionIndex > 0 ? 'module__section-group--nested' : '' }}">
                                    <div class="module__section {{ $sectionIndex > 0 ? 'module__section--next' : '' }}">
                                        @if ($sectionIndex > 0)
                                            <span class="module__section-branch" aria-hidden="true"></span>
                                        @endif
                                        <span class="module__section-mark"></span>
                                        <span class="module__section-label">{{ $section['label'] }}</span>
                                    </div>
                                    <ul class="module__section-items">
                                        @foreach ($section['items'] as $item)
                                            <li>
                                                <button class="item item--nested" type="button"
                                                    data-content-id="{{ $item['id'] }}"
                                                    data-module-id="{{ $module['id'] }}"
                                                    data-title="{{ $item['title'] }}"
                                                    data-type="{{ $item['type'] }}"
                                                    data-progress="{{ $item['progress'] }}">
                                                    <span class="item__icon">{{ strtoupper(substr($item['type'] ?? 'C', 0, 1)) }}</span>
                                                    <span class="item__label">{{ $item['title'] }}</span>
                                                    <span class="item__status">{{ $item['progress'] }}%</span>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        @else
                            @foreach ($module['items'] as $item)
                                <li>
                                    <button class="item" type="button"
                                        data-content-id="{{ $item['id'] }}"
                                        data-module-id="{{ $module['id'] }}"
                                        data-title="{{ $item['title'] }}"
                                        data-type="{{ $item['type'] }}"
                                        data-progress="{{ $item['progress'] }}">
                                        <span class="item__icon">{{ strtoupper(substr($item['type'] ?? 'C', 0, 1)) }}</span>
                                        <span class="item__label">{{ $item['title'] }}</span>
                                        <span class="item__status">{{ $item['progress'] }}%</span>
                                    </button>
                                </li>
                            @endforeach
                        @endif
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
                    <span class="metric__label">XP</span>
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
@php
    $courseContentsPayload = [];
    foreach ($modules as $module) {
        foreach ($module['items'] as $item) {
            $courseContentsPayload[(string) $item['id']] = [
                'id' => $item['id'],
                'title' => $item['title'] ?? '',
                'description' => $item['description'] ?? '',
                'type' => $item['type'] ?? '',
                'url' => $item['url'] ?? '',
                'file_url' => $item['file_url'] ?? '',
                'allow_download_student' => !empty($item['allow_download_student']),
            ];
        }
    }
@endphp
<script>
    window.__COURSE_CONTENTS__ = @json($courseContentsPayload);
</script>
<script src="{{ asset('pages/aluno_curso_conteudo/script.js') }}?v=20261004d" defer></script>
@endpush
