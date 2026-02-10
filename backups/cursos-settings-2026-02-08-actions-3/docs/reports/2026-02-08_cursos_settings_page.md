# Cursos settings - pagina de configuracoes

Data: 2026-02-08

Resumo
- Criada pagina de configuracoes por curso (admin/professor).
- Menu superior alinhado aos PDFs (Curso, Configuracoes, Participantes, Notas, Relatorios + Mais).
- Layout com sidebar de conteudos e area principal para atividades.
- Links rapidos de navegacao (pagina inicial, painel, meus cursos, administracao, voltar).
- Acoes do curso (editar, ocultar, iniciar, agendar, excluir) movidas para a pagina de configuracoes.
- Botao Editar abre a pagina de edicao reutilizando o formulario existente.
- Menu superior e sidebar com anchors clicaveis para cada secao.
- Relatorio rapido e Visao geral do admin com paginas dedicadas.

Arquivos principais
- app/Http/Controllers/CursoController.php
- resources/views/pages/cursos/_settings.blade.php
- resources/views/pages/admin_cursos/show.blade.php
- resources/views/pages/professor_cursos/show.blade.php
- public/pages/cursos/settings.css
- routes/admin.php
- routes/web.php

Observacoes
- Cards do admin/professor agora abrem a pagina de configuracoes.
- JSON-LD aplicado nas paginas de configuracao.
- Dropdown do menu inclui: Banco de Questoes, Banco de Conteudo, Conclusao de curso, Filtros, Reutilizar curso.
