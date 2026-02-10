@php
    $contextTitle = $contextTitle ?? 'Criar novo curso';
    $contextSubtitle = $contextSubtitle ?? 'Monte o curso com estrutura simples e pronta para escalar.';
    $formAction = $formAction ?? '#';
    $formMethod = $formMethod ?? 'POST';
    $course = $curso ?? null;
@endphp

<section class="course-create" aria-label="Formulario de criacao de curso">
    <header class="course-create__hero">
        <div>
            <p class="course-create__eyebrow">LMS Studio</p>
            <h1>{{ $contextTitle }}</h1>
            <p>{{ $contextSubtitle }}</p>
        </div>
        <div class="course-create__tips" aria-label="Boas praticas">
            <h2>Checklist rapido</h2>
            <ul>
                <li>Defina titulo, descricao e categoria</li>
                <li>Escolha capa, cores e tags SEO</li>
                <li>Crie modulo, licao e grupo</li>
                <li>Selecione alunos para matricula</li>
            </ul>
        </div>
    </header>

    <form class="course-form" method="POST" enctype="multipart/form-data" action="{{ $formAction }}">
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif

        <!-- Informacoes principais do curso -->
        <section class="course-panel">
            <header>
                <h2>Informacoes basicas</h2>
                <p>Conteudo principal exibido nos cards e paginas do curso.</p>
            </header>

            <div class="course-grid">
                <label>
                    <span>Titulo do curso *</span>
                    <input type="text" name="titulo" placeholder="Ex: JavaScript Moderno" value="{{ old('titulo', $course?->titulo) }}" required>
                </label>

                <label>
                    <span>Slug amigavel (SEO)</span>
                    <input type="text" name="slug" placeholder="javascript-moderno">
                </label>

                <label class="course-grid__full">
                    <span>Descricao do curso *</span>
                    <textarea name="descricao" rows="4" placeholder="Resumo objetivo com beneficios e resultados esperados." required>{{ old('descricao', $course?->descricao) }}</textarea>
                </label>

                <label>
                    <span>Categoria</span>
                    <select name="categoria">
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected(old('categoria', $course?->categoria) === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </label>

                <label>
                    <span>Nivel</span>
                    <select name="nivel">
                        <option value="basico">Basico</option>
                        <option value="intermediario">Intermediario</option>
                        <option value="avancado">Avancado</option>
                    </select>
                </label>

                <label>
                    <span>Duracao estimada</span>
                    <input type="text" name="duracao" placeholder="Ex: 24h">
                </label>

                <label>
                    <span>Status</span>
                    <select name="status">
                        <option value="rascunho" @selected(old('status', $course?->status ?? 'rascunho') === 'rascunho')>Rascunho</option>
                        <option value="ativo" @selected(old('status', $course?->status ?? 'rascunho') === 'ativo')>Ativo</option>
                        <option value="oculto" @selected(old('status', $course?->status ?? 'rascunho') === 'oculto')>Oculto</option>
                        <option value="agendado" @selected(old('status', $course?->status ?? 'rascunho') === 'agendado')>Agendado</option>
                    </select>
                </label>
            </div>
        </section>

        <!-- Identidade visual e uploads -->
        <section class="course-panel">
            <header>
                <h2>Identidade visual</h2>
                <p>Personalize capa, cores e anexos do curso.</p>
            </header>

            <div class="course-grid">
                <label>
                    <span>Cor principal</span>
                    <input type="color" name="cor" value="#1d4ed8">
                </label>

                <label>
                    <span>Imagem de capa</span>
                    <input type="file" name="capa" accept="image/*">
                </label>

                <label class="course-grid__full">
                    <span>Arquivos extras (PDF, zip, imagens)</span>
                    <input type="file" name="materiais[]" multiple>
                </label>
            </div>
        </section>

        <!-- Estrutura do curso -->
        <section class="course-panel">
            <header>
                <h2>Estrutura do curso</h2>
                <p>Crie categorias, modulos, licoes e grupos para organizar o conteudo.</p>
            </header>

            <div class="course-grid">
                <label>
                    <span>Categoria atual</span>
                    <input type="text" name="categoria_nome" placeholder="Ex: Front-end">
                </label>

                <label>
                    <span>Modulo</span>
                    <input type="text" name="modulo" placeholder="Ex: Fundamentos">
                </label>

                <label>
                    <span>Licao</span>
                    <input type="text" name="licao" placeholder="Ex: DOM e Eventos">
                </label>

                <label>
                    <span>Grupo</span>
                    <input type="text" name="grupo" placeholder="Ex: Turma noite">
                </label>

                <div class="course-grid__full course-actions-inline">
                    <button type="button" class="btn btn-secondary">+ Nova categoria</button>
                    <button type="button" class="btn btn-secondary">+ Novo modulo</button>
                    <button type="button" class="btn btn-secondary">+ Nova licao</button>
                    <button type="button" class="btn btn-secondary">+ Novo grupo</button>
                </div>
            </div>
        </section>

        <!-- Matriculas e alunos -->
        <section class="course-panel">
            <header>
                <h2>Matricula e inscricoes</h2>
                <p>Defina regras de acesso e selecione alunos para matricula inicial.</p>
            </header>

            <div class="course-grid">
                <label>
                    <span>Vagas disponiveis</span>
                    <input type="number" name="capacidade" min="1" placeholder="Ex: 40">
                </label>

                <label>
                    <span>Data de inicio</span>
                    <input type="date" name="data_inicio">
                </label>

                <label>
                    <span>Data de termino</span>
                    <input type="date" name="data_fim">
                </label>

                <label class="course-grid__full">
                    <span>Regras de matricula</span>
                    <div class="course-toggle">
                        <label><input type="checkbox" name="matricula_aberta" checked> Matricula aberta</label>
                        <label><input type="checkbox" name="aprovar_manual"> Aprovar manualmente</label>
                        <label><input type="checkbox" name="preview_livre" checked> Preview gratis</label>
                    </div>
                </label>

                <div class="course-grid__full">
                    <span>Alunos disponiveis</span>
                    @if ($students->isEmpty())
                        <p class="course-empty">Nenhum aluno cadastrado ainda.</p>
                    @else
                        <div class="course-student-list">
                            @foreach ($students as $student)
                                <label>
                                    <input type="checkbox" name="alunos[]" value="{{ $student->id }}">
                                    <span>{{ $student->name }} <small>{{ $student->email }}</small></span>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- SEO on-page -->
        <section class="course-panel">
            <header>
                <h2>SEO on-page</h2>
                <p>Melhore a indexacao com meta descricao e palavras-chave.</p>
            </header>

            <div class="course-grid">
                <label>
                    <span>Meta descricao</span>
                    <textarea name="meta_descricao" rows="3" placeholder="Resumo com palavras-chave do curso."></textarea>
                </label>

                <label>
                    <span>Palavras-chave</span>
                    <input type="text" name="keywords" placeholder="Ex: javascript, front-end, LMS">
                </label>

                <label>
                    <span>URL canonica (opcional)</span>
                    <input type="url" name="canonical" placeholder="https://seusite.com/cursos/javascript">
                </label>
            </div>
        </section>

        <div class="course-form__actions">
            <button type="button" class="btn btn-ghost">Salvar rascunho</button>
            <button type="submit" class="btn btn-primary">Publicar curso</button>
        </div>
    </form>
</section>
