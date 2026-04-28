<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class MedicineInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'cycle_id',
        'pharmacy_name',
        'pharmacy_phone',
        'invoice_date',
        'total',
        'paid_amount',
        'remaining',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
    ];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(MedicineInvoiceItem::class);
    }

    public function recalculateTotals(): void
    {
        $total = $this->items()->sum(DB::raw('quantity * unit_price'));
        $paidAmount = $this->paid_amount ?: 0;
        $remaining = max($total - $paidAmount, 0);
        $paymentStatus = $remaining === 0
            ? 'paid'
            : ($paidAmount > 0 ? 'partial' : 'unpaid');

        $this->fill([
            'total' => $total,
            'remaining' => $remaining,
            'payment_status' => $paymentStatus,
        ])->saveQuietly();
    }
}
