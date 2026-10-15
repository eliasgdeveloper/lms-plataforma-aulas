<?php
/**
 * Arena 2 — Caixa da Padaria e Café Litoral.
 * Mesma família de código da Arena 1, com outra empresa, outros arrays
 * e etapas numeradas. Funções reais do PHP: count, array_sum, média
 * (array_sum / count), array_reduce, strval e acesso de caractere.
 */
$pdf = 'Nesta aula existe o PDF "Guia do caixa: count, soma, média, array_reduce, strval e caractere". Leia esse PDF antes de validar. O PHP não tem sum(), avg(), toString() nem charAt() com esses nomes.';

return array(
    array(
        'id' => 1,
        'titulo' => 'Missão 1 · Títulos da vitrine',
        'tema' => 'foreach + strtoupper + caractere',
        'pontos' => 100,
        'surpresa' => 'avatar_fox',
        'surpresa_texto' => 'Avatar: Vitrine do Litoral',
        'enunciado' => $pdf . "\n\n"
            . "Sábado na Padaria e Café Litoral. A vitrine pede o nome de cada loja como título (maiúsculas) e a primeira letra do código da loja.\n\n"
            . "Etapa 1. Percorra \$lojas junto com \$codigos, no mesmo índice.\n"
            . "Etapa 2. O nome da loja sai em maiúsculas (strtoupper) — é título de vitrine, não um teste solto de uma palavra.\n"
            . "Etapa 3. Entre colchetes, a primeira letra do código (posição 0 do texto, o equivalente a charAt).\n"
            . "Etapa 4. Uma loja por linha, nesta ordem: Copacabana, Ipanema, Leblon, Botafogo.\n\n"
            . "Saída do contrato (4 linhas):\n"
            . "COPACABANA [c]\nIPANEMA [i]\nLEBLON [l]\nBOTAFOGO [b]",
        'dica' => 'foreach com índice. strtoupper na loja. A primeira letra é $codigos[$i][0].',
        'saida_esperada' => "COPACABANA [c]\nIPANEMA [i]\nLEBLON [l]\nBOTAFOGO [b]",
        'falhas' => array(
            'Etapa 1: a primeira loja não saiu como título em maiúsculas com a inicial do código entre colchetes.',
            'Etapa 2: a linha da Ipanema não fecha com o contrato da vitrine.',
            'Etapa 3: a linha do Leblon não fecha com o contrato da vitrine.',
            'Etapa 4: a linha de Botafogo não fecha com o contrato da vitrine.',
        ),
        'tokens' => array('foreach', 'strtoupper', 'echo'),
        'starter' => "<?php\n\$lojas = ['Copacabana', 'Ipanema', 'Leblon', 'Botafogo'];\n\$codigos = ['cpa', 'ipa', 'leb', 'bot'];\n\n",
    ),
    array(
        'id' => 2,
        'titulo' => 'Missão 2 · Campo de observação',
        'tema' => 'strlen + if + count',
        'pontos' => 110,
        'surpresa' => 'badge_contador',
        'surpresa_texto' => 'Badge: Campo de texto',
        'enunciado' => $pdf . "\n\n"
            . "O gerente pediu para medir o campo de observação de cada loja, não para contar uma palavra solta.\n\n"
            . "Etapa 1. Para cada texto de \$obs, conte os caracteres com strlen.\n"
            . "Etapa 2. Se passar de 10 caracteres, a faixa é LONGO. Senão, CURTO.\n"
            . "Etapa 3. Numere as linhas: número, parêntese, texto original, seta, faixa e o tamanho entre parênteses.\n"
            . "Etapa 4. Na última linha, use count para dizer quantos campos foram lidos.\n\n"
            . "Saída do contrato:\n"
            . "1) forno ok → CURTO (8)\n"
            . "2) fila no caixa → LONGO (13)\n"
            . "3) estoque baixo → LONGO (13)\n"
            . "4) chuva forte → LONGO (11)\n"
            . "Campos lidos: 4",
        'dica' => 'if (strlen($texto) > 10). A última linha usa count($obs).',
        'saida_esperada' => "1) forno ok → CURTO (8)\n2) fila no caixa → LONGO (13)\n3) estoque baixo → LONGO (13)\n4) chuva forte → LONGO (11)\nCampos lidos: 4",
        'falhas' => array(
            'Etapa 1: a observação do forno não está na faixa e no tamanho pedidos.',
            'Etapa 2: a observação da fila do caixa não está na faixa pedida.',
            'Etapa 3: a observação de estoque não está na faixa pedida.',
            'Etapa 4: a observação da chuva não está na faixa pedida.',
            'Etapa 5: a linha final precisa informar quantos campos de texto foram lidos, com count.',
        ),
        'tokens' => array('strlen', 'if', 'count', 'echo'),
        'starter' => "<?php\n\$obs = ['forno ok', 'fila no caixa', 'estoque baixo', 'chuva forte'];\n\n",
    ),
    array(
        'id' => 3,
        'titulo' => 'Missão 3 · Soma do sábado',
        'tema' => 'array_sum + if',
        'pontos' => 120,
        'surpresa' => 'avatar_robot',
        'surpresa_texto' => 'Avatar: Caixa somado',
        'enunciado' => $pdf . "\n\n"
            . "Feche a soma das vendas das quatro lojas. No PHP a soma de array é array_sum (não existe sum()).\n\n"
            . "Etapa 1. Some \$vendas com array_sum.\n"
            . "Etapa 2. A meta do sábado é 4000. Se a soma passar da meta, a faixa é \"acima da meta\". Senão, \"dentro da meta\".\n\n"
            . "Saída do contrato:\n"
            . "Soma das vendas: 4500\n"
            . "Faixa: acima da meta",
        'dica' => 'array_sum($vendas) e um if contra a meta 4000.',
        'saida_esperada' => "Soma das vendas: 4500\nFaixa: acima da meta",
        'falhas' => array(
            'Etapa 1: a linha da soma não fecha com a soma das quatro vendas.',
            'Etapa 2: a faixa da meta (acima ou dentro) não está de acordo com a soma.',
        ),
        'tokens' => array('array_sum', 'if', 'echo'),
        'starter' => "<?php\n\$vendas = [1200, 900, 1500, 900];\n\n",
    ),
    array(
        'id' => 4,
        'titulo' => 'Missão 4 · Média por loja',
        'tema' => 'array_sum / count + if',
        'pontos' => 120,
        'surpresa' => 'badge_indice',
        'surpresa_texto' => 'Badge: Média do turno',
        'enunciado' => $pdf . "\n\n"
            . "A média não tem função avg() no PHP. Ela é a soma (array_sum) dividida pela quantidade (count).\n\n"
            . "Etapa 1. Calcule a média inteira das vendas (a divisão fecha redonda).\n"
            . "Etapa 2. Se a média for de 1000 ou mais, o turno é \"forte\". Senão, \"fraco\".\n\n"
            . "Saída do contrato:\n"
            . "Media por loja: 1125\n"
            . "Turno: forte",
        'dica' => '$media = array_sum($vendas) / count($vendas); depois if.',
        'saida_esperada' => "Media por loja: 1125\nTurno: forte",
        'falhas' => array(
            'Etapa 1: a média por loja não fecha com soma dividida pela quantidade de lojas.',
            'Etapa 2: o turno (forte ou fraco) não condiz com a média.',
        ),
        'tokens' => array('array_sum', 'count', 'if', 'echo'),
        'starter' => "<?php\n\$vendas = [1200, 900, 1500, 900];\n\n",
    ),
    array(
        'id' => 5,
        'titulo' => 'Missão 5 · Faixa de mil',
        'tema' => 'array_reduce + if',
        'pontos' => 140,
        'surpresa' => 'avatar_wizard',
        'surpresa_texto' => 'Avatar: Reduce do caixa',
        'enunciado' => $pdf . "\n\n"
            . "O financeiro quer só as vendas de 1000 ou mais, somadas com array_reduce (é o reduce do PHP).\n\n"
            . "Etapa 1. Use array_reduce para somar apenas os valores que passam de 999.\n"
            . "Etapa 2. Conte, com if, quantas vendas entraram nessa faixa.\n\n"
            . "Saída do contrato:\n"
            . "Vendas de mil ou mais: 2700\n"
            . "Quantidade nessa faixa: 2",
        'dica' => 'No callback do array_reduce, some só quando $venda >= 1000.',
        'saida_esperada' => "Vendas de mil ou mais: 2700\nQuantidade nessa faixa: 2",
        'falhas' => array(
            'Etapa 1: a soma da faixa de mil não fecha com o reduce pedido.',
            'Etapa 2: a quantidade de lojas nessa faixa não fecha.',
        ),
        'tokens' => array('array_reduce', 'if', 'echo'),
        'starter' => "<?php\n\$vendas = [1200, 900, 1500, 900];\n\n",
    ),
    array(
        'id' => 6,
        'titulo' => 'Missão 6 · Total em texto',
        'tema' => 'strval + caractere',
        'pontos' => 110,
        'surpresa' => 'badge_map',
        'surpresa_texto' => 'Badge: Texto do caixa',
        'enunciado' => $pdf . "\n\n"
            . "O sistema antigo do caixa guarda número como texto. No PHP isso é strval (o toString), não um método toString().\n\n"
            . "Etapa 1. Converta a soma das vendas com strval e mostre na linha do total em texto.\n"
            . "Etapa 2. A palavra de controle é \"fechamento\". Mostre só a primeira letra (posição 0).\n\n"
            . "Saída do contrato:\n"
            . "Total em texto: 4500\n"
            . "Inicial da palavra fechamento: f",
        'dica' => 'strval(array_sum($vendas)) e $palavra[0] em $palavra = "fechamento".',
        'saida_esperada' => "Total em texto: 4500\nInicial da palavra fechamento: f",
        'falhas' => array(
            'Etapa 1: o total em texto não está convertido como o contrato pede.',
            'Etapa 2: a inicial da palavra fechamento não está na posição 0.',
        ),
        'tokens' => array('strval', 'echo'),
        'starter' => "<?php\n\$vendas = [1200, 900, 1500, 900];\n\$palavra = 'fechamento';\n\n",
    ),
    array(
        'id' => 7,
        'titulo' => 'Missão 7 · Status de cada loja',
        'tema' => 'if / elseif / else',
        'pontos' => 150,
        'surpresa' => 'avatar_ninja',
        'surpresa_texto' => 'Avatar: Status do sábado',
        'enunciado' => $pdf . "\n\n"
            . "Classifique cada loja pelo valor vendido. Use condicionais de verdade, não um texto fixo.\n\n"
            . "Etapa 1. Se a venda for 1500 ou mais, o status é estrela.\n"
            . "Etapa 2. Senão, se for 1000 ou mais, o status é ok.\n"
            . "Etapa 3. Senão, o status é atencao.\n"
            . "Etapa 4. Numere com parêntese e uma seta, na ordem das lojas.\n\n"
            . "Saída do contrato:\n"
            . "1) Copacabana → ok\n"
            . "2) Ipanema → atencao\n"
            . "3) Leblon → estrela\n"
            . "4) Botafogo → atencao",
        'dica' => 'if ($venda >= 1500) ... elseif ($venda >= 1000) ... else ...',
        'saida_esperada' => "1) Copacabana → ok\n2) Ipanema → atencao\n3) Leblon → estrela\n4) Botafogo → atencao",
        'falhas' => array(
            'Etapa 1: o status da Copacabana não segue as três faixas de venda.',
            'Etapa 2: o status da Ipanema não segue as três faixas de venda.',
            'Etapa 3: o status do Leblon não segue as três faixas de venda.',
            'Etapa 4: o status de Botafogo não segue as três faixas de venda.',
        ),
        'tokens' => array('if', 'else', 'echo'),
        'starter' => "<?php\n\$lojas = ['Copacabana', 'Ipanema', 'Leblon', 'Botafogo'];\n\$vendas = [1200, 900, 1500, 900];\n\n",
    ),
    array(
        'id' => 8,
        'titulo' => 'Missão 8 · Checklist do fechamento',
        'tema' => 'while + count',
        'pontos' => 160,
        'surpresa' => 'trophy',
        'surpresa_texto' => 'Troféu: Fechamento do Litoral',
        'enunciado' => $pdf . "\n\n"
            . "O fechamento da loja segue um checklist real. Percorra com while, não na mão.\n\n"
            . "Etapa 1. Comece \$i em 0 e avance enquanto \$i for menor que count(\$passos).\n"
            . "Etapa 2. Cada linha é o número do passo (começando em 1), ponto e o texto do passo.\n"
            . "Etapa 3. Depois do laço, informe quantos passos o checklist tem.\n\n"
            . "Saída do contrato:\n"
            . "1. Conferir gaveta\n"
            . "2. Somar vendas\n"
            . "3. Arquivar observacoes\n"
            . "4. Enviar ao gerente\n"
            . "Checklist: 4 passos",
        'dica' => 'while ($i < count($passos)) { echo ($i + 1) . ". " . $passos[$i]; $i++; }',
        'saida_esperada' => "1. Conferir gaveta\n2. Somar vendas\n3. Arquivar observacoes\n4. Enviar ao gerente\nChecklist: 4 passos",
        'falhas' => array(
            'Etapa 1: o primeiro passo do checklist não está numerado como o contrato pede.',
            'Etapa 2: o segundo passo do checklist não fecha.',
            'Etapa 3: o terceiro passo do checklist não fecha.',
            'Etapa 4: o quarto passo do checklist não fecha.',
            'Etapa 5: a linha final precisa dizer quantos passos o checklist tem.',
        ),
        'tokens' => array('while', 'count', 'echo'),
        'starter' => "<?php\n\$passos = ['Conferir gaveta', 'Somar vendas', 'Arquivar observacoes', 'Enviar ao gerente'];\n\$i = 0;\n\n",
    ),
);
