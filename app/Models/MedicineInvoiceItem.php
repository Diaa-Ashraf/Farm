<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicineInvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_invoice_id',
        'medicine_name',
        'unit',
        'quantity',
        'unit_price',
        'total',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(MedicineInvoice::class, 'medicine_invoice_id');
    }
}
