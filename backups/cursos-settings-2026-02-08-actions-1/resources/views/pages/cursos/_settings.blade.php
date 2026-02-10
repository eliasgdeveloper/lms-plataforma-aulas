@php
    $courseName = $curso->titulo ?? 'Curso';
@endphp

<section class="course-settings" aria-label="Configuracoes do curso">
    <header class="course-settings__topbar">
        <div class="course-settings__links">
            @foreach ($quickLinks as $link)
                <a href="{{ $link['href'] }}">{{ $link['label'] }}</a>
            @endforeach
        </div>
    </header>

    <header class="course-settings__hero">
        <h1>{{ strtoupper($courseName) }}</h1>
        <p>Edite configuracoes, organize modulos e acompanhe participantes com praticidade.</p>
    </header>

    <nav class="course-tabs" aria-label="Menu do curso">
        <a class="course-tabs__item course-tabs__item--active" href="#" aria-current="page">Curso</a>
        <a class="course-tabs__item" href="#">Configurações</a>
        <a class="course-tabs__item" href="#">Participantes</a>
        <a class="course-tabs__item" href="#">Notas</a>
        <a class="course-tabs__item" href="#">Relatórios</a>
        <details class="course-tabs__more">
            <summary>Mais</summary>
            <div class="course-tabs__dropdown">
                <a href="#">Banco de Questões</a>
                <a href="#">Banco de Conteúdo</a>
                <a href="#">Conclusão de curso</a>
                <a href="#">Filtros</a>
                <a href="#">Reutilizar curso</a>
            </div>
        </details>
    </nav>

    <div class="course-settings__layout">
        <aside class="course-settings__sidebar" aria-label="Conteudos do curso">
            <div class="course-settings__sidebar-header">
                <h2>Conteudos</h2>
                <button class="btn btn-ghost" type="button">+ Novo item</button>
            </div>

            @foreach ($modules as $module)
                <div class="course-module">
                    <div class="course-module__title">{{ $module['title'] }}</div>
                    <ul>
                        @foreach ($module['items'] as $item)
                            <li class="course-module__item course-module__item--{{ $item['type'] }}">
                                <span>{{ $item['label'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </aside>

        <section class="course-settings__content">
            <div class="course-settings__card">
                <h2>Atividades principais</h2>
                <p>Arraste, edite e organize os materiais exibidos para alunos.</p>

                <div class="course-activity-grid">
                    <div class="course-activity">
                        <h3>Video principal</h3>
                        <p>Video de boas-vindas e orientacoes do curso.</p>
                        <button class="btn btn-secondary" type="button">Enviar video</button>
                    </div>
                    <div class="course-activity">
                        <h3>PDFs e apostilas</h3>
                        <p>Materiais em PDF, Word ou planilhas de apoio.</p>
                        <button class="btn btn-secondary" type="button">Adicionar arquivos</button>
                    </div>
                    <div class="course-activity">
                        <h3>Conteudo interativo</h3>
                        <p>Quizzes, atividades e links externos.</p>
                        <button class="btn btn-secondary" type="button">Criar atividade</button>
                    </div>
                </div>
            </div>

            <div class="course-settings__card">
                <h2>Resumo do curso</h2>
                <div class="course-summary">
                    <div>
                        <span>Categoria</span>
                        <strong>{{ $curso->categoria ?? 'Sem categoria' }}</strong>
                    </div>
                    <div>
                        <span>Status</span>
                        <strong>Ativo</strong>
                    </div>
                    <div>
                        <span>Instrutor</span>
                        <strong>{{ $curso->professor?->name ?? 'Equipe LMS' }}</strong>
                    </div>
                </div>
            </div>
        </section>
    </div>
</section>
