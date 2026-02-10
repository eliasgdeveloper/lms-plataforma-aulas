@php
    $courseName = $curso->titulo ?? 'Curso';
    $quickLinks = $quickLinks ?? [];
    $modules = $modules ?? [];
    $hasModules = ! empty($modules);
    $firstModulePanelId = $hasModules ? 'module-' . $modules[0]['id'] : 'module-new';
    $starterBlocks = [
        [
            'id' => 'geral',
            'label' => 'Informacoes gerais',
            'summary' => 'Nome, descricao, capa, status e agenda do curso.',
        ],
        [
            'id' => 'video',
            'label' => 'Video principal',
            'summary' => 'Upload do video de abertura e orientacoes iniciais.',
        ],
        [
            'id' => 'pdfs',
            'label' => 'PDFs e apostilas',
            'summary' => 'Materiais de apoio, planilhas e downloads.',
        ],
        [
            'id' => 'quiz',
            'label' => 'Quizzes e atividades',
            'summary' => 'Atividades interativas e checkpoints.',
        ],
        [
            'id' => 'provas',
            'label' => 'Provas e avaliacoes',
            'summary' => 'Avaliacoes formais e criterios de nota.',
        ],
        [
            'id' => 'links',
            'label' => 'Links externos',
            'summary' => 'Ferramentas externas e materiais adicionais.',
        ],
        [
            'id' => 'certificados',
            'label' => 'Certificados',
            'summary' => 'Regras e modelos de certificado do curso.',
        ],
    ];
    $isAdminContext = request()->routeIs('admin.*');
    $isProfessorContext = request()->routeIs('professor.*');
    $backUrl = $isAdminContext
        ? route('admin.cursos')
        : ($isProfessorContext ? route('professor.cursos') : url()->previous());
    $homeUrl = $isAdminContext
        ? route('admin.dashboard')
        : ($isProfessorContext ? route('professor.dashboard') : url('/'));
    $defaultPanel = 'curso';
@endphp

<section
    class="course-settings"
    aria-label="Configuracoes do curso"
    data-course-settings
    data-has-modules="{{ $hasModules ? '1' : '0' }}"
    data-default-panel="{{ $defaultPanel }}"
    data-first-module-panel="{{ $firstModulePanelId }}"
