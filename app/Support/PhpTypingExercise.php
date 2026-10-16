<?php

namespace App\Support;

use Illuminate\Support\Str;

class PhpTypingExercise
{
    private const EXAMPLES = [
        'ambiente e primeiro script' => "<?php\n\$nome = 'Ana';\necho \"Ola, \$nome!\";",
        'variaveis, tipos e operadores' => "<?php\n\$preco = 29.90;\n\$quantidade = 2;\n\$total = \$preco * \$quantidade;\necho \$total;",
        'condicionais e loops' => "<?php\n\$notas = [8, 6, 10];\nforeach (\$notas as \$nota) {\n    if (\$nota >= 7) echo \"Aprovado\\n\";\n}",
        'arrays e strings' => "<?php\n\$linguagens = ['PHP', 'SQL'];\necho implode(', ', \$linguagens);",
        'funcoes e escopo' => "<?php\nfunction dobro(int \$numero): int\n{\n    return \$numero * 2;\n}\necho dobro(5);",
        'php + html e saida' => "<?php\n\$nome = '<Ana>';\necho htmlspecialchars(\$nome, ENT_QUOTES, 'UTF-8');",
        'debug e erros basicos' => "<?php\n\$usuario = ['nome' => 'Ana'];\nvar_dump(\$usuario);\necho \$usuario['email'] ?? 'Sem email';",
        'incluir arquivos' => "<?php\nrequire_once __DIR__ . '/config.php';\necho \$config['app_name'];",
        'arrays associativos na pratica' => "<?php\n\$aluno = ['nome' => 'Ana', 'nota' => 9];\necho \$aluno['nome'] . ': ' . \$aluno['nota'];",
        'funcoes de string uteis' => "<?php\n\$frase = 'Aprender PHP';\necho strtoupper(substr(\$frase, 9));",
        'operadores logicos e match' => "<?php\n\$nota = 8;\n\$resultado = match (true) {\n    \$nota >= 7 => 'Aprovado',\n    default => 'Revisar',\n};",
        'checkpoint basico + arena 1' => "<?php\n\$servicos = ['web', 'banco'];\nforeach (\$servicos as \$servico) {\n    echo strtoupper(\$servico) . PHP_EOL;\n}",
        'formularios get/post' => "<?php\n\$nome = trim(\$_POST['nome'] ?? '');\nif (\$nome !== '') echo \"Ola, \" . htmlspecialchars(\$nome);",
        'validacao e filtros' => "<?php\n\$email = filter_var(\$_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);\nif (! \$email) echo 'Email invalido';",
        'sessoes e cookies' => "<?php\nsession_start();\n\$_SESSION['usuario'] = 'Ana';\necho \$_SESSION['usuario'];",
        'upload de arquivos' => "<?php\nif (\$_FILES['foto']['error'] === UPLOAD_ERR_OK) {\n    move_uploaded_file(\$_FILES['foto']['tmp_name'], __DIR__ . '/uploads/foto.jpg');\n}",
        'arquivos e json' => "<?php\n\$dados = ['status' => 'ok'];\nfile_put_contents('dados.json', json_encode(\$dados));",
        'introducao a oop' => "<?php\nclass Produto\n{\n    public function __construct(public string \$nome) {}\n}\necho (new Produto('Livro'))->nome;",
        'heranca e interfaces' => "<?php\ninterface Notificavel { public function enviar(): string; }\nclass Email implements Notificavel\n{\n    public function enviar(): string { return 'Enviado'; }\n}",
        'excecoes e erros' => "<?php\ntry {\n    if (! file_exists('dados.json')) throw new RuntimeException('Arquivo ausente');\n} catch (RuntimeException \$erro) {\n    echo \$erro->getMessage();\n}",
        'mysql + pdo crud' => "<?php\n\$pdo = new PDO(\$dsn, \$usuario, \$senha);\n\$consulta = \$pdo->prepare('SELECT * FROM alunos WHERE id = :id');\n\$consulta->execute(['id' => 1]);",
        'seguranca web' => "<?php\n\$hash = password_hash(\$senha, PASSWORD_DEFAULT);\nif (password_verify(\$senhaDigitada, \$hash)) {\n    echo 'Acesso autorizado';\n}",
        'mini-projeto notas' => "<?php\n\$notas = [8, 7, 9];\n\$media = array_sum(\$notas) / count(\$notas);\necho \$media >= 7 ? 'Aprovado' : 'Revisar';",
        'arena sprint 2 war room' => "<?php\n\$alertas = ['web' => true, 'banco' => false];\nforeach (\$alertas as \$servico => \$ativo) {\n    if (\$ativo) echo \"Verificar \$servico\\n\";\n}",
        'composer e psr-4' => "<?php\n// composer.json associa App\\\\ a app/\nnamespace App\\Services;\n\nclass RelatorioService\n{\n    public function gerar(): string { return 'Relatorio'; }\n}",
        'namespaces e estrutura' => "<?php\nnamespace App\\Models;\n\nclass Aluno\n{\n    public function nome(): string { return 'Ana'; }\n}",
        'router e mvc leve' => "<?php\n\$rota = parse_url(\$_SERVER['REQUEST_URI'], PHP_URL_PATH);\nif (\$rota === '/alunos') {\n    require __DIR__ . '/../views/alunos.php';\n}",
        'auth e senhas' => "<?php\n\$hash = password_hash(\$senha, PASSWORD_DEFAULT);\nif (password_verify(\$senhaInformada, \$hash)) {\n    echo 'Login valido';\n}",
        'apis rest json' => "<?php\nheader('Content-Type: application/json');\necho json_encode(['status' => 'ok'], JSON_UNESCAPED_UNICODE);",
        'php 8+ e qualidade' => "<?php\n\$saudacao = fn (string \$nome): string => \"Ola, \$nome\";\necho \$saudacao('Ana');",
        'testes manuais e logs' => "<?php\nerror_log('Iniciando processamento');\n\$total = 2 + 3;\nif (\$total !== 5) throw new RuntimeException('Total inesperado');",
        'capstone api autenticada' => "<?php\nif (! password_verify(\$senha, \$usuario['senha_hash'])) {\n    http_response_code(401);\n    echo json_encode(['erro' => 'Nao autorizado']);\n}",
    ];

