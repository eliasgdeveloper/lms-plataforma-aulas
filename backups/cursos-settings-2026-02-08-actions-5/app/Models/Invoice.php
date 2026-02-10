<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Invoice - Representa um boleto ou fatura
 * 
 * @property int $id
 * @property int $payment_id
 * @property string $number (ID único do boleto)
 * @property date $due_date
 * @property text|null $url (link/código do boleto)
 * @property enum $status (pending, paid, cancelled)
 * @property timestamps
 */
class Invoice extends Model
{
    protected $fillable = [
        'payment_id',
        'number',
        'due_date',
        'url',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Gerar número único de boleto
     */
    public static function generateNumber()
    {
        return 'BOL-' . date('Ymd') . '-' . str_pad(
            self::count() + 1,
            6,
            '0',
            STR_PAD_LEFT
        );
    }
}
