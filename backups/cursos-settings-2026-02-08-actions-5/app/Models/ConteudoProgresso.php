<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConteudoProgresso extends Model
{
    protected $table = 'conteudo_progresso';

    protected $fillable = [
        'user_id',
        'conteudo_id',
        'progresso',
        'concluido',
    ];

    protected $casts = [
        'concluido' => 'boolean',
        'progresso' => 'integer',
    ];

    public function conteudo()
    {
        return $this->belongsTo(Conteudo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