    public static function forSummary(
        ?string $courseTitle,
        string $lessonTitle,
        string $contentTitle,
        string $contentType
    ): ?array {
        if (
            $contentType !== 'texto'
            || ! Str::startsWith(Str::lower($contentTitle), 'resumo')
            || ! Str::contains(Str::lower(Str::ascii($courseTitle ?? '')), 'php')
        ) {
            return null;
        }

        return self::forLesson($lessonTitle);
    }

    public static function forLesson(string $lessonTitle): array
    {
        $title = Str::ascii(Str::lower($lessonTitle));
        $title = preg_replace('/^\\s*\\d+\\s*[·.-]\\s*\\[[^\\]]+\\]\\s*/u', '', $title) ?? $title;

        foreach (self::EXAMPLES as $topic => $code) {
            if (str_contains($title, $topic)) {
                return [
                    'topic' => $topic,
                    'code' => self::formatCode($code),
                ];
            }
        }

        return [
            'topic' => trim($lessonTitle),
            'code' => self::formatCode("<?php\n\$tema = " . var_export(trim($lessonTitle), true) . ";\necho 'Praticando: ' . \$tema;"),
        ];
    }

    private static function formatCode(string $code): string
    {
        $code = trim($code);

        if (! str_starts_with($code, '<?php')) {
            $code = "<?php\n" . $code;
        }

        if (! str_ends_with($code, '?>')) {
            $code .= "\n?>";
        }

        return $code;
    }
}
