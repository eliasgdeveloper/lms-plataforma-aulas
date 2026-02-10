<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Lesson - Representa uma aula em um curso
 * 
 * @property int $id
 * @property int $course_id
 * @property string $title
 * @property text $content
 * @property enum $type (text, video, quiz, task, resource)
 * @property int $order (posição na aula)
 * @property timestamp|null $published_at
 * @property timestamps
 */
class Lesson extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'content',
        'type',
        'order',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'timestamp',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }
}
