<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GasInvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'gas_invoice_id',
        'gas_type_id',
        'quantity',
        'unit_price',
        'total',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(GasInvoice::class, 'gas_invoice_id');
    }

    public function gasType(): BelongsTo
    {
        return $this->belongsTo(GasType::class);
    }
}
