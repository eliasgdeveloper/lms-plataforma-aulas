<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArenaSsoController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user, 403);

        $target = (string) $request->query('to', '/arena-php/desafio.php');
        if (! str_starts_with($target, '/arena-php/')) {
            $target = '/arena-php/' . ltrim($target, '/');
        }

        $path = parse_url($target, PHP_URL_PATH) ?: '/arena-php/desafio.php';
        if (! str_starts_with($path, '/arena-php/')) {
            $path = '/arena-php/desafio.php';
        }
        $query = parse_url($target, PHP_URL_QUERY);
        $destination = $path . ($query ? '?' . $query : '');

        $arenaUser = $this->mapArenaUser(
            $user->name ?? '',
            $user->email ?? '',
            (string) ($user->role ?? ''),
            (string) ($user->username ?? '')
        );
        abort_unless($arenaUser, 403, 'Usuario sem acesso a Arena PHP.');

        $payload = [
            'u' => $arenaUser['username'],
            'n' => $arenaUser['name'],
            'r' => $arenaUser['role'] ?? 'Aluno PHP',
            'exp' => time() + 300,
            'to' => $destination,
        ];

        $json = json_encode($payload, JSON_UNESCAPED_UNICODE);
        $b64 = rtrim(strtr(base64_encode($json), '+/', '-_'), '=');
        // Mesma chave bruta do .env que sso_consume.php lê (inclui prefixo base64: se houver).
        $appKey = (string) (env('APP_KEY') ?: config('app.key'));
        $sig = hash_hmac('sha256', $b64, $appKey);

        return redirect()->to('/arena-php/auth/sso_consume.php?token=' . rawurlencode($b64 . '.' . $sig));
    }

    /**
     * @return array{username:string,name:string,role?:string}|null
     */
    private function mapArenaUser(string $name, string $email, string $role = '', string $username = ''): ?array
    {
        $email = strtolower(trim($email));
        $first = trim(explode(' ', $name)[0] ?? '');
        $username = trim($username);

        $users = require public_path('arena-php/auth/users.php');

        $byEmail = [
            'leandro@codar.local' => 'Leandro',
            'elias@codar.local' => 'Elias',
            'eliasgomesjf@gmail.com' => 'Elias',
        ];

        $wanted = null;
        if ($role === 'professor' || $role === 'admin') {
            $wanted = 'Elias';
        } elseif (isset($byEmail[$email])) {
            $wanted = $byEmail[$email];
        } elseif ($username !== '') {
            $wanted = $username;
        } else {
            $wanted = $first;
        }

        foreach ($users as $candidate) {
            if (strcasecmp((string) ($candidate['username'] ?? ''), (string) $wanted) === 0) {
                return [
                    'username' => $candidate['username'],
                    'name' => $name !== '' ? $name : ($candidate['name'] ?? $candidate['username']),
                    'role' => $candidate['role'] ?? 'Aluno PHP',
                ];
            }
        }

        // Aluno LMS sem conta espelho: usa Convidado só como fallback de sessão,
        // mas prioriza match por primeiro nome case-insensitive parcial.
        foreach ($users as $candidate) {
            if (strcasecmp((string) ($candidate['username'] ?? ''), $first) === 0) {
                return [
                    'username' => $candidate['username'],
                    'name' => $name !== '' ? $name : ($candidate['name'] ?? $candidate['username']),
                    'role' => $candidate['role'] ?? 'Aluno PHP',
                ];
            }
        }

        return null;
    }
}
