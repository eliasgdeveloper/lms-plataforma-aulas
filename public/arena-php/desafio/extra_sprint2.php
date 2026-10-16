<?php
/**
 * Desafio Extra da Arena 2 — fechamento do caixa da Padaria e Café Litoral.
 * Contrato numerado. O juiz não devolve a saída pronta.
 */
return array(
    'id' => 'extra',
    'titulo' => 'Desafio Extra · Fechamento do caixa',
    'tema' => 'Caixa Litoral (contrato numerado)',
    'pontos' => 300,
    'surpresa' => 'estrela_extra',
    'enunciado' => <<<'TXT'
CONTRATO DE DESAFIO — Padaria e Café Litoral

Antes de codar, abra o PDF desta aula: "Guia do caixa: count, soma, média, array_reduce, strval e caractere".
O PHP não possui sum(), avg(), toString() nem charAt(). O PDF mostra o nome real de cada um.

Você fecha o sábado das quatro lojas. Use estes arrays, nesta ordem:

$lojas = ['Copacabana', 'Ipanema', 'Leblon', 'Botafogo'];
$codigos = ['cpa', 'ipa', 'leb', 'bot'];
$vendas = [1200, 900, 1500, 900];
$obs = ['forno ok', 'fila no caixa', 'estoque baixo', 'chuva forte'];

ETAPAS (o juiz corrige por estas etapas, sem te entregar a resposta):

1) Primeira linha, exatamente o cabeçalho de abertura do caixa Litoral.
2) Uma linha numerada por loja, com parêntese — não use hífen no lugar do número.
   Título da loja em maiúsculas, primeira letra do código entre colchetes,
   seta, status e o valor.
3) Status pela venda, com if / elseif / else:
   - 1500 ou mais: estrela
   - 1000 ou mais: ok
   - abaixo disso: atencao
4) Linha TOTAL com a soma das vendas (array_sum).
5) Linha MEDIA com a média inteira (array_sum dividido por count). Pode mostrar esse número via strval.
6) Linha REDUCE_MIL com a soma, via array_reduce, só das vendas de 1000 ou mais.
7) Linha OBS com a quantidade de campos (count) e o maior tamanho de texto (strlen).
8) Linha INICIAL FECHAMENTO com a primeira letra da palavra fechamento (posição 0).
9) Última linha: o fechamento do caixa Litoral.

Cole no campo de baixo só o texto que o PHP imprimir. Sem essa saída o juiz não aprova.
TXT,
    'dica' => 'Percorra com foreach e índice. strtoupper, [0], if/elseif/else, array_sum, count, array_reduce, strlen e strval. Numere com ")".',
    'saida_esperada' => "CAIXA LITORAL — ABERTURA\n1) COPACABANA [c] → ok | 1200\n2) IPANEMA [i] → atencao | 900\n3) LEBLON [l] → estrela | 1500\n4) BOTAFOGO [b] → atencao | 900\nTOTAL: 4500\nMEDIA: 1125\nREDUCE_MIL: 2700\nOBS: 4 campos | maior texto: 13\nINICIAL FECHAMENTO: f\nCAIXA LITORAL — FECHO",
    'tokens' => array('foreach', 'array_sum', 'array_reduce', 'count', 'strval', 'strtoupper', 'strlen', 'if', 'echo'),
    'starter' => "<?php\n\$lojas = ['Copacabana', 'Ipanema', 'Leblon', 'Botafogo'];\n\$codigos = ['cpa', 'ipa', 'leb', 'bot'];\n\$vendas = [1200, 900, 1500, 900];\n\$obs = ['forno ok', 'fila no caixa', 'estoque baixo', 'chuva forte'];\n\n",
);
