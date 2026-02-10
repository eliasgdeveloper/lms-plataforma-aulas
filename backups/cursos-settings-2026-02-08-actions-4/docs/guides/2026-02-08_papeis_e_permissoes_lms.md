# Papeis e permissoes LMS

Data: 2026-02-08

Camadas

1. Entidades
- Usuario
- Curso
- Aula
- Modulo
- Avaliacao
- Certificado
- Plano
- Permissao
- Relatorio

2. Papeis
- Admin master (total)
- Admin (gestao operacional)
- Professor/Instrutor (cria e organiza conteudo)
- Aluno (consome conteudo)
- Empresa/Cliente (visao corporativa)

3. Acoes por papel (resumo)
- Admin master: tudo, inclusive definir permissoes e politicas
- Admin: criar/editar cursos, publicar, gerenciar matriculas, moderar conteudos
- Professor: criar cursos e aulas proprias, gerenciar conteudos e alunos do curso
- Aluno: visualizar e consumir aulas, responder atividades, editar perfil basico
- Empresa: visualizar progresso dos funcionarios, relatorios agregados

4. Fluxos principais
- Criar curso → Criar modulos → Criar aulas → Publicar → Matricular → Consumir → Avaliar → Certificar

Regras de perfil do aluno
- Pode editar dados basicos do perfil
- Nao pode editar nome completo, email, CPF (dados obrigatorios do sistema)

Observacoes
- Admin deve ter painel de controle para aprovar ou negar permissoes especificas.
- Sugestao: adicionar matriz de permissoes por papel e controles de auditoria.
