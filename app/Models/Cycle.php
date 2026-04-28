<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cycle extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'name',
        'start_date',
        'end_date',
        'initial_count',
        'chick_price',
        'chick_total',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    public function feedPurchases(): HasMany
    {
        return $this->hasMany(FeedPurchase::class);
    }

    public function medicineInvoices(): HasMany
    {
        return $this->hasMany(MedicineInvoice::class);
    }

    public function gasInvoices(): HasMany
    {
        return $this->hasMany(GasInvoice::class);
    }

    public function sawdustPurchases(): HasMany
    {
        return $this->hasMany(SawdustPurchase::class);
    }

    public function workerCycles(): HasMany
    {
        return $this->hasMany(WorkerCycle::class);
    }

    public function miscellaneousExpenses(): HasMany
    {
        return $this->hasMany(MiscellaneousExpense::class);
    }

    public function miscExpenses(): HasMany
    {
        return $this->hasMany(MiscellaneousExpense::class);
    }

    public function mortalityLogs(): HasMany
    {
        return $this->hasMany(MortalityLog::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function cycleClosing(): HasOne
    {
        return $this->hasOne(CycleClosing::class);
    }

    public function closing(): HasOne
    {
        return $this->cycleClosing();
    }

    public function financialRecords(): HasMany
    {
        return $this->hasMany(FinancialRecord::class);
    }
}
