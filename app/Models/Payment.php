<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Payment - Representa um pagamento na plataforma
 *
 * Relacionamentos:
 * - Um pagamento pertence a um usuário
 * - Um pagamento opcionalmente pertence a uma matrícula
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $enrollment_id
 * @property decimal $amount
 * @property enum $method (boleto, card, pix)
 * @property enum $status (pending, paid, cancelled)
 * @property string|null $reference
 * @property text|null $receipt
 * @property timestamps
 */
class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'enrollment_id',
        'amount',
        'method',
        'status',
        'reference',
        'receipt',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Relacionamento: Um pagamento pertence a um usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: Um pagamento pertence a uma matrícula
     */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    /**
     * Relacionamento: Um pagamento tem um boleto
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Scope: Pagamentos pendentes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Pagamentos pagos
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Marcar como pago
     */
    public function markAsPaid()
    {
        $this->update(['status' => 'paid']);
        
        if ($this->enrollment) {
            $this->enrollment->update(['status' => 'active']);
        }
    }

    /**
     * Calcular receita total
     */
    public static function totalRevenue()
    {
        return self::where('status', 'paid')->sum('amount');
    }
}
