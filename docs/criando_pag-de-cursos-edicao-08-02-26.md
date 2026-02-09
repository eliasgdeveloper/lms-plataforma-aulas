# Registro de alteracoes - 08/02/2026

## Contexto
- Ambiente: Windows, Laravel 12, PHP 8.3, SQLite.
- Foco: tela de configuracao de cursos e fluxo de conteudos.

## O que foi feito
- Migracoes pendentes aplicadas para incluir ordem de aulas, visibilidade e progresso de conteudos.
- Acoes rapidas (Enviar video, Adicionar tarefa, Criar quiz) passam a abrir o modulo correto e preparar o formulario.
- Upload recebeu area elegante com badge por tipo de arquivo e feedback visual do arquivo selecionado.
- Painel de edicao de conteudo exibe preview do video quando arquivo ou URL estao disponiveis.
- Mensagens de sucesso e erros de validacao aparecem no topo do painel.
- Preview de PDF e arquivos com opcoes de abrir/baixar no admin.
- Area do aluno agora mostra abrir/baixar para PDF, Word, Excel e arquivos gerais.
- Download removido dos videos e menu de contexto do video bloqueado.
- SQLite ajustado para aceitar novos tipos em `conteudos.tipo` (excel, word, arquivo, quiz, prova, tarefa).

## Observacoes
- Upload continua usando storage publico em `storage/app/public`.
- Se o preview nao carregar o arquivo enviado, confirme o link simbolico de storage com `php artisan storage:link`.
