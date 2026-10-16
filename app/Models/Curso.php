<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $fillable = [
        'titulo',
        'descricao',
        'categoria',
        'duracao_horas_min',
        'duracao_horas_max',
        'nivel_trilha',
        'capa_path',
        'matriz_path',
        'checklist_path',
        'professor_id',
        'status',
        'agendado_em',
    ];

    protected $casts = [
        'agendado_em' => 'datetime',
        'duracao_horas_min' => 'integer',
        'duracao_horas_max' => 'integer',
    ];

    public function professor()
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    public function aulas()
    {
        return $this->hasMany(Aula::class);
    }

    /**
     * Label de nível para cards e catálogo (padrão reutilizável).
     */
    public function nivelLabel(): string
    {
        return match ($this->nivel_trilha) {
            'basico' => 'Basico',
            'intermediario' => 'Intermediario',
            'avancado' => 'Avancado',
            'trilha_completa' => 'Trilha completa',
            default => 'Trilha completa',
        };
    }

    /**
     * Faixa de horas estimada (ex.: 50–70h).
     */
    public function duracaoLabel(): string
    {
        $min = $this->duracao_horas_min;
        $max = $this->duracao_horas_max;

        if ($min && $max && $min !== $max) {
            return "{$min}–{$max}h";
        }

        if ($min || $max) {
            return ($min ?: $max).'h';
        }

        return '—';
    }

    public function capaUrl(): ?string
    {
        if (!$this->capa_path) {
            return null;
        }

        return '/storage/'.$this->capa_path;
    }

    public function matrizUrl(): ?string
    {
        return $this->matriz_path ? '/storage/'.$this->matriz_path : null;
    }

    public function checklistUrl(): ?string
    {
        return $this->checklist_path ? '/storage/'.$this->checklist_path : null;
    }
}
