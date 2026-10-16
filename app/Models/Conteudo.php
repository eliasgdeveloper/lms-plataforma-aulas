<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conteudo extends Model
{
    use HasFactory;

    protected $table = 'conteudos'; // nome da tabela

    protected $fillable = [
        'aula_id',
        'titulo',
        'descricao',
        'tipo',
        'arquivo',
        'url',
        'is_hidden',
        'ordem',
        'allow_download_student',
        'allow_download_professor',
    ];

    protected $casts = [
        'is_hidden' => 'boolean',
        'ordem' => 'integer',
        'allow_download_student' => 'boolean',
        'allow_download_professor' => 'boolean',
    ];

    // Relacionamento: um conteúdo pertence a uma aula
    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }
}
