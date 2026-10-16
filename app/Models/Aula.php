<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aula extends Model
{
    use HasFactory;

    protected $table = 'aulas'; // nome da tabela

    protected $fillable = [
        'curso_id',
        'titulo',
        'descricao',
        'data',
        'ordem',
        'is_hidden',
    ];

    // Relacionamento: uma aula tem muitos conteúdos
    public function conteudos()
    {
        return $this->hasMany(Conteudo::class);
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }
}