>
    <header class="course-settings__topbar">
        <div class="course-settings__links">
            @foreach ($quickLinks as $link)
                <a href="{{ $link['href'] }}">{{ $link['label'] }}</a>
            @endforeach
        </div>
        <div class="course-settings__actions">
            <a class="btn btn-ghost" href="{{ $conteudosPreviewRoute }}" target="_blank" rel="noopener noreferrer">Preview</a>
            <a class="btn btn-ghost" href="{{ $backUrl }}">Voltar</a>
            <a class="btn btn-secondary" href="{{ $homeUrl }}">Home</a>
        </div>
    </header>

    <header class="course-settings__hero">
        <h1>{{ strtoupper($courseName) }}</h1>
        <p>Edite configuracoes, organize modulos e acompanhe participantes com praticidade.</p>
    </header>

    @if (session('success'))
        <div class="course-settings__notice course-settings__notice--success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="course-settings__notice course-settings__notice--error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <nav class="course-tabs" aria-label="Menu do curso">
        <a class="course-tabs__item course-tabs__item--active" href="#curso" data-tab="curso" data-tab-role="primary" aria-current="page">Curso</a>
        <a class="course-tabs__item" href="#configuracoes" data-tab="configuracoes" data-tab-role="primary">Configurações</a>
        <a class="course-tabs__item" href="#participantes" data-tab="participantes" data-tab-role="primary">Participantes</a>
        <a class="course-tabs__item" href="#notas" data-tab="notas" data-tab-role="primary">Notas</a>
        <a class="course-tabs__item" href="#relatorios" data-tab="relatorios" data-tab-role="primary">Relatórios</a>
        <details class="course-tabs__more">
            <summary>Mais</summary>
            <div class="course-tabs__dropdown">
                <a href="#banco-questoes" data-tab="banco-questoes" data-tab-role="primary">Banco de Questões</a>
                <a href="#banco-conteudo" data-tab="banco-conteudo" data-tab-role="primary">Banco de Conteúdo</a>
                <a href="#conclusao" data-tab="conclusao" data-tab-role="primary">Conclusão de curso</a>
                <a href="#filtros" data-tab="filtros" data-tab-role="primary">Filtros</a>
                <a href="#reutilizar" data-tab="reutilizar" data-tab-role="primary">Reutilizar curso</a>
            </div>
        </details>
    </nav>

    <div class="course-settings__layout" data-course-layout>
        <aside class="course-settings__sidebar" aria-label="Topicos do curso" data-course-sidebar>
            <div class="course-settings__sidebar-header">
                <h2>Topicos</h2>
                <button class="btn btn-ghost" type="button" data-tab="module-new" data-tab-role="sidebar">+ Novo modulo</button>
            </div>

            @if ($hasModules)
                @foreach ($modules as $moduleIndex => $module)
                    @php
                        $modulePanelId = 'module-' . $module['id'];
                        $moduleIsHidden = !empty($module['is_hidden']);
                        $moduleToggleRoute = route("{$role}.cursos.aulas.toggle", [$curso, $module['id']]);
                        $moduleDeleteRoute = route("{$role}.cursos.aulas.destroy", [$curso, $module['id']]);
                    @endphp
                    <div class="course-module{{ $moduleIsHidden ? ' is-hidden' : '' }}">
                        <div class="course-module__title">
                            <button
                                class="course-module__title-link"
                                type="button"
                                data-tab="{{ $modulePanelId }}"
                                data-tab-role="sidebar"
                                data-block-id="{{ $modulePanelId }}"
                                data-fallback="{{ $module['title'] }}"
                            >
                                {{ $module['title'] }}
                            </button>
                            <div class="course-module__title-actions">
                                <span class="course-module__progress">{{ $module['progress'] ?? 0 }}%</span>
                                <button class="icon-button" type="button" data-tab="{{ $modulePanelId }}" data-tab-role="sidebar" title="Editar modulo">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20h4l10-10-4-4L4 16v4zM14 6l4 4" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                </button>
                                <form method="POST" action="{{ $moduleToggleRoute }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="icon-button" type="submit" title="{{ $moduleIsHidden ? 'Mostrar' : 'Ocultar' }}">
                                        @if ($moduleIsHidden)
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 3l18 18M10.5 10.5a2 2 0 002.8 2.8M9.9 5.1A11.5 11.5 0 0112 5c6 0 10 7 10 7a18.8 18.8 0 01-4 4.6M6.3 6.3C3.7 8.4 2 12 2 12a18.7 18.7 0 004.7 5.4A9.6 9.6 0 0012 19c1.1 0 2.1-.2 3.1-.5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                        @else
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7z" fill="none" stroke="currentColor" stroke-width="1.7"></path><circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="1.7"></circle></svg>
                                        @endif
                                    </button>
                                </form>
                                <form method="POST" action="{{ $moduleDeleteRoute }}" onsubmit="return confirm('Excluir este modulo e seus conteudos?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="icon-button" type="submit" title="Excluir">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M9 7V5h6v2M9 10v7M15 10v7M6 7l1 12h10l1-12" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="course-module__bar">
                            <span style="width: {{ $module['progress'] ?? 0 }}%"></span>
                        </div>
                        <ul>
                            @foreach ($module['items'] as $itemIndex => $item)
                                @php
                                    $panelId = 'item-' . $module['id'] . '-' . $itemIndex;
                                    $toggleRoute = route("{$role}.cursos.conteudos.toggle", [$curso, $item['id']]);
                                    $updateRoute = route("{$role}.cursos.conteudos.update", [$curso, $item['id']]);
                                    $deleteRoute = route("{$role}.cursos.conteudos.destroy", [$curso, $item['id']]);
                                    $itemIsHidden = !empty($item['is_hidden']);
                                @endphp
                                <li class="course-module__item course-module__item--{{ $item['type'] }}{{ $itemIsHidden ? ' is-hidden' : '' }}">
                                    <span class="course-module__status{{ !empty($item['completed']) ? ' is-done' : '' }}"></span>
                                    <button
                                        class="course-module__link"
                                        type="button"
                                        data-tab="{{ $panelId }}"
                                        data-tab-role="sidebar"
                                        data-block-id="{{ $panelId }}"
                                        data-fallback="{{ $item['label'] }}"
                                    >
                                        {{ $item['label'] }}
                                    </button>
                                    <div class="course-module__actions">
                                        <form method="POST" action="{{ $toggleRoute }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="icon-button" type="submit" title="{{ $itemIsHidden ? 'Mostrar' : 'Ocultar' }}">
                                                @if ($itemIsHidden)
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 3l18 18M10.5 10.5a2 2 0 002.8 2.8M9.9 5.1A11.5 11.5 0 0112 5c6 0 10 7 10 7a18.8 18.8 0 01-4 4.6M6.3 6.3C3.7 8.4 2 12 2 12a18.7 18.7 0 004.7 5.4A9.6 9.6 0 0012 19c1.1 0 2.1-.2 3.1-.5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                                @else
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7z" fill="none" stroke="currentColor" stroke-width="1.7"></path><circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="1.7"></circle></svg>
                                                @endif
                                            </button>
                                        </form>
                                        <button class="icon-button" type="button" data-tab="{{ $panelId }}" data-tab-role="sidebar" title="Editar">
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20h4l10-10-4-4L4 16v4zM14 6l4 4" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                        </button>
                                        <form method="POST" action="{{ $deleteRoute }}" onsubmit="return confirm('Excluir este conteudo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="icon-button" type="submit" title="Excluir">
                                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M9 7V5h6v2M9 10v7M15 10v7M6 7l1 12h10l1-12" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            @else
                <div class="course-empty-menu">
                    <p class="course-empty-menu__hint">Nenhum modulo criado ainda.</p>
                    <div class="course-empty-menu__list course-empty-menu__list--modules">
                        @foreach (['Modulo 1', 'Modulo 2', 'Modulo 3'] as $placeholder)
                            <button
                                class="course-empty-menu__item course-empty-menu__item--module"
                                type="button"
                                data-tab="module-new"
                                data-tab-role="sidebar"
                            >
                                <div>
                                    <strong>{{ $placeholder }}</strong>
                                    <span>Adicionar atividades e materiais</span>
                                </div>
                                <span class="course-section__empty">vazio</span>
                            </button>
                        @endforeach
                    </div>
                    <button class="btn btn-secondary" type="button" data-tab="module-new" data-tab-role="sidebar">Criar primeiro modulo</button>
                </div>
            @endif
        </aside>

        <section class="course-settings__content">
            <div class="course-panel" data-panel="curso">
                <div class="course-settings__card course-overview">
                    <div class="course-overview__header">
                        <div>
                            <p class="course-overview__eyebrow">Workspace do curso</p>
                            <h2>Organize aulas, tarefas e materiais</h2>
                            <p>Monte o curso em blocos. Clique no menu lateral para editar cada topico.</p>
                        </div>
                        <div class="course-overview__meta">
                            <div>
                                <span>Topicos</span>
                                <strong>{{ $hasModules ? count($modules) : 0 }}</strong>
                            </div>
                            <div>
                                <span>Status</span>
                                <strong>{{ ucfirst($curso->status ?? 'rascunho') }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="course-overview__body">
                        <div class="course-player">
                            <div class="course-player__frame">
                                <div class="course-player__placeholder">
                                    <span>Preview do conteudo</span>
                                    <strong>Carregue um video ou aula para visualizar aqui.</strong>
                                </div>
                            </div>
                            <div class="course-player__actions">
                                <button class="btn btn-secondary" type="button" data-quick-action="video" data-quick-pickfile="1">Enviar video</button>
                                <button class="btn btn-ghost" type="button" data-quick-action="tarefa">Adicionar tarefa</button>
                                <button class="btn btn-ghost" type="button" data-quick-action="quiz">Criar quiz</button>
                            </div>
                        </div>

                        <div class="course-overview__side">
                            <div class="course-overview__card">
                                <h3>Atividades e funcoes</h3>
                                <div class="course-action-list">
                                    @foreach ($starterBlocks as $block)
                                        <button
                                            class="course-action"
                                            type="button"
                                            data-tab="{{ $block['id'] }}"
                                            data-tab-role="sidebar"
                                            data-block-id="{{ $block['id'] }}"
                                            data-fallback="{{ $block['label'] }}"
                                        >
                                            <strong>{{ $block['label'] }}</strong>
                                            <span>{{ $block['summary'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                                <button class="btn btn-primary" type="button" data-tab="module-new" data-tab-role="sidebar">Criar primeiro modulo</button>
                            </div>
                            <div class="course-overview__card">
                                <h3>Resumo do curso</h3>
                                <div class="course-summary">
                                    <div>
                                        <span>Categoria</span>
                                        <strong>{{ $curso->categoria ?? 'Sem categoria' }}</strong>
                                    </div>
                                    <div>
                                        <span>Instrutor</span>
                                        <strong>{{ $curso->professor?->name ?? 'Equipe LMS' }}</strong>
                                    </div>
                                    <div>
                                        <span>Visibilidade</span>
                                        <strong>{{ ucfirst($curso->status ?? 'rascunho') }}</strong>
                                    </div>
                                </div>
                            </div>
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

            <div class="course-panel" data-panel="module-new" hidden>
                <div class="course-settings__card">
                    <h2>Novo modulo</h2>
                    <p>Crie uma sessao para agrupar atividades, materiais e avaliacao.</p>

                    <form method="POST" action="{{ $aulasStoreRoute }}">
                        @csrf
                        <div class="course-editor__row">
                            <label class="course-field">
                                <span>Titulo do modulo</span>
                                <input type="text" name="titulo" placeholder="Ex: Modulo 1 - Introducao" required>
                            </label>
                            <label class="course-field">
                                <span>Ordem</span>
                                <input type="number" name="ordem" min="0" placeholder="0">
                            </label>
                        </div>

                        <label class="course-field">
                            <span>Descricao</span>
                            <textarea name="descricao" rows="3" placeholder="Resumo do que sera abordado"></textarea>
                        </label>

                        <div class="course-settings__actions">
                            <button class="btn btn-primary" type="submit">Salvar</button>
                            <a class="btn btn-ghost" href="{{ $conteudosPreviewRoute }}" target="_blank" rel="noopener noreferrer">Ver</a>
                            <button class="btn btn-ghost" type="button" data-tab="curso" data-tab-role="sidebar">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="course-panel" data-panel="geral" hidden>
                <div class="course-settings__card">
                    <h2>Informacoes gerais</h2>
                    <p>Defina os dados basicos e o posicionamento do curso.</p>

                    <div class="course-editor__row">
                        <label class="course-field">
                            <span>Titulo do topico</span>
                            <input type="text" value="Informacoes gerais" data-block-title-input data-block-id="geral">
                        </label>
                        <label class="course-field">
                            <span>Resumo do topico</span>
                            <input type="text" value="Nome, descricao, capa e status">
                        </label>
                    </div>

                    <div class="course-content-grid">
                        <div class="course-content-block">
                            <h3>Descricao e objetivos</h3>
                            <ul>
                                <li>Nome, resumo e nivel do curso</li>
                                <li>Categoria, tags e instrutor</li>
                                <li>Status e visibilidade</li>
                            </ul>
                        </div>
                        <div class="course-content-block">
                            <h3>Agenda e matriculas</h3>
                            <ul>
                                <li>Datas de abertura e encerramento</li>
                                <li>Regras de acesso e vagas</li>
                                <li>Preview e conteudo gratuito</li>
                            </ul>
                        </div>
                        <div class="course-content-block">
                            <h3>Capas e identidade</h3>
                            <ul>
                                <li>Capa, banner e miniatura</li>
                                <li>Cores e identidade visual</li>
                                <li>Certificado padrao</li>
                            </ul>
                        </div>
                    </div>

                    <div class="course-settings__actions">
                        <button class="btn btn-primary" type="button">Editar dados do curso</button>
                        <button class="btn btn-ghost" type="button">Salvar rascunho</button>
                    </div>
                </div>
            </div>

            <div class="course-panel" data-panel="video" hidden>
                <div class="course-settings__card">
                    <h2>Video principal</h2>
                    <p>Envie o video de boas-vindas e organize a aula inicial.</p>

                    <div class="course-editor__row">
                        <label class="course-field">
                            <span>Titulo do topico</span>
                            <input type="text" value="Video principal" data-block-title-input data-block-id="video">
                        </label>
                        <label class="course-field">
                            <span>Duracao estimada</span>
                            <input type="text" placeholder="Ex: 5 min">
                        </label>
                    </div>

                    <div class="course-content-grid">
                        <div class="course-content-block">
                            <h3>Upload rapido</h3>
                            <p>Envie MP4 ou conecte com YouTube/Vimeo.</p>
                            <button class="btn btn-secondary" type="button" data-quick-action="video" data-quick-pickfile="1">Enviar video</button>
                        </div>
                        <div class="course-content-block">
                            <h3>Rotina de aula</h3>
                            <p>Defina duracao, modulo e requisitos.</p>
                            <button class="btn btn-ghost" type="button">Adicionar detalhes</button>
                        </div>
                        <div class="course-content-block">
                            <h3>Materiais extras</h3>
                            <p>Inclua roteiro, slides ou links de apoio.</p>
                            <button class="btn btn-ghost" type="button" data-quick-action="arquivo" data-quick-pickfile="1">Adicionar anexos</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="course-panel" data-panel="pdfs" hidden>
                <div class="course-settings__card">
                    <h2>PDFs e apostilas</h2>
                    <p>Centralize materiais de leitura e exercicios.</p>

                    <div class="course-editor__row">
                        <label class="course-field">
                            <span>Titulo do topico</span>
                            <input type="text" value="PDFs e apostilas" data-block-title-input data-block-id="pdfs">
                        </label>
                        <label class="course-field">
                            <span>Descricao rapida</span>
                            <input type="text" placeholder="Ex: Materiais complementares">
                        </label>
                    </div>

                    <div class="course-content-grid">
                        <div class="course-content-block">
                            <h3>Enviar arquivos</h3>
                            <p>Suporta PDF, DOCX e planilhas.</p>
                            <button class="btn btn-secondary" type="button" data-quick-action="pdf" data-quick-pickfile="1">Adicionar arquivos</button>
                        </div>
                        <div class="course-content-block">
                            <h3>Organizar pastas</h3>
                            <p>Separe por modulo ou semana.</p>
                            <button class="btn btn-ghost" type="button">Criar pasta</button>
                        </div>
                        <div class="course-content-block">
                            <h3>Controle de acesso</h3>
                            <p>Defina liberacao por progresso.</p>
                            <button class="btn btn-ghost" type="button">Configurar acesso</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="course-panel" data-panel="quiz" hidden>
                <div class="course-settings__card">
                    <h2>Quizzes e atividades</h2>
                    <p>Crie atividades interativas para reforcar o aprendizado.</p>

                    <div class="course-editor__row">
                        <label class="course-field">
                            <span>Titulo do topico</span>
                            <input type="text" value="Quizzes e atividades" data-block-title-input data-block-id="quiz">
                        </label>
                        <label class="course-field">
                            <span>Peso na nota</span>
                            <input type="text" placeholder="Ex: 20%">
                        </label>
                    </div>

                    <div class="course-content-grid">
                        <div class="course-content-block">
                            <h3>Gerar quiz</h3>
                            <p>Monte perguntas objetivas e discursivas.</p>
                            <button class="btn btn-secondary" type="button">Criar quiz</button>
                        </div>
                        <div class="course-content-block">
                            <h3>Banco de questoes</h3>
                            <p>Importe ou reuse questoes prontas.</p>
                            <button class="btn btn-ghost" type="button">Importar questoes</button>
                        </div>
                        <div class="course-content-block">
                            <h3>Atividades praticas</h3>
                            <p>Defina entregas, prazos e rubricas.</p>
                            <button class="btn btn-ghost" type="button">Criar atividade</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="course-panel" data-panel="provas" hidden>
                <div class="course-settings__card">
                    <h2>Provas e avaliacoes</h2>
                    <p>Configure provas, pesos e criterios de aprovacao.</p>

                    <div class="course-editor__row">
                        <label class="course-field">
                            <span>Titulo do topico</span>
                            <input type="text" value="Provas e avaliacoes" data-block-title-input data-block-id="provas">
                        </label>
                        <label class="course-field">
                            <span>Data prevista</span>
                            <input type="text" placeholder="Ex: 10/03/2026">
                        </label>
                    </div>

                    <div class="course-content-grid">
                        <div class="course-content-block">
                            <h3>Criar prova</h3>
                            <p>Monte provas por modulo ou etapa.</p>
                            <button class="btn btn-secondary" type="button">Nova prova</button>
                        </div>
                        <div class="course-content-block">
                            <h3>Agendamento</h3>
                            <p>Defina janela de aplicacao e tempo.</p>
                            <button class="btn btn-ghost" type="button">Agendar</button>
                        </div>
                        <div class="course-content-block">
                            <h3>Politica de notas</h3>
                            <p>Configure pesos, recuperacao e media.</p>
                            <button class="btn btn-ghost" type="button">Configurar notas</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="course-panel" data-panel="links" hidden>
                <div class="course-settings__card">
                    <h2>Links externos</h2>
                    <p>Adicione links, ferramentas e recursos externos.</p>

                    <div class="course-editor__row">
                        <label class="course-field">
                            <span>Titulo do topico</span>
                            <input type="text" value="Links externos" data-block-title-input data-block-id="links">
                        </label>
                        <label class="course-field">
                            <span>Destino</span>
                            <input type="url" placeholder="https://...">
                        </label>
                    </div>

                    <div class="course-content-grid">
                        <div class="course-content-block">
                            <h3>Adicionar link</h3>
                            <p>Links para aulas ao vivo, docs ou ferramentas.</p>
                            <button class="btn btn-secondary" type="button">Novo link</button>
                        </div>
                        <div class="course-content-block">
                            <h3>Checklist de acesso</h3>
                            <p>Exija leitura ou aceite de termos.</p>
                            <button class="btn btn-ghost" type="button">Configurar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="course-panel" data-panel="certificados" hidden>
                <div class="course-settings__card">
                    <h2>Certificados</h2>
                    <p>Defina criterios e personalize o certificado.</p>

                    <div class="course-editor__row">
                        <label class="course-field">
                            <span>Titulo do topico</span>
                            <input type="text" value="Certificados" data-block-title-input data-block-id="certificados">
                        </label>
                        <label class="course-field">
                            <span>Regra principal</span>
                            <input type="text" placeholder="Ex: 80% de conclusao">
                        </label>
                    </div>

                    <div class="course-content-grid">
                        <div class="course-content-block">
                            <h3>Modelo do certificado</h3>
                            <p>Escolha layout, assinatura e selo.</p>
                            <button class="btn btn-secondary" type="button">Selecionar modelo</button>
                        </div>
                        <div class="course-content-block">
                            <h3>Regras de conclusao</h3>
                            <p>Configure presenca minima e notas.</p>
                            <button class="btn btn-ghost" type="button">Configurar regras</button>
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

            @if ($hasModules)
                @foreach ($modules as $moduleIndex => $module)
                    @php
                        $modulePanelId = 'module-' . $module['id'];
                        $moduleUpdateRoute = route("{$role}.cursos.aulas.update", [$curso, $module['id']]);
                    @endphp
                    <div class="course-panel" data-panel="{{ $modulePanelId }}" hidden>
                        <div class="course-settings__card">
                            <div class="course-editor__header">
                                <div>
                                    <p class="course-editor__eyebrow">Modulo</p>
                                    <h2>Editar modulo</h2>
                                    <p>Atualize o titulo, descricao e ordem deste topico.</p>
                                </div>
                                <div class="course-editor__badge">MODULO</div>
                            </div>

                            <form method="POST" action="{{ $moduleUpdateRoute }}">
                                @csrf
                                @method('PUT')
                                <div class="course-editor__row">
                                    <label class="course-field">
                                        <span>Titulo do modulo</span>
                                        <input
                                            type="text"
                                            name="titulo"
                                            value="{{ $module['title'] }}"
                                            data-block-title-input
                                            data-block-id="{{ $modulePanelId }}"
                                            required
                                        >
                                    </label>
                                    <label class="course-field">
                                        <span>Ordem</span>
                                        <input type="number" name="ordem" min="0" value="{{ $module['ordem'] ?? 0 }}">
                                    </label>
                                </div>

                                <label class="course-field">
                                    <span>Descricao</span>
                                    <textarea name="descricao" rows="3">{{ $module['descricao'] ?? '' }}</textarea>
                                </label>

                                <div class="course-settings__actions">
                                    <button class="btn btn-primary" type="submit">Salvar</button>
                                    <a class="btn btn-ghost" href="{{ $conteudosPreviewRoute }}" target="_blank" rel="noopener noreferrer">Ver</a>
                                    <button class="btn btn-ghost" type="button" data-tab="curso" data-tab-role="sidebar">Cancelar</button>
                                </div>
                            </form>
                        </div>

                        <div class="course-settings__card">
                            <div class="course-editor__header">
                                <div>
                                    <p class="course-editor__eyebrow">Adicionar atividade</p>
                                    <h2>Criar novo conteudo</h2>
                                    <p>Inclua videos, provas, quizzes, PDFs ou links neste modulo.</p>
                                </div>
                            </div>

                            <form method="POST" action="{{ $conteudosStoreRoute }}" enctype="multipart/form-data" data-content-form>
                                @csrf
                                <input type="hidden" name="aula_id" value="{{ $module['id'] }}">
                                <div class="course-editor__row">
                                    <label class="course-field">
                                        <span>Titulo da atividade</span>
                                        <input type="text" name="titulo" placeholder="Ex: Aula 1 - Apresentacao" required data-content-title>
                                    </label>
                                    <label class="course-field">
                                        <span>Tipo</span>
                                        <select name="tipo" required data-content-type>
                                            <option value="video">Video</option>
                                            <option value="pdf">PDF</option>
                                            <option value="link">Link</option>
                                            <option value="texto">Texto</option>
                                            <option value="arquivo">Arquivo</option>
                                            <option value="word">Word</option>
                                            <option value="excel">Excel</option>
                                            <option value="quiz">Quiz</option>
                                            <option value="prova">Prova</option>
                                            <option value="tarefa">Tarefa</option>
                                        </select>
                                    </label>
                                </div>

                                <label class="course-field">
                                    <span>Descricao</span>
                                    <textarea name="descricao" rows="3" placeholder="Resumo da atividade"></textarea>
                                </label>

                                <div class="course-editor__row">
                                    <label class="course-field">
                                        <span>URL (opcional)</span>
                                        <input type="url" name="url" placeholder="https://..."></input>
                                    </label>
                                    <label class="course-field">
                                        <span>Arquivo (opcional)</span>
                                        <span class="course-upload" data-upload>
                                            <input class="course-upload__input" type="file" name="arquivo" data-content-file data-upload-input>
                                            <span class="course-upload__surface">
                                                <span class="course-upload__badge" data-upload-badge>ARQ</span>
                                                <span class="course-upload__text">
                                                    <strong data-upload-title>Solte o arquivo aqui ou clique para escolher</strong>
                                                    <small data-upload-name>Nenhum arquivo selecionado</small>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>

                                <label class="course-field">
                                    <span>Ordem</span>
                                    <input type="number" name="ordem" min="0" placeholder="0">
                                </label>

                                <div class="course-settings__actions">
                                    <button class="btn btn-primary" type="submit">Salvar</button>
                                    <a class="btn btn-ghost" href="{{ $conteudosPreviewRoute }}" target="_blank" rel="noopener noreferrer">Ver</a>
                                    <button class="btn btn-ghost" type="button" data-tab="{{ $modulePanelId }}" data-tab-role="sidebar">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @foreach ($module['items'] as $itemIndex => $item)
                        @php
                            $panelId = 'item-' . $module['id'] . '-' . $itemIndex;
                            $itemType = $item['type'] ?? 'lesson';
                            $updateRoute = route("{$role}.cursos.conteudos.update", [$curso, $item['id']]);
                            $arquivoUrl = $item['arquivo'] ? asset('storage/' . $item['arquivo']) : null;
                            $typeMeta = [
                                'video' => [
                                    'title' => 'Video do modulo',
                                    'description' => 'Atualize o video e materiais de apoio.',
                                    'primary' => 'Enviar video',
                                ],
                                'pdf' => [
                                    'title' => 'PDF e apostilas',
                                    'description' => 'Suba arquivos de leitura e apoio.',
                                    'primary' => 'Enviar PDF',
                                ],
                                'link' => [
                                    'title' => 'Link externo',
                                    'description' => 'Conecte ferramentas e paginas externas.',
                                    'primary' => 'Adicionar link',
                                ],
                                'texto' => [
                                    'title' => 'Conteudo em texto',
                                    'description' => 'Edite o texto e os anexos do topico.',
                                    'primary' => 'Editar texto',
                                ],
                                'arquivo' => [
                                    'title' => 'Arquivo geral',
                                    'description' => 'Envie arquivos extras e materiais.',
                                    'primary' => 'Enviar arquivo',
                                ],
                                'word' => [
                                    'title' => 'Documento Word',
                                    'description' => 'Suba o documento e materiais complementares.',
                                    'primary' => 'Enviar Word',
                                ],
                                'excel' => [
                                    'title' => 'Planilha Excel',
                                    'description' => 'Inclua planilhas e dados de apoio.',
                                    'primary' => 'Enviar Excel',
                                ],
                                'lesson' => [
                                    'title' => 'Aula em video',
                                    'description' => 'Atualize o video, roteiro e anexos do topico.',
                                    'primary' => 'Enviar video',
                                ],
                                'quiz' => [
                                    'title' => 'Quiz interativo',
                                    'description' => 'Edite perguntas, tempo e criterios de nota.',
                                    'primary' => 'Editar quiz',
                                ],
                                'prova' => [
                                    'title' => 'Prova e avaliacao',
                                    'description' => 'Configure questoes e criterios de nota.',
                                    'primary' => 'Editar prova',
                                ],
                                'tarefa' => [
                                    'title' => 'Tarefa pratica',
                                    'description' => 'Ajuste entrega, prazo e rubricas.',
                                    'primary' => 'Editar tarefa',
                                ],
                                'alert' => [
                                    'title' => 'Aviso e comunicados',
                                    'description' => 'Atualize o texto e a prioridade do aviso.',
                                    'primary' => 'Editar aviso',
                                ],
                            ];
                            $meta = $typeMeta[$itemType] ?? $typeMeta['lesson'];
                        @endphp
                        <div class="course-panel" data-panel="{{ $panelId }}" hidden>
                            <div class="course-settings__card">
                                <div class="course-editor__header">
                                    <div>
                                        <p class="course-editor__eyebrow">{{ $module['title'] }}</p>
                                        <h2>{{ $meta['title'] }}</h2>
                                        <p>{{ $meta['description'] }}</p>
                                    </div>
                                    <div class="course-editor__badge">{{ strtoupper($itemType) }}</div>
                                </div>

                                @if (in_array($itemType, ['video', 'pdf', 'word', 'excel', 'arquivo', 'link', 'texto'], true))
                                    <div class="course-item-preview">
                                        @switch($itemType)
                                            @case('video')
                                                @if ($arquivoUrl)
                                                    <video controls preload="metadata" src="{{ $arquivoUrl }}"></video>
                                                @elseif (!empty($item['url']))
                                                    <div class="course-item-preview__embed">
                                                        <iframe
                                                            src="{{ $item['url'] }}"
                                                            title="Preview do video"
                                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                            allowfullscreen
                                                        ></iframe>
                                                    </div>
                                                @else
                                                    <div class="course-item-preview__empty">Envie um video para visualizar aqui.</div>
                                                @endif
                                                @break
                                            @case('pdf')
                                                @if ($arquivoUrl)
                                                    <div class="course-item-preview__embed">
                                                        <iframe src="{{ $arquivoUrl }}" title="Preview do PDF"></iframe>
                                                    </div>
                                                @elseif (!empty($item['url']))
                                                    <div class="course-item-preview__embed">
                                                        <iframe src="{{ $item['url'] }}" title="Preview do PDF"></iframe>
                                                    </div>
                                                @else
                                                    <div class="course-item-preview__empty">Envie um PDF para visualizar aqui.</div>
                                                @endif
                                                @break
                                            @case('word')
                                            @case('excel')
                                            @case('arquivo')
                                                @if ($arquivoUrl)
                                                    <div class="course-item-preview__file">
                                                        <span>Arquivo anexado:</span>
                                                        <a href="{{ $arquivoUrl }}" target="_blank" rel="noopener noreferrer">Abrir arquivo</a>
                                                    </div>
                                                @elseif (!empty($item['url']))
                                                    <div class="course-item-preview__file">
                                                        <span>Link do arquivo:</span>
                                                        <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer">Abrir link</a>
                                                    </div>
                                                @else
                                                    <div class="course-item-preview__empty">Envie um arquivo para visualizar aqui.</div>
                                                @endif
                                                @break
                                            @case('link')
                                                @if (!empty($item['url']))
                                                    <div class="course-item-preview__file">
                                                        <span>Link externo:</span>
                                                        <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer">Abrir link</a>
                                                    </div>
                                                @else
                                                    <div class="course-item-preview__empty">Adicione um link para visualizar aqui.</div>
                                                @endif
                                                @break
                                            @case('texto')
                                                @if (!empty($item['url']))
                                                    <div class="course-item-preview__text">{!! nl2br(e($item['url'])) !!}</div>
                                                @elseif (!empty($item['descricao']))
                                                    <div class="course-item-preview__text">{!! nl2br(e($item['descricao'])) !!}</div>
                                                @else
                                                    <div class="course-item-preview__empty">Adicione um texto para visualizar aqui.</div>
                                                @endif
                                                @break
                                        @endswitch
                                    </div>
                                @endif

                                <form method="POST" action="{{ $updateRoute }}" enctype="multipart/form-data" data-content-form>
                                    @csrf
                                    @method('PUT')
                                    <div class="course-editor__row">
                                        <label class="course-field">
                                            <span>Titulo do topico</span>
                                            <input
                                                type="text"
                                                name="titulo"
                                                value="{{ $item['label'] }}"
                                                data-block-title-input
                                                data-block-id="{{ $panelId }}"
                                                required
                                                data-content-title
                                            >
                                        </label>
                                        <label class="course-field">
                                            <span>Tipo</span>
                                            <select name="tipo" required data-content-type>
                                                <option value="video" {{ $itemType === 'video' ? 'selected' : '' }}>Video</option>
                                                <option value="pdf" {{ $itemType === 'pdf' ? 'selected' : '' }}>PDF</option>
                                                <option value="link" {{ $itemType === 'link' ? 'selected' : '' }}>Link</option>
                                                <option value="texto" {{ $itemType === 'texto' ? 'selected' : '' }}>Texto</option>
                                                <option value="arquivo" {{ $itemType === 'arquivo' ? 'selected' : '' }}>Arquivo</option>
                                                <option value="word" {{ $itemType === 'word' ? 'selected' : '' }}>Word</option>
                                                <option value="excel" {{ $itemType === 'excel' ? 'selected' : '' }}>Excel</option>
                                                <option value="quiz" {{ $itemType === 'quiz' ? 'selected' : '' }}>Quiz</option>
                                                <option value="prova" {{ $itemType === 'prova' ? 'selected' : '' }}>Prova</option>
                                                <option value="tarefa" {{ $itemType === 'tarefa' ? 'selected' : '' }}>Tarefa</option>
                                            </select>
                                        </label>
                                    </div>

                                    <label class="course-field">
                                        <span>Descricao</span>
                                        <textarea name="descricao" rows="3">{{ $item['descricao'] ?? '' }}</textarea>
                                    </label>

                                    <div class="course-editor__row">
                                        <label class="course-field">
                                            <span>URL (opcional)</span>
                                            <input type="url" name="url" value="{{ $item['url'] ?? '' }}">
                                        </label>
                                        <label class="course-field">
                                            <span>Arquivo (opcional)</span>
                                            <span class="course-upload" data-upload>
                                                <input class="course-upload__input" type="file" name="arquivo" data-content-file data-upload-input>
                                                <span class="course-upload__surface">
                                                    <span class="course-upload__badge" data-upload-badge>ARQ</span>
                                                    <span class="course-upload__text">
                                                        <strong data-upload-title>Solte o arquivo aqui ou clique para escolher</strong>
                                                        <small data-upload-name>Nenhum arquivo selecionado</small>
                                                    </span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>

                                    <label class="course-field">
                                        <span>Ordem</span>
                                        <input type="number" name="ordem" min="0" value="{{ $item['ordem'] ?? 0 }}">
                                    </label>

                                    @if ($arquivoUrl)
                                        <p class="course-file">Arquivo atual: <a href="{{ $arquivoUrl }}" target="_blank" rel="noopener noreferrer">Abrir</a></p>
                                    @endif

                                    <div class="course-settings__actions">
                                        <button class="btn btn-primary" type="submit">Salvar</button>
                                        <a class="btn btn-ghost" href="{{ $conteudosPreviewRoute }}" target="_blank" rel="noopener noreferrer">Ver</a>
                                        <button class="btn btn-ghost" type="button" data-tab="{{ $modulePanelId }}" data-tab-role="sidebar">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            @endif
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const root = document.querySelector('[data-course-settings]');
            if (!root) return;

            const tabs = root.querySelectorAll('[data-tab]');
            const primaryTabs = root.querySelectorAll('[data-tab-role="primary"]');
            const sidebarTabs = root.querySelectorAll('[data-tab-role="sidebar"]');
            const panels = root.querySelectorAll('[data-panel]');
            const sidebar = root.querySelector('[data-course-sidebar]');
            const layout = root.querySelector('[data-course-layout]');
            const hasModules = root.dataset.hasModules === '1';
            const defaultPanel = root.dataset.defaultPanel || 'curso';
            const firstModulePanel = root.dataset.firstModulePanel || 'module-new';
            const primaryTargets = new Set(Array.from(primaryTabs).map((tab) => tab.dataset.tab));

            const activate = (target) => {
                panels.forEach((panel) => {
                    panel.hidden = panel.dataset.panel !== target;
                });

                const primaryActive = primaryTargets.has(target) ? target : 'curso';
                primaryTabs.forEach((tab) => {
                    const isActive = tab.dataset.tab === primaryActive;
                    tab.classList.toggle('course-tabs__item--active', isActive);
                    if (isActive) {
                        tab.setAttribute('aria-current', 'page');
                    } else {
                        tab.removeAttribute('aria-current');
                    }
                });

                sidebarTabs.forEach((tab) => {
                    const isActive = tab.dataset.tab === target;
                    tab.classList.toggle('is-active', isActive);
                });

                const showSidebar = true;
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

            const titleInputs = root.querySelectorAll('[data-block-title-input]');
            titleInputs.forEach((input) => {
                input.addEventListener('input', () => {
                    const id = input.dataset.blockId;
                    if (!id) return;
                    const target = root.querySelector(`[data-block-id="${id}"]`);
                    if (!target) return;
                    const fallback = target.dataset.fallback || target.textContent;
                    const nextValue = input.value.trim();
                    target.textContent = nextValue.length ? nextValue : fallback;
                });
            });

            const fileKinds = {
                pdf: { label: 'PDF', kind: 'pdf' },
                doc: { label: 'DOC', kind: 'doc' },
                docx: { label: 'DOCX', kind: 'doc' },
                xls: { label: 'XLS', kind: 'xls' },
                xlsx: { label: 'XLSX', kind: 'xls' },
                csv: { label: 'CSV', kind: 'xls' },
                mp4: { label: 'MP4', kind: 'video' },
                mov: { label: 'MOV', kind: 'video' },
                mkv: { label: 'MKV', kind: 'video' },
                zip: { label: 'ZIP', kind: 'zip' },
                rar: { label: 'RAR', kind: 'zip' },
                '7z': { label: '7Z', kind: 'zip' },
                png: { label: 'IMG', kind: 'img' },
                jpg: { label: 'IMG', kind: 'img' },
                jpeg: { label: 'IMG', kind: 'img' },
                gif: { label: 'IMG', kind: 'img' },
            };

            const updateUploadState = (input) => {
                const wrapper = input.closest('[data-upload]');
                if (!wrapper) return;
                const badge = wrapper.querySelector('[data-upload-badge]');
                const title = wrapper.querySelector('[data-upload-title]');
                const name = wrapper.querySelector('[data-upload-name]');
                const file = input.files && input.files[0] ? input.files[0] : null;

                if (!badge || !title || !name) return;

                if (!file) {
                    badge.textContent = 'ARQ';
                    badge.dataset.kind = 'default';
                    title.textContent = 'Solte o arquivo aqui ou clique para escolher';
                    name.textContent = 'Nenhum arquivo selecionado';
                    wrapper.classList.remove('has-file');
                    return;
                }

                const fileName = file.name || 'arquivo';
                const extension = fileName.includes('.')
                    ? fileName.split('.').pop().toLowerCase()
                    : '';
                const meta = fileKinds[extension] || { label: 'ARQ', kind: 'default' };

                badge.textContent = meta.label;
                badge.dataset.kind = meta.kind;
                title.textContent = 'Arquivo selecionado';
                name.textContent = fileName;
                wrapper.classList.add('has-file');
            };

            const uploadInputs = root.querySelectorAll('[data-upload-input]');
            uploadInputs.forEach((input) => {
                updateUploadState(input);
                input.addEventListener('change', () => updateUploadState(input));
            });

            const openContentForm = (panelId, type, pickFile) => {
                const panel = root.querySelector(`[data-panel="${panelId}"]`);
                if (!panel) return;
                const form = panel.querySelector('[data-content-form]');
                if (!form) return;

                const typeSelect = form.querySelector('[data-content-type]');
                if (type && typeSelect) {
                    typeSelect.value = type;
                    typeSelect.dispatchEvent(new Event('change', { bubbles: true }));
                }

                const titleInput = form.querySelector('[data-content-title]');
                if (titleInput) {
                    titleInput.focus({ preventScroll: true });
                }

                const fileInput = form.querySelector('[data-content-file]');
                if (pickFile && fileInput) {
                    fileInput.click();
                }

                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            };

            const quickButtons = root.querySelectorAll('[data-quick-action]');
            quickButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    if (!hasModules) {
                        activate('module-new');
                        return;
                    }

                    const targetPanel = button.dataset.quickTarget || firstModulePanel;
                    activate(targetPanel);
                    openContentForm(targetPanel, button.dataset.quickAction, button.dataset.quickPickfile === '1');
                });
            });

            activate(defaultPanel);
        });
    </script>
</section>