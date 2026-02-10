<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Enrollment - Representa a matrícula de um aluno em um curso
 * 
 * Relacionamentos:
 * - Um enrollment pertence a um usuário (aluno)
 * - Um enrollment pertence a um curso
 * 
 * @property int $id
 * @property int $aluno_id
 * @property int $curso_id
 * @property date $data_matricula
 * @property enum $status (ativo, concluido, cancelado)
 * @property timestamps
 */
class Enrollment extends Model
{
    protected $table = 'matriculas';

    protected $fillable = [
        'aluno_id',
        'curso_id',
        'turma_id',
        'data_matricula',
        'status',
    ];

    protected $casts = [
        'data_matricula' => 'date',
    ];

    /**
     * Relacionamento: Uma matrícula pertence a um usuário (aluno)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aluno_id');
    }

    /**
     * Relacionamento: Uma matrícula pertence a um curso
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    /**
     * Relacionamento: Uma matricula pode pertencer a uma turma
     */
    public function turma(): BelongsTo
    {
        return $this->belongsTo(Turma::class, 'turma_id');
    }

    /**
     * Scope: Matrículas ativas
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'ativo');
    }

    /**
     * Scope: Matrículas concluídas
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'concluido');
    }

    /**
     * Marcar como concluída
     */
    public function complete()
    {
        $this->update([
            'status' => 'concluido',
        ]);
    }

    /**
     * Cancelar matrícula
     */
    public function cancel()
    {
        $this->update(['status' => 'cancelado']);
    }
}