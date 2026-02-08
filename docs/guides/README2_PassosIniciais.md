🔎 Documentação atual (resumo até aqui) - 06-02-26  -  20:44
Estrutura de Navegação
Aluno

Dashboard → /aluno

Minhas Aulas → /aluno/aulas

Materiais → /aluno/materiais

Notas → /aluno/notas

Professor

Dashboard → /professor

Criar Aula → /professor/aulas

Postar Material → /professor/materiais

Acompanhar Alunos → /professor/alunos

Admin

Dashboard → /admin

Gerenciar Usuários → /admin/usuarios

Gerenciar Cursos → /admin/cursos

Configurações → /admin/configuracoes

Identidade Visual (cores sugeridas)
Aluno → Azul (confiança, aprendizado)

Professor → Roxo/Indigo (sabedoria, autoridade)

Admin → Vermelho (controle, gestão)

Neutros → Cinza claro, branco, preto suave

Banco de Dados (modelo inicial) - sUGESTÃO 
Usuários (papéis: aluno, professor, admin)

Cursos

Aulas

Materiais

Notas / Progresso

Inscrições (ligando aluno ↔ curso)


---------------------------------
create_users_table.php - migration
# Estrutura de Cursos e Lições

## Fluxo do Aluno
- Dashboard → lista de cursos inscritos
- Curso → lista de lições
- Lição → conteúdos (vídeos, tarefas, teoria)

## Banco de Dados
### Tabelas
- usuarios (id, nome, email, senha, papel)
- cursos (id, título, descrição, professor_id)
- inscricoes (id, aluno_id, curso_id, data_inscricao)
- licoes (id, curso_id, título, descrição, ordem)
- conteudos (id, lição_id, tipo, título, descrição, duração/xp, link)
- progresso (id, aluno_id, lição_id, status, nota/xp)

### Relacionamentos
- Curso ↔ Professor
- Curso ↔ Lições
- Lição ↔ Conteúdos
- Aluno ↔ Cursos (Inscrições)
- Aluno ↔ Lições (Progresso)


📌 Diagrama ER (em texto)
# Diagrama ER - LMS

## Entidades
- Usuários (aluno, professor, admin)
- Cursos
- Lições
- Conteúdos (vídeo, tarefa, teoria)
- Inscrições (aluno ↔ curso)
- Progresso (aluno ↔ lição)

## Relacionamentos
- Usuário (professor) 1:N Cursos
- Curso 1:N Lições
- Lição 1:N Conteúdos
- Usuário (aluno) N:N Cursos via Inscrições
- Usuário (aluno) N:N Lições via Progresso
---------
## Migration: create_users_table.php
Localização: database/migrations/0001_01_01_000000_create_users_table.php

### Tabelas criadas
- users
  - id → chave primária
  - name → nome do usuário
  - email → único
  - email_verified_at → data de verificação
  - password → senha criptografada
  - role → enum (aluno, professor, admin), default 'aluno'
  - remember_token → token de sessão
  - timestamps → created_at e updated_at

- password_reset_tokens
  - email → chave primária
  - token → token de redefinição
  - created_at → data de criação

- sessions
  - id → chave primária
  - user_id → FK para users
  - ip_address → IP do usuário
  - user_agent → navegador/dispositivo
  - payload → dados da sessão
  - last_activity → última atividade registrada

**Descrição:**  
Migration responsável por criar a estrutura inicial de autenticação e autorização do sistema.

---


## Migration: create_cursos_table.php
Localização: database/migrations/2026_02_07_003751_create_cursos_table.php

### Tabela criada
- cursos
  - id → chave primária
  - titulo → título do curso
  - descricao → descrição detalhada (opcional)
  - categoria → área ou categoria do curso (opcional)
  - professor_id → chave estrangeira para users (professor responsável)
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por criar a tabela de cursos, vinculando cada curso a um professor cadastrado na tabela `users`.
------------

## Migration: create_aulas_table.php
Localização: database/migrations/2026_02_07_004019_create_aulas_table.php

### Tabela criada
- aulas
  - id → chave primária
  - titulo → título da aula
  - descricao → descrição detalhada (opcional)
  - data → data da aula (opcional)
  - curso_id → chave estrangeira para cursos (cada aula pertence a um curso)
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por criar a tabela de aulas, vinculando cada aula a um curso existente na tabela `cursos`.
----

## Migration: create_matriculas_table.php
Localização: database/migrations/2026_02_07_004221_create_matriculas_table.php

### Tabela criada
- matriculas
  - id → chave primária
  - aluno_id → chave estrangeira para users (apenas alunos)
  - curso_id → chave estrangeira para cursos
  - data_matricula → data em que o aluno se matriculou
  - status → enum (ativo, concluido, cancelado), default 'ativo'
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por registrar as matrículas dos alunos nos cursos, permitindo controlar quem está ativo, quem concluiu e quem cancelou.
----
## Migration: create_notas_table.php
Localização: database/migrations/2026_02_07_004715_create_notas_table.php

