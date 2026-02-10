# Aluno curso conteudo - layout e progresso

Data: 2026-02-10

Resumo
- Ajustes no layout do preview para ocupar mais altura e largura, com section esticando via flex.
- Correcao do progresso do video para nao regredir ao reabrir o conteudo.
- Permissoes de download e acoes de abertura para arquivos nao suportados.
- Notificacoes visiveis de salvamento de progresso.

Principais ajustes
- Preview usa altura fluida e flex para preencher a area disponivel.
- Barra de progresso do video so avanca quando o percentual aumenta.
- Acoes de abrir/baixar/Google Sheets para conteudos nao renderizados no leitor.
- Permissao de download respeitada no data-allow-download.

Arquivos principais
- public/pages/aluno_curso_conteudo/style.css
- public/pages/aluno_curso_conteudo/script.js
- resources/views/pages/aluno_cursos/show.blade.php
- app/Http/Controllers/CursoController.php
- tests/Feature/AlunoConteudoProgressTest.php
- database/migrations/2026_02_10_120000_fix_conteudo_progresso_fk_sqlite.php
- database/migrations/2026_02_10_130000_rebuild_conteudo_progresso_sqlite.php

Backup
- backups/aluno-curso-conteudo-2026-02-10-02

Testes
- php artisan test
