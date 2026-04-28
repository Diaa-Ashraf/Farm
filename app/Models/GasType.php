<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GasType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'default_price',
    ];

    protected $attributes = [
        'default_price' => 0,
    ];

    public function gasInvoiceItems(): HasMany
    {
        return $this->hasMany(GasInvoiceItem::class);
    }
}
