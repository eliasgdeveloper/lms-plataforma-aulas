<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo User
 *
 * Este model representa a tabela `users` no banco de dados.
 * Ele ÃƒÂ© usado para autenticaÃƒÂ§ÃƒÂ£o, autorizaÃƒÂ§ÃƒÂ£o e relacionamento
 * com outras entidades do sistema.
 *
 * ObservaÃƒÂ§ÃƒÂµes:
 * - O campo `role` define o perfil do usuÃƒÂ¡rio (admin, professor, aluno).
 * - MÃƒÂ©todos auxiliares (`isAdmin()`, `isProfessor()`, `isAluno()`) facilitam
 *   verificaÃƒÂ§ÃƒÂµes em controllers, views e middlewares.
 * - O campo `password` ÃƒÂ© automaticamente criptografado via `casts()`.
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Campos que podem ser atribuÃƒÂ­dos em massa (mass assignment).
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',       // Nome do usuÃƒÂ¡rio
        'email',      // Email ÃƒÂºnico
        'password',   // Senha criptografada
        'role',       // Papel do usuÃƒÂ¡rio (admin, professor, aluno)
        'status',     // Status (active, inactive)
        'phone',      // Telefone
        'bio',        // Bio
        'avatar',     // Avatar
    ];

    /**
     * Campos ocultos quando o model ÃƒÂ© serializado (ex.: JSON).
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',        // Nunca expor a senha
        'remember_token',  // Token de sessÃƒÂ£o persistente
    ];

    /**
     * Casts automÃƒÂ¡ticos de atributos.
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
     * MÃƒÂ©todos auxiliares para verificar o papel do usuÃƒÂ¡rio.
     * Facilitam a lÃƒÂ³gica de autorizaÃƒÂ§ÃƒÂ£o em controllers/views.
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

    /**
     * Relacionamento: Um usuÃ¡rio pode ter vÃ¡rias matrÃ­culas (enrollments)
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'aluno_id');
    }

    /**
     * Relacionamento: Um usuÃ¡rio pode ter vÃ¡rios pagamentos
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
