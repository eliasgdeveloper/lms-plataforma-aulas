<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model AuditLog - Registro de todas ações de admin para auditoria
 * 
 * IMPORTANTE: Usar para rastreabilidade e conformidade com LGPD/GDPR
 * 
 * @property int $id
 * @property int|null $user_id (quem fez a ação)
 * @property string $action (criar, editar, deletar, etc)
 * @property string $model (qual modelo foi afetado)
 * @property int $model_id (ID da instância afetada)
 * @property json|null $changes (mudanças realizadas)
 * @property string|null $ip_address
 * @property text|null $user_agent
 * @property timestamps
 */
class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'changes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'changes' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Registrar ação automaticamente
     */
    public static function log($action, $model, $modelId, $changes = null)
    {
        if (!auth()->check()) return;

        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Filtrar logs por modelo
     */
    public function scopeByModel($query, $model)
    {
        return $query->where('model', $model);
    }

    /**
     * Logs do usuário
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
