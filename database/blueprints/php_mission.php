<?php
/**
 * Course Blueprint expandido — gerado automaticamente.
 * Nao rode o seeder de novo: ele apaga aulas e conteudos e perde o progresso do LMS.
 * No banco vivo, Arena 1 e Arena 2 estao juntas no modulo 05 (aula 38),
 * logo depois de Arrays e strings. A aula 50 (antigo modulo 24) foi removida em 30/09/2026.
 */
return array (
  'curso_id' => 3,
  'titulo' => 'PHP em Missao — Do Zero ao Backend',
  'descricao' => 'Trilha longa e pratica: sintaxe, web, OOP, PDO, seguranca, Composer e duas Arenas gamificadas (arrays + war room de TI). Estimativa 70 a 100 horas no seu ritmo, com progresso, XP e checkpoints.',
  'categoria' => 'Programacao',
  'duracao_horas_min' => 70,
  'duracao_horas_max' => 100,
  'nivel_trilha' => 'trilha_completa',
  'status' => 'ativo',
  'capa_filename' => 'php-em-missao.svg',
  'enroll_student_emails' => 
  array (
    0 => 'aluno@example.com',
    1 => 'leandro@codar.local',
  ),
  'matriz_title' => 'Matriz curricular — PHP em Missao',
  'checklist_title' => 'Checklist detalhado do curso PHP',
  'modulos' => 
  array (
    0 => 
    array (
      'ordem' => 1,
      'nivel' => 'Basico',
      'titulo' => 'Ambiente e primeiro script',
      'descricao' => 'Setup local e echo · missao da trilha PHP.',
      'resumo' => 'Missao 1 — Ambiente e primeiro script

Setup local e echo

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Ambiente e primeiro script',
      'pdf_body' => 'Modulo 1 [Basico]
Ambiente e primeiro script

Setup local e echo

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/getting-started.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Ambiente e primeiro script',
    ),
    1 => 
    array (
      'ordem' => 2,
      'nivel' => 'Basico',
      'titulo' => 'Variaveis, tipos e operadores',
      'descricao' => 'Tipos e operadores · missao da trilha PHP.',
      'resumo' => 'Missao 2 — Variaveis, tipos e operadores

Tipos e operadores

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Variaveis, tipos e operadores',
      'pdf_body' => 'Modulo 2 [Basico]
Variaveis, tipos e operadores

Tipos e operadores

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/language.types.intro.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Variaveis, tipos e operadores',
    ),
    2 => 
    array (
      'ordem' => 3,
      'nivel' => 'Basico',
      'titulo' => 'Condicionais e loops',
      'descricao' => 'if, for, while, foreach · missao da trilha PHP.',
      'resumo' => 'Missao 3 — Condicionais e loops

if, for, while, foreach

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Condicionais e loops',
      'pdf_body' => 'Modulo 3 [Basico]
Condicionais e loops

if, for, while, foreach

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/language.control-structures.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Condicionais e loops',
    ),
    3 => 
    array (
      'ordem' => 4,
      'nivel' => 'Basico',
      'titulo' => 'Arrays e strings',
      'descricao' => 'Listas e texto · missao da trilha PHP.',
      'resumo' => 'Missao 4 — Arrays e strings

Listas e texto

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Arrays e strings',
      'pdf_body' => 'Modulo 4 [Basico]
Arrays e strings

Listas e texto

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/language.types.array.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Arrays e strings',
    ),
    4 => 
    array (
      'ordem' => 5,
      'nivel' => 'Basico',
      'titulo' => 'Funcoes e escopo',
      'descricao' => 'Funcoes reutilizaveis · missao da trilha PHP.',
      'resumo' => 'Missao 5 — Funcoes e escopo

Funcoes reutilizaveis

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Funcoes e escopo',
      'pdf_body' => 'Modulo 5 [Basico]
Funcoes e escopo

Funcoes reutilizaveis

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/functions.user-defined.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Funcoes e escopo',
    ),
    5 => 
    array (
      'ordem' => 6,
      'nivel' => 'Basico',
      'titulo' => 'PHP + HTML e saida',
      'descricao' => 'Escape e templates · missao da trilha PHP.',
      'resumo' => 'Missao 6 — PHP + HTML e saida

Escape e templates

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · PHP + HTML e saida',
      'pdf_body' => 'Modulo 6 [Basico]
PHP + HTML e saida

Escape e templates

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/function.htmlspecialchars.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · PHP + HTML e saida',
    ),
    6 => 
    array (
      'ordem' => 7,
      'nivel' => 'Basico',
      'titulo' => 'Debug e erros basicos',
      'descricao' => 'var_dump e notices · missao da trilha PHP.',
      'resumo' => 'Missao 7 — Debug e erros basicos

var_dump e notices

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Debug e erros basicos',
      'pdf_body' => 'Modulo 7 [Basico]
Debug e erros basicos

var_dump e notices

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/function.var-dump.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Debug e erros basicos',
    ),
    7 => 
    array (
      'ordem' => 8,
      'nivel' => 'Basico',
      'titulo' => 'Incluir arquivos',
      'descricao' => 'include e require · missao da trilha PHP.',
      'resumo' => 'Missao 8 — Incluir arquivos

include e require

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Incluir arquivos',
      'pdf_body' => 'Modulo 8 [Basico]
Incluir arquivos

include e require

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/function.include.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Incluir arquivos',
    ),
    8 => 
    array (
      'ordem' => 9,
      'nivel' => 'Basico',
      'titulo' => 'Arrays associativos na pratica',
      'descricao' => 'Mapas chave-valor · missao da trilha PHP.',
      'resumo' => 'Missao 9 — Arrays associativos na pratica

Mapas chave-valor

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Arrays associativos na pratica',
      'pdf_body' => 'Modulo 9 [Basico]
Arrays associativos na pratica

Mapas chave-valor

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/language.types.array.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Arrays associativos na pratica',
    ),
    9 => 
    array (
      'ordem' => 10,
      'nivel' => 'Basico',
      'titulo' => 'Funcoes de string uteis',
      'descricao' => 'str_replace, substr, strlen · missao da trilha PHP.',
      'resumo' => 'Missao 10 — Funcoes de string uteis

str_replace, substr, strlen

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Funcoes de string uteis',
      'pdf_body' => 'Modulo 10 [Basico]
Funcoes de string uteis

str_replace, substr, strlen

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/ref.strings.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Funcoes de string uteis',
    ),
    10 => 
    array (
      'ordem' => 11,
      'nivel' => 'Basico',
      'titulo' => 'Operadores logicos e match',
      'descricao' => '&& || match · missao da trilha PHP.',
      'resumo' => 'Missao 11 — Operadores logicos e match

&& || match

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Operadores logicos e match',
      'pdf_body' => 'Modulo 11 [Basico]
Operadores logicos e match

&& || match

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/control-structures.match.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Operadores logicos e match',
    ),
    11 => 
    array (
      'ordem' => 12,
      'nivel' => 'Basico',
      'titulo' => 'Checkpoint Basico + Arena 1',
      'descricao' => 'Revisao e missao gamificada · missao da trilha PHP.',
      'resumo' => 'Missao 12 — Checkpoint Basico + Arena 1

Revisao e missao gamificada

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Checkpoint Basico + Arena 1',
      'pdf_body' => 'Modulo 12 [Basico]
Checkpoint Basico + Arena 1

Revisao e missao gamificada

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => '/arena-php/desafio.php',
      'link_label' => 'Entrar na Arena PHP (atividade 1)',
      'image_title' => 'Card visual · Checkpoint Basico + Arena 1',
      'extra' => 
      array (
        'tipo' => 'tarefa',
        'titulo' => 'Abrir Arena PHP (atividade 1)',
        'descricao' => 'Entre com usuario Leandro na Arena e continue de onde parou.',
        'url' => '/arena-php/desafio.php',
      ),
      'quiz' => 
      array (
        'titulo' => 'Quiz · Arena PHP atividade 1',
        'pass_score' => 70,
        'questions' => 
        array (
          0 => 
          array (
            'q' => 'Qual estrutura cria uma lista ordenada de valores em PHP?',
            'options' => 
            array (
              0 => 'array',
              1 => 'class',
              2 => 'cookie',
              3 => 'session',
            ),
            'answer' => 0,
          ),
          1 => 
          array (
            'q' => 'Para percorrer cada item de um array, usamos principalmente:',
            'options' => 
            array (
              0 => 'foreach',
              1 => 'include',
              2 => 'echo apenas',
              3 => 'password_hash',
            ),
            'answer' => 0,
          ),
          2 => 
          array (
            'q' => 'A funcao count() serve para:',
            'options' => 
            array (
              0 => 'contar elementos',
              1 => 'conectar MySQL',
              2 => 'enviar email',
              3 => 'criar PDF',
            ),
            'answer' => 0,
          ),
          3 => 
          array (
            'q' => 'Ao copiar $backup = $servicos e alterar so o original, o backup:',
            'options' => 
            array (
              0 => 'mantem o valor antigo (copia)',
              1 => 'sempre muda junto',
              2 => 'apaga o array',
              3 => 'vira string',
            ),
            'answer' => 0,
          ),
          4 => 
          array (
            'q' => 'strtoupper("php") resulta em:',
            'options' => 
            array (
              0 => 'PHP',
              1 => 'php',
              2 => 'Php',
              3 => 'erro',
            ),
            'answer' => 0,
          ),
        ),
      ),
    ),
    12 => 
    array (
      'ordem' => 13,
      'nivel' => 'Intermediario',
      'titulo' => 'Formularios GET/POST',
      'descricao' => 'Receber dados · missao da trilha PHP.',
      'resumo' => 'Missao 13 — Formularios GET/POST

Receber dados

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Formularios GET/POST',
      'pdf_body' => 'Modulo 13 [Intermediario]
Formularios GET/POST

Receber dados

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/tutorial.forms.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Formularios GET/POST',
    ),
    13 => 
    array (
      'ordem' => 14,
      'nivel' => 'Intermediario',
      'titulo' => 'Validacao e filtros',
      'descricao' => 'filter_var · missao da trilha PHP.',
      'resumo' => 'Missao 14 — Validacao e filtros

filter_var

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Validacao e filtros',
      'pdf_body' => 'Modulo 14 [Intermediario]
Validacao e filtros

filter_var

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/book.filter.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Validacao e filtros',
    ),
    14 => 
    array (
      'ordem' => 15,
      'nivel' => 'Intermediario',
      'titulo' => 'Sessoes e cookies',
      'descricao' => 'Estado entre paginas · missao da trilha PHP.',
      'resumo' => 'Missao 15 — Sessoes e cookies

Estado entre paginas

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Sessoes e cookies',
      'pdf_body' => 'Modulo 15 [Intermediario]
Sessoes e cookies

Estado entre paginas

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/book.session.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Sessoes e cookies',
    ),
    15 => 
    array (
      'ordem' => 16,
      'nivel' => 'Intermediario',
      'titulo' => 'Upload de arquivos',
      'descricao' => '$_FILES seguro · missao da trilha PHP.',
      'resumo' => 'Missao 16 — Upload de arquivos

$_FILES seguro

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Upload de arquivos',
      'pdf_body' => 'Modulo 16 [Intermediario]
Upload de arquivos

$_FILES seguro

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/features.file-upload.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Upload de arquivos',
    ),
    16 => 
    array (
      'ordem' => 17,
      'nivel' => 'Intermediario',
      'titulo' => 'Arquivos e JSON',
      'descricao' => 'Persistencia simples · missao da trilha PHP.',
      'resumo' => 'Missao 17 — Arquivos e JSON

Persistencia simples

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Arquivos e JSON',
      'pdf_body' => 'Modulo 17 [Intermediario]
Arquivos e JSON

Persistencia simples

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/book.json.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Arquivos e JSON',
    ),
    17 => 
    array (
      'ordem' => 18,
      'nivel' => 'Intermediario',
      'titulo' => 'Introducao a OOP',
      'descricao' => 'Classes e metodos · missao da trilha PHP.',
      'resumo' => 'Missao 18 — Introducao a OOP

Classes e metodos

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Introducao a OOP',
      'pdf_body' => 'Modulo 18 [Intermediario]
Introducao a OOP

Classes e metodos

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/language.oop5.basic.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Introducao a OOP',
    ),
    18 => 
    array (
      'ordem' => 19,
      'nivel' => 'Intermediario',
      'titulo' => 'Heranca e interfaces',
      'descricao' => 'Contratos · missao da trilha PHP.',
      'resumo' => 'Missao 19 — Heranca e interfaces

Contratos

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Heranca e interfaces',
      'pdf_body' => 'Modulo 19 [Intermediario]
Heranca e interfaces

Contratos

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/language.oop5.inheritance.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Heranca e interfaces',
    ),
    19 => 
    array (
      'ordem' => 20,
      'nivel' => 'Intermediario',
      'titulo' => 'Excecoes e erros',
      'descricao' => 'try/catch · missao da trilha PHP.',
      'resumo' => 'Missao 20 — Excecoes e erros

try/catch

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Excecoes e erros',
      'pdf_body' => 'Modulo 20 [Intermediario]
Excecoes e erros

try/catch

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/language.exceptions.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Excecoes e erros',
    ),
    20 => 
    array (
      'ordem' => 21,
      'nivel' => 'Intermediario',
      'titulo' => 'MySQL + PDO CRUD',
      'descricao' => 'Prepared statements · missao da trilha PHP.',
      'resumo' => 'Missao 21 — MySQL + PDO CRUD

Prepared statements

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · MySQL + PDO CRUD',
      'pdf_body' => 'Modulo 21 [Intermediario]
MySQL + PDO CRUD

Prepared statements

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/book.pdo.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · MySQL + PDO CRUD',
    ),
    21 => 
    array (
      'ordem' => 22,
      'nivel' => 'Intermediario',
      'titulo' => 'Seguranca web',
      'descricao' => 'XSS SQLi hashing · missao da trilha PHP.',
      'resumo' => 'Missao 22 — Seguranca web

XSS SQLi hashing

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Seguranca web',
      'pdf_body' => 'Modulo 22 [Intermediario]
Seguranca web

XSS SQLi hashing

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.phptherightway.com/#security',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Seguranca web',
    ),
    22 => 
    array (
      'ordem' => 23,
      'nivel' => 'Intermediario',
      'titulo' => 'Mini-projeto notas',
      'descricao' => 'App integrado · missao da trilha PHP.',
      'resumo' => 'Missao 23 — Mini-projeto notas

App integrado

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Mini-projeto notas',
      'pdf_body' => 'Modulo 23 [Intermediario]
Mini-projeto notas

App integrado

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.phptherightway.com/',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Mini-projeto notas',
    ),
    23 => 
    array (
      'ordem' => 24,
      'nivel' => 'Intermediario',
      'titulo' => 'Arena Sprint 2 War Room',
      'descricao' => 'Missao corporativa · missao da trilha PHP.',
      'resumo' => 'Missao 24 — Arena Sprint 2 War Room

Missao corporativa

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Arena Sprint 2 War Room',
      'pdf_body' => 'Modulo 24 [Intermediario]
Arena Sprint 2 War Room

Missao corporativa

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => '/arena-php/desafio_sprint2.php',
      'link_label' => 'Entrar na Arena Sprint 2',
      'image_title' => 'Card visual · Arena Sprint 2 War Room',
      'extra' => 
      array (
        'tipo' => 'tarefa',
        'titulo' => 'Abrir Arena Sprint 2 (War Room)',
        'descricao' => 'Nova atividade: condicionais, loops e cenario de empresa de TI.',
        'url' => '/arena-php/desafio_sprint2.php',
      ),
      'quiz' => 
      array (
        'titulo' => 'Quiz · Sprint 2 War Room',
        'pass_score' => 70,
        'questions' => 
        array (
          0 => 
          array (
            'q' => 'Em um if que detecta a palavra "api" em um texto, uma abordagem valida e:',
            'options' => 
            array (
              0 => 'strpos !== false',
              1 => 'count apenas',
              2 => 'echo sem if',
              3 => 'session_start',
            ),
            'answer' => 0,
          ),
          1 => 
          array (
            'q' => 'Um for e util quando voce precisa de:',
            'options' => 
            array (
              0 => 'indice controlado',
              1 => 'somente HTML',
              2 => 'somente CSS',
              3 => 'deploy automatico',
            ),
            'answer' => 0,
          ),
          2 => 
          array (
            'q' => 'while continua enquanto a condicao for:',
            'options' => 
            array (
              0 => 'verdadeira',
              1 => 'sempre falsa',
              2 => 'nula',
              3 => 'um array vazio fixo',
            ),
            'answer' => 0,
          ),
          3 => 
          array (
            'q' => 'Em war room, marcar severidade critica/alta serve para:',
            'options' => 
            array (
              0 => 'priorizar alerta',
              1 => 'apagar logs',
              2 => 'trocar senha do aluno',
              3 => 'gerar PDF do LMS',
            ),
            'answer' => 0,
          ),
          4 => 
          array (
            'q' => 'array_map + strtolower ajuda a:',
            'options' => 
            array (
              0 => 'normalizar catalogo de servicos',
              1 => 'criar usuario admin',
              2 => 'instalar Composer',
              3 => 'abrir porta 8090',
            ),
            'answer' => 0,
          ),
        ),
      ),
    ),
    24 => 
    array (
      'ordem' => 25,
      'nivel' => 'Avancado',
      'titulo' => 'Composer e PSR-4',
      'descricao' => 'Autoload · missao da trilha PHP.',
      'resumo' => 'Missao 25 — Composer e PSR-4

Autoload

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Composer e PSR-4',
      'pdf_body' => 'Modulo 25 [Avancado]
Composer e PSR-4

Autoload

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://getcomposer.org/doc/01-basic-usage.md',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Composer e PSR-4',
    ),
    25 => 
    array (
      'ordem' => 26,
      'nivel' => 'Avancado',
      'titulo' => 'Namespaces e estrutura',
      'descricao' => 'Organizar projeto · missao da trilha PHP.',
      'resumo' => 'Missao 26 — Namespaces e estrutura

Organizar projeto

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Namespaces e estrutura',
      'pdf_body' => 'Modulo 26 [Avancado]
Namespaces e estrutura

Organizar projeto

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/language.namespaces.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Namespaces e estrutura',
    ),
    26 => 
    array (
      'ordem' => 27,
      'nivel' => 'Avancado',
      'titulo' => 'Router e MVC leve',
      'descricao' => 'Front controller · missao da trilha PHP.',
      'resumo' => 'Missao 27 — Router e MVC leve

Front controller

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Router e MVC leve',
      'pdf_body' => 'Modulo 27 [Avancado]
Router e MVC leve

Front controller

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.phptherightway.com/#code_style_guide',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Router e MVC leve',
    ),
    27 => 
    array (
      'ordem' => 28,
      'nivel' => 'Avancado',
      'titulo' => 'Auth e senhas',
      'descricao' => 'password_hash · missao da trilha PHP.',
      'resumo' => 'Missao 28 — Auth e senhas

password_hash

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Auth e senhas',
      'pdf_body' => 'Modulo 28 [Avancado]
Auth e senhas

password_hash

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/function.password-hash.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Auth e senhas',
    ),
    28 => 
    array (
      'ordem' => 29,
      'nivel' => 'Avancado',
      'titulo' => 'APIs REST JSON',
      'descricao' => 'Endpoints · missao da trilha PHP.',
      'resumo' => 'Missao 29 — APIs REST JSON

Endpoints

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · APIs REST JSON',
      'pdf_body' => 'Modulo 29 [Avancado]
APIs REST JSON

Endpoints

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/manual/pt_BR/function.json-encode.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · APIs REST JSON',
    ),
    29 => 
    array (
      'ordem' => 30,
      'nivel' => 'Avancado',
      'titulo' => 'PHP 8+ e qualidade',
      'descricao' => 'Tipagem moderna · missao da trilha PHP.',
      'resumo' => 'Missao 30 — PHP 8+ e qualidade

Tipagem moderna

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · PHP 8+ e qualidade',
      'pdf_body' => 'Modulo 30 [Avancado]
PHP 8+ e qualidade

Tipagem moderna

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.php.net/releases/8.0/pt_BR.php',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · PHP 8+ e qualidade',
    ),
    30 => 
    array (
      'ordem' => 31,
      'nivel' => 'Avancado',
      'titulo' => 'Testes manuais e logs',
      'descricao' => 'Habitos de QA · missao da trilha PHP.',
      'resumo' => 'Missao 31 — Testes manuais e logs

Habitos de QA

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Testes manuais e logs',
      'pdf_body' => 'Modulo 31 [Avancado]
Testes manuais e logs

Habitos de QA

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://www.phptherightway.com/',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Testes manuais e logs',
    ),
    31 => 
    array (
      'ordem' => 32,
      'nivel' => 'Avancado',
      'titulo' => 'Capstone API autenticada',
      'descricao' => 'Projeto final · missao da trilha PHP.',
      'resumo' => 'Missao 32 — Capstone API autenticada

Projeto final

Objetivo: concluir o material, o PDF e o link. Avance a barra de progresso e colecione XP no LMS.
Dica de gameficacao: marque cada item ao terminar e celebre o checkpoint do nivel.',
      'pdf_title' => 'Guia · Capstone API autenticada',
      'pdf_body' => 'Modulo 32 [Avancado]
Capstone API autenticada

Projeto final

Checklist rapido:
[ ] Ler resumo
[ ] Estudar PDF
[ ] Abrir referencia
[ ] Fazer exercicio/quiz se houver
[ ] Atualizar progresso no LMS

Estimativa deste topico: 2 a 4 horas.',
      'link_url' => 'https://laravel.com/docs',
      'link_label' => 'Referencia oficial',
      'image_title' => 'Card visual · Capstone API autenticada',
    ),
  ),
);
