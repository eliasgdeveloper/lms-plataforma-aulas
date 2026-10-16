<?php
/**
 * Desafio Extra — só libera após as 8 missões.
 * Usa somente: arrays, foreach, contador, count, cópia, índice,
 * strtoupper, strtolower e array_map.
 */
return array(
    'id' => 'extra',
    'titulo' => 'Desafio Extra · Relatório do Diretor',
    'tema' => 'Encadeamento das 8 missões',
    'pontos' => 300,
    'surpresa' => 'estrela_extra',
    'enunciado' => <<<'TXT'
CONTEXTO (pedido real de TI)

O diretor da escola pediu ao coordenador um relatório rápido dos projetos digitais da semana.
Você é o desenvolvedor júnior responsável por montar esse relatório em PHP.

Já existem duas listas prontas, com 10 itens cada, alinhadas pela MESMA POSIÇÃO (índice):
- o item [0] de $projetos combina com o item [0] de $status
- o item [1] com o [1], e assim por diante.

$projetos = [
    'Landing Page Empresa Alpha',
    'Portal do Aluno Beta',
    'App de Presenca Gamma',
    'Site Institucional Delta',
    'API de Notas Epsilon',
    'Dashboard Financeiro Zeta',
    'Blog Tech Eta',
    'Loja Virtual Theta',
    'Sistema de Chamados Iota',
    'Intranet Kappa',
];

$status = [
    'em analise',
    'em desenvolvimento',
    'em teste',
    'aguardando cliente',
    'em desenvolvimento',
    'em analise',
    'publicado',
    'em teste',
    'em desenvolvimento',
    'em analise',
];

O QUE VOCÊ DEVE ENTREGAR

1) Cabeçalho exatamente assim:
=== RELATORIO DE PROJETOS TI ===

2) Liste os 10 pares com contador começando em 1, no formato:
1) Nome do Projeto → status
(use o índice para cruzar $projetos[$i] com $status[$i], ou foreach com chave)

3) Mostre o total com count:
TOTAL: 10

4) Faça uma CÓPIA do array $status (ex.: $statusBackup = $status).
   Em seguida, atualize SOMENTE a posição [3] do array $status original para: publicado
   (simula o diretor pedindo: "o Site Institucional Delta já pode ir ao ar")

5) Mostre estas duas linhas, nesta ordem:
BACKUP STATUS [3]: aguardando cliente
STATUS ATUAL [3]: publicado

6) Com array_map + strtoupper, gere a seção de banners (nomes em MAIÚSCULAS):
BANNERS:
>> NOME EM MAIUSCULAS
(um por linha, na ordem original dos projetos)

7) Com array_map + strtolower, gere a seção técnica:
SLUGS:
>> nome em minusculas
(um por linha, na ordem original)

RECOMENDAÇÃO DO PROFESSOR
Prefira encadear foreach, contador, count, cópia, índice e array_map.
Evite copiar 10 echos iguais na mão: em empresa isso vira manutenção difícil.

IMPORTANTE
A saída precisa bater linha a linha com o formato acima.
Não invente projetos extras. Não mude a ordem. Não omita seções.
TXT,
    'dica' => 'Monte o relatório por partes: lista com foreach → count → backup + índice [3] → array_map(strtoupper) → array_map(strtolower).',
    'starter' => "<?php\n\$projetos = [\n    'Landing Page Empresa Alpha',\n    'Portal do Aluno Beta',\n    'App de Presenca Gamma',\n    'Site Institucional Delta',\n    'API de Notas Epsilon',\n    'Dashboard Financeiro Zeta',\n    'Blog Tech Eta',\n    'Loja Virtual Theta',\n    'Sistema de Chamados Iota',\n    'Intranet Kappa',\n];\n\n\$status = [\n    'em analise',\n    'em desenvolvimento',\n    'em teste',\n    'aguardando cliente',\n    'em desenvolvimento',\n    'em analise',\n    'publicado',\n    'em teste',\n    'em desenvolvimento',\n    'em analise',\n];\n\n// monte o relatório pedido pelo diretor\n",
    'saida_esperada' => "=== RELATORIO DE PROJETOS TI ===\n1) Landing Page Empresa Alpha → em analise\n2) Portal do Aluno Beta → em desenvolvimento\n3) App de Presenca Gamma → em teste\n4) Site Institucional Delta → aguardando cliente\n5) API de Notas Epsilon → em desenvolvimento\n6) Dashboard Financeiro Zeta → em analise\n7) Blog Tech Eta → publicado\n8) Loja Virtual Theta → em teste\n9) Sistema de Chamados Iota → em desenvolvimento\n10) Intranet Kappa → em analise\nTOTAL: 10\nBACKUP STATUS [3]: aguardando cliente\nSTATUS ATUAL [3]: publicado\nBANNERS:\n>> LANDING PAGE EMPRESA ALPHA\n>> PORTAL DO ALUNO BETA\n>> APP DE PRESENCA GAMMA\n>> SITE INSTITUCIONAL DELTA\n>> API DE NOTAS EPSILON\n>> DASHBOARD FINANCEIRO ZETA\n>> BLOG TECH ETA\n>> LOJA VIRTUAL THETA\n>> SISTEMA DE CHAMADOS IOTA\n>> INTRANET KAPPA\nSLUGS:\n>> landing page empresa alpha\n>> portal do aluno beta\n>> app de presenca gamma\n>> site institucional delta\n>> api de notas epsilon\n>> dashboard financeiro zeta\n>> blog tech eta\n>> loja virtual theta\n>> sistema de chamados iota\n>> intranet kappa",
    'tokens_sugeridos' => array('foreach', 'count', 'array_map', 'strtoupper', 'strtolower', '[3]'),
);
