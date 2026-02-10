# Cursos create - pagina de criacao

Data: 2026-02-08

Resumo
- Criada pagina de criacao de cursos para admin e professor.
- Formulario completo com SEO, identidade visual, estrutura e matriculas.
- CSS exclusivo para o fluxo de criacao, mantendo o estilo dos cards.
- Ajustado POST da criacao para evitar erro 405.

Arquivos principais
- app/Http/Controllers/CursoController.php
- resources/views/pages/cursos/_form.blade.php
- resources/views/pages/admin_cursos/create.blade.php
- resources/views/pages/professor_cursos/create.blade.php
- public/pages/cursos/create.css
- routes/web.php
- routes/admin.php

Observacoes
- Lista alunos nao matriculados: usa todos alunos (curso novo).
- Botoes de criar categoria/modulo/licao/grupo prontos para integrar CRUD futuro.
- SEO on-page aplicado (title, meta description, keywords, canonical, og tags).
- Rota POST criada para salvar cursos (admin/professor).
- Cards do admin/professor agora abrem configuracoes do curso quando ha ID.
