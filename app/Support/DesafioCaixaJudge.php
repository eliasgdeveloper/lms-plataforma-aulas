<?php

namespace App\Support;

class DesafioCaixaJudge
{
    public static function expectedOutput(): string
    {
        $lines = [
            '=== RELATORIO DE FECHAMENTO DE CAIXA ===',
            'Total de Lojas Analisadas: 7',
            'Transacoes Validas Processadas: 4',
            'Faturamento Total: 6771.25',
            'Ticket Medio: 1692.81',
            '',
            '--- TOP 3 MAIORES TRANSACOES ---',
            '1. Filial "Sul": 3250.00',
            '2. Loja Centro & Cia: 1500.75',
            '3. Outlet Premium: 1200.00',
            '=========================================',
        ];

        return implode("\n", $lines);
    }

    public static function normalize(string $texto): string
    {
        $texto = str_replace(["\r\n", "\r"], "\n", $texto);
        $texto = trim($texto);
        $linhas = preg_split("/\n/", $texto) ?: [];
        $limpas = [];
        foreach ($linhas as $linha) {
            $limpas[] = trim($linha);
        }

        return implode("\n", $limpas);
    }

    /**
     * @return array{ok:bool,code_ok:bool,output_ok:bool,message:string,hints:array<int,string>,mood:string}
     */
    public static function grade(string $codigo, string $saida, string $nome = 'Aluno'): array
    {
        $nome = $nome !== '' ? $nome : 'Aluno';
        $codigo = trim($codigo);
        $saida = trim($saida);

        if ($codigo === '') {
            return [
                'ok' => false,
                'code_ok' => false,
                'output_ok' => false,
                'mood' => 'nudge',
                'message' => "Oi, {$nome}! 😊 Cola o seu PHP no campo de cima. Sem o código, o juiz não consegue te ajudar.",
                'hints' => ['Cole o script PHP completo (foreach, validações e echo da saída).'],
            ];
        }

        if ($saida === '') {
            return [
                'ok' => false,
                'code_ok' => true,
                'output_ok' => false,
                'mood' => 'nudge',
                'message' => "{$nome}, está quase! ✨ Falta colar, no campo de baixo, o texto que o PHP imprimiu. Sem a saída o juiz não aprova.",
                'hints' => ['Rode o script e copie só o relatório (as linhas do echo), não o código.'],
            ];
        }

        $esperada = self::normalize(self::expectedOutput());
        $enviada = self::normalize($saida);
        $outputOk = $esperada === $enviada;

        $tokens = [
            'explode' => 'Quebrar cada linha em campos com explode (o ; é o recorte).',
            'is_numeric' => 'Validar o valor com is_numeric antes de somar.',
            'APROVADO' => 'Só entra no relatório quem está com status APROVADO.',
            'array_reduce' => 'O faturamento total cai bem com array_reduce.',
            'array_slice' => 'O Top 3 nasce com array_slice depois de ordenar.',
            'number_format' => 'Dinheiro no contrato: number_format com 2 casas e ponto decimal.',
            'PHP_EOL' => 'Quebra de linha portável: PHP_EOL no echo final.',
        ];
        $faltando = [];
        foreach ($tokens as $token => $dica) {
            if (stripos($codigo, $token) === false) {
                $faltando[$token] = $dica;
            }
        }
        $temOrdenacao = stripos($codigo, 'usort') !== false
            || preg_match('/\bsort\s*\(/i', $codigo);
        if (! $temOrdenacao) {
            $faltando['usort'] = 'Ordene as transações (usort) do maior valor para o menor.';
        }

        if ($outputOk) {
            $cheers = [
                "Parabéns, {$nome}! 🎉 A saída bateu com o contrato. Agora responda as perguntas sobre este Desafio/Prova, bom Quiz pra você!",
                "Muito bom, {$nome}! 😄 Relatório aprovado pelo juiz. Agora responda as perguntas sobre este Desafio/Prova, bom Quiz pra você!",
                "Você está indo bem, {$nome}! 🌟 Saída certinha. Agora responda as perguntas sobre este Desafio/Prova, bom Quiz pra você!",
            ];

            return [
                'ok' => true,
                'code_ok' => true,
                'output_ok' => true,
                'mood' => 'pass',
                'message' => $cheers[array_rand($cheers)],
                'hints' => [],
            ];
        }

        $hints = self::diffHints($esperada, $enviada);
        foreach (array_values($faltando) as $dica) {
            $hints[] = $dica;
        }
        $hints = array_values(array_unique(array_slice($hints, 0, 4)));

        $prefix = self::cheerPrefix($nome, count($faltando));

        return [
            'ok' => false,
            'code_ok' => $faltando === [],
            'output_ok' => false,
            'mood' => 'almost',
            'message' => $prefix.' A saída ainda não está idêntica ao contrato. Respira, compara linha a linha 😉',
            'hints' => $hints,
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function diffHints(string $esperada, string $enviada): array
    {
        $exp = explode("\n", $esperada);
        $got = explode("\n", $enviada);
        $hints = [];

        if (count($got) !== count($exp)) {
            $hints[] = 'Está quase lá! 👀 Confere a quantidade de linhas (títulos, totais, linha em branco e Top 3).';
        }

        $checks = [
            'RELATORIO' => 'O título precisa ser exatamente === RELATORIO DE FECHAMENTO DE CAIXA === (sem acento).',
            'Total de Lojas Analisadas: 7' => 'Total de Lojas Analisadas deve ser 7 (todas as linhas úteis, mesmo as inválidas).',
            'Transacoes Validas Processadas: 4' => 'Transações válidas: 4 (APROVADO + valor numérico).',
            'Faturamento Total: 6771.25' => 'Faturamento Total: 6771.25 com ponto decimal e duas casas.',
            'Ticket Medio: 1692.81' => 'Ticket Médio: 1692.81 (total / 4), protegido contra divisão por zero.',
            'Filial "Sul"' => 'No Top 1, mantenha as aspas do nome: Filial "Sul".',
            'Loja Centro & Cia' => 'No Top 2, o & do nome Loja Centro & Cia precisa aparecer.',
            'Outlet Premium' => 'O Top 3 é Outlet Premium: 1200.00.',
        ];
        foreach ($checks as $needle => $hint) {
            if (stripos($enviada, $needle) === false) {
                $hints[] = $hint;
            }
        }

        if ($hints === []) {
            $hints[] = 'Quase lá! ✨ Olha espaços extras, ordem do Top 3 e o number_format (ponto, sem milhar).';
        }

        return $hints;
    }

    private static function cheerPrefix(string $nome, int $missingCount): string
    {
        if ($missingCount <= 1) {
            return "Está quase lá, {$nome}! 💪";
        }
        if ($missingCount <= 3) {
            return "Você está indo bem, {$nome}! 😊";
        }

        return "Calma, {$nome} — o juiz está do seu lado! 🦊";
    }
}
