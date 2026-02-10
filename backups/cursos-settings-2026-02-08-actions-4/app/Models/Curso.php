<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $fillable = [
        'titulo',
        'descricao',
        'categoria',
        'professor_id',
        'status',
        'agendado_em',
    ];

    protected $casts = [
        'agendado_em' => 'datetime',
    ];

    public function professor()
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    public function aulas()
    {
        return $this->hasMany(Aula::class);
    }
}
