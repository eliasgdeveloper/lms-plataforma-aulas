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
 * @property int $user_id
 * @property int $course_id
 * @property timestamp $enrolled_at
 * @property timestamp|null $completed_at
 * @property decimal $progress (0-100)
 * @property enum $status (active, completed, cancelled)
 * @property int|null $certificate_id
 * @property timestamps
 */
class Enrollment extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'enrolled_at',
        'completed_at',
        'progress',
        'status',
        'certificate_id',
    ];

    protected $casts = [
        'enrolled_at' => 'timestamp',
        'completed_at' => 'timestamp',
        'progress' => 'decimal:2',
    ];

    /**
     * Relacionamento: Uma matrícula pertence a um usuário (aluno)
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relacionamento: Uma matrícula pertence a um curso
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Scope: Matrículas ativas
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Matrículas concluídas
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Marcar como concluída
     */
    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'progress' => 100,
        ]);
    }

    /**
     * Cancelar matrícula
     */
    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
    }
}
