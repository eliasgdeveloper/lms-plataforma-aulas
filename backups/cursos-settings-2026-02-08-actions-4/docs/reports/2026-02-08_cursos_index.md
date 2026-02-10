# Cursos index - paginas e heranca

Data: 2026-02-08

Resumo
- Paginas de cursos para admin, professor e aluno com cards verticais.
- Componente reutilizavel de card de curso.
- CSS compartilhado para manter padrao visual.
- Rotas integradas e SEO on-page (meta tags).

Arquivos principais
- app/Http/Controllers/CursoController.php
- resources/views/components/course-card.blade.php
- resources/views/pages/aluno_cursos/index.blade.php
- resources/views/pages/professor_cursos/index.blade.php
- resources/views/pages/admin_cursos/index.blade.php
- public/pages/cursos/style.css
- routes/web.php
- routes/admin.php
- resources/views/layouts/page.blade.php

Notas
- Cards incluem matricula, previa gratis e grade.
- Admin/professor possuem botoes de gestao (editar, ocultar, agendar, excluir).
- Layout segue padrao hibrido com heranca de layouts.
- Cards com ID abrem pagina de configuracoes do curso.