### Tabela criada
- notas
  - id → chave primária
  - aluno_id → chave estrangeira para users (aluno avaliado)
  - curso_id → chave estrangeira para cursos
  - aula_id → chave estrangeira para aulas (opcional)
  - valor → nota numérica (decimal, ex.: 8.50)
  - observacao → comentários do professor (opcional)
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por registrar o desempenho dos alunos em cursos e aulas, permitindo controle de notas e feedback dos professores.

----
## Migration: create_presencas_table.php
Localização: database/migrations/YYYY_MM_DD_HHMMSS_create_presencas_table.php

### Tabela criada
- presencas
  - id → chave primária
  - aluno_id → chave estrangeira para users (aluno avaliado)
  - aula_id → chave estrangeira para aulas
  - status → enum (presente, ausente, atrasado), default 'presente'
  - observacao → comentários adicionais (opcional)
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por registrar a frequência dos alunos nas aulas, permitindo controle de presença, ausência e atrasos.

----

## Migration: create_conteudos_table.php
Localização: database/migrations/YYYY_MM_DD_HHMMSS_create_conteudos_table.php

### Tabela criada
- conteudos
  - id → chave primária
  - aula_id → chave estrangeira para aulas
  - tipo → enum (video, pdf, link, texto)
  - titulo → título do conteúdo
  - descricao → descrição opcional
  - url → link ou caminho do material (opcional)
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por registrar os materiais e recursos das aulas, permitindo associar vídeos, PDFs, links e textos a cada aula.

---
## Migration: create_conteudos_table.php
Localização: database/migrations/2026_02_07_XXXXXX_create_conteudos_table.php

### Tabela criada
- conteudos
  - id → chave primária
  - aula_id → chave estrangeira para aulas
  - tipo → enum (video, pdf, link, texto)
  - titulo → título do conteúdo
  - descricao → descrição opcional
  - url → link ou caminho do material (opcional)
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por registrar os materiais e recursos das aulas, permitindo associar vídeos, PDFs, links e textos a cada aula.

---
## Migration: create_comentarios_table.php
Localização: database/migrations/YYYY_MM_DD_HHMMSS_create_comentarios_table.php

### Tabela criada
- comentarios
  - id → chave primária
  - user_id → chave estrangeira para users (autor do comentário)
  - aula_id → chave estrangeira para aulas
  - conteudo → texto do comentário
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por registrar comentários e feedback de alunos e professores nas aulas, permitindo interação e troca de ideias dentro da plataforma.


--

## Migration: create_mensagens_table.php
Localização: database/migrations/YYYY_MM_DD_HHMMSS_create_mensagens_table.php

### Tabela criada
- mensagens
  - id → chave primária
  - remetente_id → chave estrangeira para users (quem enviou)
  - destinatario_id → chave estrangeira para users (quem recebeu)
  - conteudo → texto da mensagem
  - lida → boolean (true/false), default false
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por registrar mensagens privadas entre usuários, permitindo comunicação direta entre alunos e professores dentro da plataforma.

--
## Migration: create_notificacoes_table.php
Localização: database/migrations/YYYY_MM_DD_HHMMSS_create_notificacoes_table.php

### Tabela criada
- notificacoes
  - id → chave primária
  - user_id → chave estrangeira para users (quem recebe)
  - titulo → título curto da notificação
  - mensagem → texto completo da notificação
  - tipo → enum (sistema, curso, aula, mensagem), default 'sistema'
  - lida → boolean (true/false), default false
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por registrar notificações enviadas aos usuários, permitindo avisos automáticos ou manuais sobre cursos, aulas, mensagens e eventos do sistema.


--

## Migration: create_categorias_table.php
- categorias
  - id → chave primária
  - nome → nome da categoria
  - descricao → descrição opcional
  - timestamps → created_at e updated_at

## Migration: create_categoria_curso_table.php
- categoria_curso
  - id → chave primária
  - curso_id → chave estrangeira para cursos
  - categoria_id → chave estrangeira para categorias
  - timestamps → created_at e updated_at

**Descrição:**  
Essas migrations permitem organizar cursos em categorias/tags, facilitando a navegação e a filtragem de conteúdos.


---

## Migration: create_certificados_table.php
Localização: database/migrations/YYYY_MM_DD_HHMMSS_create_certificados_table.php

