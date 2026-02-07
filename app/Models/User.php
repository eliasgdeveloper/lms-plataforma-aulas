<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo User
 *
 * Este model representa a tabela `users` no banco de dados.
 * Ele é usado para autenticação, autorização e relacionamento
 * com outras entidades do sistema.
 *
 * Observações:
 * - O campo `role` define o perfil do usuário (admin, professor, aluno).
 * - Métodos auxiliares (`isAdmin()`, `isProfessor()`, `isAluno()`) facilitam
 *   verificações em controllers, views e middlewares.
 * - O campo `password` é automaticamente criptografado via `casts()`.
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Campos que podem ser atribuídos em massa (mass assignment).
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',       // Nome do usuário
        'email',      // Email único
        'password',   // Senha criptografada
        'role',       // Papel do usuário (admin, professor, aluno)
    ];

    /**
     * Campos ocultos quando o model é serializado (ex.: JSON).
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',        // Nunca expor a senha
        'remember_token',  // Token de sessão persistente
    ];

    /**
     * Casts automáticos de atributos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Converte para objeto DateTime
            'password' => 'hashed',            // Garante que a senha seja sempre criptografada
        ];
    }

    /**
     * Métodos auxiliares para verificar o papel do usuário.
     * Facilitam a lógica de autorização em controllers/views.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isProfessor(): bool
    {
        return $this->role === 'professor';
    }

    public function isAluno(): bool
    {
        return $this->role === 'aluno';
    }
}
