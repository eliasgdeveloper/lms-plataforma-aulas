@php
    $courseName = $curso->titulo ?? 'Curso';
@endphp

<section class="course-settings" aria-label="Configuracoes do curso" data-course-settings>
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
        <a class="course-tabs__item course-tabs__item--active" href="#curso" data-tab="curso" aria-current="page">Curso</a>
        <a class="course-tabs__item" href="#configuracoes" data-tab="configuracoes">Configurações</a>
        <a class="course-tabs__item" href="#participantes" data-tab="participantes">Participantes</a>
        <a class="course-tabs__item" href="#notas" data-tab="notas">Notas</a>
        <a class="course-tabs__item" href="#relatorios" data-tab="relatorios">Relatórios</a>
        <details class="course-tabs__more">
            <summary>Mais</summary>
            <div class="course-tabs__dropdown">
                <a href="#banco-questoes" data-tab="banco-questoes">Banco de Questões</a>
                <a href="#banco-conteudo" data-tab="banco-conteudo">Banco de Conteúdo</a>
                <a href="#conclusao" data-tab="conclusao">Conclusão de curso</a>
                <a href="#filtros" data-tab="filtros">Filtros</a>
                <a href="#reutilizar" data-tab="reutilizar">Reutilizar curso</a>
            </div>
        </details>
    </nav>

    <div class="course-settings__layout" data-course-layout>
        <aside class="course-settings__sidebar" aria-label="Conteudos do curso" data-course-sidebar>
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
            <div class="course-panel" data-panel="curso">
                <div class="course-settings__card">
                    <h2>Conteudos do curso</h2>
                    <p>Estrutura completa do curso para navegacao rapida.</p>

                    <div class="course-content-grid">
                        <div class="course-content-block">
                            <h3>Video principal</h3>
                            <p>Video de boas-vindas e orientacoes do curso.</p>
                            <button class="btn btn-secondary" type="button">Enviar video</button>
                        </div>
                        <div class="course-content-block">
                            <h3>PDFs e apostilas</h3>
                            <p>Materiais em PDF, Word ou planilhas de apoio.</p>
                            <button class="btn btn-secondary" type="button">Adicionar arquivos</button>
                        </div>
                        <div class="course-content-block">
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
                            <strong>{{ ucfirst($curso->status ?? 'ativo') }}</strong>
                        </div>
                        <div>
                            <span>Instrutor</span>
                            <strong>{{ $curso->professor?->name ?? 'Equipe LMS' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="course-panel" data-panel="configuracoes" hidden>
                <div class="course-settings__card">
                    <h2>Configuracoes do curso</h2>
                    <p>Ajustes principais, visibilidade e configuracoes de matricula.</p>

                    <div class="course-content-grid">
                        <div class="course-content-block">
                            <h3>Geral</h3>
                            <ul>
                                <li>Nome e resumo do curso</li>
                                <li>Categoria e visibilidade</li>
                                <li>Instrutor responsavel</li>
                            </ul>
                        </div>
                        <div class="course-content-block">
                            <h3>Formato</h3>
                            <ul>
                                <li>Formato semanal ou por topicos</li>
                                <li>Numero de modulos</li>
                                <li>Conclusao e certificados</li>
                            </ul>
                        </div>
                        <div class="course-content-block">
                            <h3>Acesso</h3>
                            <ul>
                                <li>Matricula aberta ou manual</li>
                                <li>Limite de vagas</li>
                                <li>Preview gratis</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="course-panel" data-panel="participantes" hidden>
                <div class="course-settings__card">
                    <h2>Participantes</h2>
                    <p>Gerencie alunos, convites pendentes e grupos vinculados.</p>
                </div>
            </div>

            <div class="course-panel" data-panel="notas" hidden>
                <div class="course-settings__card">
                    <h2>Notas</h2>
                    <p>Configure avaliacao, pesos e acompanhe desempenho da turma.</p>
                </div>
            </div>

            <div class="course-panel" data-panel="relatorios" hidden>
                <div class="course-settings__card">
                    <h2>Relatorios</h2>
                    <p>Analise progresso, engajamento e conclusao do curso.</p>
                </div>
            </div>

            <div class="course-panel" data-panel="banco-questoes" hidden>
                <div class="course-settings__card">
                    <h2>Banco de Questões</h2>
                    <p>Organize questoes por nivel, tag e tema para reutilizacao.</p>
                </div>
            </div>

            <div class="course-panel" data-panel="banco-conteudo" hidden>
                <div class="course-settings__card">
                    <h2>Banco de Conteúdo</h2>
                    <p>Centralize materiais, PDFs e arquivos reutilizaveis.</p>
                </div>
            </div>

            <div class="course-panel" data-panel="conclusao" hidden>
                <div class="course-settings__card">
                    <h2>Conclusão de curso</h2>
                    <p>Defina criterios de conclusao e liberacao de certificados.</p>
                </div>
            </div>

            <div class="course-panel" data-panel="filtros" hidden>
                <div class="course-settings__card">
                    <h2>Filtros</h2>
                    <p>Configure filtros por data, status e tipo de atividade.</p>
                </div>
            </div>

            <div class="course-panel" data-panel="reutilizar" hidden>
                <div class="course-settings__card">
                    <h2>Reutilizar curso</h2>
                    <p>Duplique conteudos e ajuste configuracoes para novas turmas.</p>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const root = document.querySelector('[data-course-settings]');
            if (!root) return;

            const tabs = root.querySelectorAll('[data-tab]');
            const panels = root.querySelectorAll('[data-panel]');
            const sidebar = root.querySelector('[data-course-sidebar]');
            const layout = root.querySelector('[data-course-layout]');

            const activate = (target) => {
                panels.forEach((panel) => {
                    panel.hidden = panel.dataset.panel !== target;
                });

                tabs.forEach((tab) => {
                    const isActive = tab.dataset.tab === target;
                    tab.classList.toggle('course-tabs__item--active', isActive);
                    if (isActive) {
                        tab.setAttribute('aria-current', 'page');
                    } else {
                        tab.removeAttribute('aria-current');
                    }
                });

                const showSidebar = target === 'curso';
                if (sidebar && layout) {
                    sidebar.classList.toggle('is-hidden', !showSidebar);
                    layout.classList.toggle('course-settings__layout--single', !showSidebar);
                }
            };

            tabs.forEach((tab) => {
                tab.addEventListener('click', (event) => {
                    event.preventDefault();
                    const target = tab.dataset.tab;
                    if (!target) return;
                    activate(target);
                });
            });

            activate('curso');
        });
    </script>
</section>