### Tabela criada
- certificados
  - id → chave primária
  - aluno_id → chave estrangeira para users (quem recebe)
  - curso_id → chave estrangeira para cursos
  - codigo → código único do certificado
  - data_emissao → data de emissão
  - valido_ate → data de validade (opcional)
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por registrar certificados emitidos para alunos que concluíram cursos, garantindo autenticidade e rastreabilidade por meio de um código único.

---

## Migration: create_modulos_table.php
Localização: database/migrations/YYYY_MM_DD_HHMMSS_create_modulos_table.php

### Tabela criada
- modulos
  - id → chave primária
  - curso_id → chave estrangeira para cursos
  - titulo → título do módulo
  - descricao → descrição opcional
  - ordem → número inteiro para ordenar os módulos
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por organizar cursos em módulos, permitindo dividir o conteúdo em partes menores e mais estruturadas.

--
## Migration: create_tarefas_table.php
Localização: database/migrations/YYYY_MM_DD_HHMMSS_create_tarefas_table.php

### Tabela criada
- tarefas
  - id → chave primária
  - aula_id → chave estrangeira para aulas
  - titulo → título da tarefa
  - descricao → descrição detalhada (opcional)
  - tipo → enum (exercicio, trabalho, quiz, projeto)
  - data_entrega → prazo final (opcional)
  - pontuacao_maxima → valor máximo de pontos
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por registrar atividades vinculadas às aulas, permitindo controle de exercícios, trabalhos, quizzes e projetos com prazo e pontuação máxima.

--
## Migration: create_entregas_table.php
Localização: database/migrations/YYYY_MM_DD_HHMMSS_create_entregas_table.php

### Tabela criada
- entregas
  - id → chave primária
  - tarefa_id → chave estrangeira para tarefas
  - aluno_id → chave estrangeira para users
  - conteudo → texto ou link enviado (opcional)
  - arquivo → caminho do arquivo anexado (opcional)
  - nota → pontuação recebida (opcional)
  - feedback → comentário do professor (opcional)
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por registrar as submissões de tarefas pelos alunos, incluindo conteúdo enviado, arquivos anexados, nota atribuída e feedback do professor.

--
## Migration: create_pagamentos_table.php
Localização: database/migrations/YYYY_MM_DD_HHMMSS_create_pagamentos_table.php

### Tabela criada
- pagamentos
  - id → chave primária
  - aluno_id → chave estrangeira para users (quem paga)
  - curso_id → chave estrangeira para cursos
  - valor → valor do pagamento
  - data_pagamento → data em que foi realizado
  - metodo → enum (cartao, boleto, pix, transferencia)
  - status → enum (pendente, pago, cancelado), default 'pendente'
  - referencia → código de referência da transação (opcional)
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por registrar mensalidades e pagamentos de cursos, incluindo valor, método, status e referência da transação.

--
## Migration: create_foruns_table.php
- foruns
  - id → chave primária
  - curso_id → chave estrangeira para cursos
  - user_id → chave estrangeira para users (autor do tópico)
  - titulo → título da discussão
  - descricao → descrição inicial
  - timestamps → created_at e updated_at

## Migration: create_respostas_forum_table.php
- respostas_forum
  - id → chave primária
  - forum_id → chave estrangeira para foruns
  - user_id → chave estrangeira para users (autor da resposta)
  - conteudo → mensagem da resposta
  - timestamps → created_at e updated_at

**Descrição:**  
Essas migrations permitem criar fóruns de discussão vinculados a cursos, onde alunos e professores podem abrir tópicos e responder dentro deles.

--
## Migration: create_avisos_table.php
Localização: database/migrations/YYYY_MM_DD_HHMMSS_create_avisos_table.php

### Tabela criada
- avisos
  - id → chave primária
  - curso_id → chave estrangeira para cursos
  - user_id → chave estrangeira para users (autor do aviso)
  - titulo → título curto do aviso
  - mensagem → texto completo
  - data_publicacao → data de publicação
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por registrar avisos e comunicados dos professores nos cursos, funcionando como um mural de informações para os alunos.

--
## Migration: create_eventos_table.php
Localização: database/migrations/YYYY_MM_DD_HHMMSS_create_eventos_table.php

### Tabela criada
- eventos
  - id → chave primária
  - titulo → título do evento
  - descricao → descrição detalhada (opcional)
  - data_inicio → data e hora de início
  - data_fim → data e hora de término (opcional)
  - local → local do evento (opcional)
  - tipo → enum (institucional, curso, aula, feriado), default 'institucional'
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por registrar eventos da agenda escolar e institucionais, permitindo organizar palestras, semanas acadêmicas, feriados e reuniões dentro da plataforma.

--
## Migration: create_biblioteca_table.php
Localização: database/migrations/YYYY_MM_DD_HHMMSS_create_biblioteca_table.php

