@props([
    'course',
    'cardUrl' => null,
    'showButtons' => true,
])

<article class="course-card{{ $cardUrl ? ' course-card--clickable' : '' }}" itemscope itemtype="https://schema.org/Course">
    @if ($cardUrl)
        <a class="course-card__link" href="{{ $cardUrl }}" aria-label="Abrir configuracoes do curso"></a>
    @endif
    <header class="course-card__header">
        <div class="course-card__badge" aria-hidden="true">{{ strtoupper(substr($course['category'], 0, 1)) }}</div>
        <div>
            <p class="course-card__category" itemprop="educationalLevel">{{ $course['category'] }}</p>
            <h2 class="course-card__title" itemprop="name">{{ $course['title'] }}</h2>
        </div>
    </header>

    <p class="course-card__description" itemprop="description">{{ $course['description'] }}</p>

    <dl class="course-card__meta">
        <div>
            <dt>Nivel</dt>
            <dd>{{ $course['level'] }}</dd>
        </div>
        <div>
            <dt>Carga horaria</dt>
            <dd>{{ $course['duration'] }}</dd>
        </div>
        <div>
            <dt>Instrutor</dt>
            <dd itemprop="provider">{{ $course['teacher'] }}</dd>
        </div>
    </dl>

    <ul class="course-card__tags" aria-label="Palavras-chave">
        @foreach ($course['tags'] as $tag)
            <li>{{ $tag }}</li>
        @endforeach
    </ul>
    
    @if ($showButtons)
        <div class="course-card__actions">
            <a class="btn btn-primary" href="{{ $course['enroll_url'] }}">Matricular</a>
            <a class="btn btn-secondary" href="{{ $course['preview_url'] }}">Previa gratis</a>
            <a class="btn btn-ghost" href="{{ $course['syllabus_url'] }}">Ver grade</a>
        </div>
    @endif

</article>
