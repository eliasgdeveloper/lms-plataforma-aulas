<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Model Course - Representa um curso na plataforma
 *
 * Relacionamentos:
 * - Um professor (teacher) tem muitos cursos
 * - Um curso tem muitos alunos (através de enrollments)
 * - Um curso tem muitas aulas (lessons)
 * - Um curso tem muitos pagamentos
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $category
 * @property int $teacher_id (ID do professor responsável)
 * @property decimal $price
 * @property int $duration (em horas)
 * @property int|null $max_students
 * @property date $start_date
 * @property date $end_date
 * @property enum $status (draft, active, inactive, archived)
 * @property string|null $thumbnail
 * @property timestamps
 */
class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'teacher_id',
        'price',
        'duration',
        'max_students',
        'start_date',
        'end_date',
        'status',
        'thumbnail',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
    ];

    /**
     * Relacionamento: Um curso pertence a um professor
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Relacionamento: Um curso tem muitos alunos (Many-to-Many via enrollments)
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot('enrolled_at', 'completed_at', 'progress', 'status')
            ->withTimestamps();
    }

    /**
     * Relacionamento: Um curso tem muitas matrículas
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Relacionamento: Um curso tem muitas aulas
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * Relacionamento: Um curso tem muitos pagamentos
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scope: Cursos ativos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today());
    }

    /**
     * Scope: Cursos do professor
     */
    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Calcular receita total do curso
     */
    public function totalRevenue()
    {
        return $this->payments()
            ->where('status', 'paid')
            ->sum('amount');
    }

    /**
     * Calcular taxa de conclusão
     */
    public function completionRate()
    {
        $total = $this->enrollments()->count();
        if ($total === 0) {
            return 0;
        }
        
        $completed = $this->enrollments()
            ->where('status', 'completed')
            ->count();
        
        return round(($completed / $total) * 100, 2);
    }
}