### Tabela criada
- biblioteca
  - id → chave primária
  - titulo → título do recurso
  - autor → autor ou responsável (opcional)
  - tipo → enum (livro, artigo, apostila, material_digital)
  - descricao → descrição detalhada (opcional)
  - arquivo → caminho do arquivo armazenado (opcional)
  - url → link externo (opcional)
  - curso_id → chave estrangeira para cursos (opcional)
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por registrar recursos acadêmicos extras, como livros, apostilas, artigos e materiais digitais, vinculados ou não a cursos específicos.

--
## Migration: create_perfis_table.php
Localização: database/migrations/YYYY_MM_DD_HHMMSS_create_perfis_table.php

### Tabela criada
- perfis
  - id → chave primária
  - user_id → chave estrangeira para users
  - bio → biografia (opcional)
  - foto → caminho da foto de perfil (opcional)
  - especializacao → área de especialização (opcional)
  - rede_social → link para redes sociais (opcional)
  - telefone → contato telefônico (opcional)
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por registrar informações adicionais dos usuários, permitindo perfis mais completos com biografia, foto, especializações e contatos.

--
## Migration: create_turmas_table.php
Localização: database/migrations/YYYY_MM_DD_HHMMSS_create_turmas_table.php

### Tabela criada
- turmas
  - id → chave primária
  - curso_id → chave estrangeira para cursos
  - professor_id → chave estrangeira para users (professor responsável)
  - nome → nome da turma
  - descricao → descrição opcional
  - data_inicio → data de início
  - data_fim → data de término (opcional)
  - horario → dias/horários (opcional)
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por organizar grupos de alunos em turmas, vinculadas a cursos e professores, com datas e horários definidos.

--

## Migration: create_aulas_table.php
Localização: database/migrations/YYYY_MM_DD_HHMMSS_create_aulas_table.php

### Tabela criada
- aulas
  - id → chave primária
  - curso_id → chave estrangeira para cursos
  - titulo → título da aula
  - descricao → descrição da aula
  - data → data da aula
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por criar a tabela de aulas, vinculadas a cursos e contendo informações básicas como título, descrição e data.

---

## Migration: create_conteudos_table.php
Localização: database/migrations/YYYY_MM_DD_HHMMSS_create_conteudos_table.php

### Tabela criada
- conteudos
  - id → chave primária
  - aula_id → chave estrangeira para aulas
  - titulo → título do conteúdo
  - descricao → descrição do conteúdo
  - tipo → tipo do conteúdo (vídeo, pdf, link, etc.)
  - arquivo → caminho do arquivo (se aplicável)
  - url → link externo (se aplicável)
  - timestamps → created_at e updated_at

**Descrição:**  
Migration responsável por criar a tabela de conteúdos, vinculados a uma aula específica.

---

## Model: Aula.php
Localização: app/Models/Aula.php

### Relacionamentos
- hasMany(Conteudo::class) → uma aula possui vários conteúdos.

**Descrição:**  
Model que representa a entidade Aula, permitindo acessar seus conteúdos relacionados.

---

## Model: Conteudo.php
Localização: app/Models/Conteudo.php

### Relacionamentos
- belongsTo(Aula::class) → um conteúdo pertence a uma aula.

**Descrição:**  
Model que representa a entidade Conteúdo, vinculado diretamente a uma aula.

---

## Controller: ConteudoController.php
Localização: app/Http/Controllers/ConteudoController.php

### Métodos
- index($aulaId) → lista os conteúdos de uma aula e retorna a view aluno.conteudos com o primeiro conteúdo selecionado.
- show($conteudoId) → exibe um conteúdo específico e retorna a mesma view com o conteúdo selecionado.

**Descrição:**  
Controller responsável por gerenciar a exibição dos conteúdos de uma aula.

---

## Rotas: web.php
Localização: routes/web.php

### Rotas criadas
- /aluno/aulas/{aula}/conteudos → chama ConteudoController@index
- /aluno/conteudos/{conteudo} → chama ConteudoController@show

**Descrição:**  
Rotas protegidas por autenticação e role aluno, permitindo que apenas alunos acessem os conteúdos.

---

## Seeder: AulaSeeder.php
Localização: database/seeders/AulaSeeder.php

### Dados criados
- Aula: “Tipos de Referência de Célula”
- Conteúdos:
  - Vídeo introdutório
  - PDF com apostila
  - Link para exercício online

**Descrição:**  
Seeder responsável por popular a tabela de aulas e conteúdos com dados de exemplo para testes.

---

## Teste no Navegador
- Subir servidor: php artisan serve
- Acessar: http://localhost:8000/aluno/aulas/1/conteudos

**Descrição:**  
Exibe a tela aluno/conteudos.blade.php com sidebar listando os conteúdos e área principal mostrando o conteúdo selecionado.


