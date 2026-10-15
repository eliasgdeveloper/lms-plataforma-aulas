<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;

class ViewAs
{
    public const SESSION_KEY = 'view_as_role';

    public static function roles(): array
    {
        return [
            'admin' => 'Administrador',
            'professor' => 'Professor',
            'aluno' => 'Estudante',
        ];
    }

    public static function canSwitch(): bool
    {
        $role = Auth::user()->role ?? null;

        return in_array($role, ['admin', 'professor'], true);
    }

    public static function realRole(): ?string
    {
        return Auth::user()->role ?? null;
    }

    public static function effectiveRole(): ?string
    {
        $real = self::realRole();
        $chosen = session(self::SESSION_KEY);

        if (! self::canSwitch() || ! is_string($chosen) || ! array_key_exists($chosen, self::roles())) {
            return $real;
        }

        return $chosen;
    }

    public static function isPreview(): bool
    {
        return Auth::check() && self::effectiveRole() !== self::realRole();
    }

    public static function label(?string $role): string
    {
        return self::roles()[$role] ?? 'Usuário';
    }

    public static function homeFor(?string $role): string
    {
        return match ($role) {
            'admin' => route('admin.dashboard'),
            'professor' => route('professor.dashboard'),
            'aluno' => route('aluno.dashboard'),
            default => route('dashboard'),
        };
    }
}
