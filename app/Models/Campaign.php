<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Campaign - Representa campanhas de marketing
 * 
 * @property int $id
 * @property int $created_by (ID do admin criador)
 * @property string $title
 * @property enum $type (email, sms, push)
 * @property text $content
 * @property int $audience_count
 * @property decimal $open_rate
 * @property enum $status (draft, scheduled, active, completed)
 * @property timestamp|null $scheduled_at
 * @property timestamp|null $sent_at
 * @property timestamps
 */
class Campaign extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'type',
        'content',
        'audience_count',
        'open_rate',
        'status',
        'scheduled_at',
        'sent_at',
    ];

    protected $casts = [
        'open_rate' => 'decimal:2',
        'scheduled_at' => 'timestamp',
        'sent_at' => 'timestamp',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', '!=', 'completed');
    }

    /**
     * Enviar campanha
     */
    public function send()
    {
        $this->update([
            'status' => 'active',
            'sent_at' => now(),
        ]);
    }
}
