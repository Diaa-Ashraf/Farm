<?php

namespace App\Filament\Resources\Cycles\Actions;

use App\Models\Cycle;
use App\Models\CycleClosing;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class CloseCycleAction
{
    public static function make(): Action
    {
        return Action::make('closeCycle')
            ->label('تقفيل الدورة')
            ->color('success')
            ->icon('heroicon-o-check-circle')
            ->requiresConfirmation()
            ->modalHeading('تقفيل الدورة')
            ->modalSubheading('هل أنت متأكد من تقفيل هذه الدورة؟ لا يمكن التراجع عن هذا.')
            ->modalButton('نعم، تقفيل الدورة')
            ->action(function (Model $record): void {
                /** @var Cycle $cycle */
                $cycle = $record;

                // منع التقفيل إذا كانت الدورة مغلقة بالفعل
                if ($cycle->status === 'closed' || $cycle->cycleClosing()->exists()) {
                    throw new \Exception('هذه الدورة مغلقة بالفعل');
                }

                // حساب جميع الإحصائيات
                $mortalityCount = $cycle->mortalityLogs()->sum('count');
                $soldCount = $cycle->sales()->sum('bird_count') ?? 0;
                $cycleDays = $cycle->start_date->diffInDays($cycle->end_date ?? now());

                $feedCost = $cycle->feedPurchases()->sum('total');
                $feedTons = $cycle->feedPurchases()
                    ->where('unit', 'ton')
                    ->sum('quantity');

                $medicineCost = $cycle->medicineInvoices()->sum('total');
                $gasCost = $cycle->gasInvoices()->sum('total');

                $sawdustCost = $cycle->sawdustPurchases()->sum('total');
                $sawdustTons = $cycle->sawdustPurchases()->sum('quantity_tons');

                $workerCost = $cycle->workerCycles()->sum('net_wage');
                $miscCost = $cycle->miscExpenses()->sum('amount');

                $totalExpenses = $cycle->chick_total + $feedCost + $medicineCost + $gasCost + $sawdustCost + $workerCost + $miscCost;

                $totalRevenue = $cycle->sales()->sum('total');
                $totalWeightKg = $cycle->sales()->sum('total_weight_kg');

                $netProfit = $totalRevenue - $totalExpenses;
                $result = $netProfit > 0 ? 'profit' : ($netProfit < 0 ? 'loss' : 'break_even');

                // حساب KPIs
                $fcr = ($feedTons > 0 && $totalWeightKg > 0) ? ($feedTons * 1000) / $totalWeightKg : 0;
                $avgWeightKg = $soldCount > 0 ? $totalWeightKg / $soldCount : 0;
                $mortalityRate = $cycle->initial_count > 0 ? ($mortalityCount / $cycle->initial_count) * 100 : 0;
                $profitPerKg = $totalWeightKg > 0 ? $netProfit / $totalWeightKg : 0;

                // إنشاء سجل تقفيل
                CycleClosing::create([
                    'cycle_id' => $cycle->id,
                    'closing_date' => now(),
                    'initial_count' => $cycle->initial_count,
                    'mortality_count' => $mortalityCount,
                    'sold_count' => $soldCount,
                    'cycle_days' => $cycleDays,
                    'chick_cost' => $cycle->chick_total,
                    'feed_cost' => $feedCost,
                    'feed_tons' => $feedTons,
                    'medicine_cost' => $medicineCost,
                    'gas_cost' => $gasCost,
                    'sawdust_cost' => $sawdustCost,
                    'sawdust_tons' => $sawdustTons,
                    'worker_cost' => $workerCost,
                    'misc_cost' => $miscCost,
                    'total_expenses' => $totalExpenses,
                    'total_revenue' => $totalRevenue,
                    'total_weight_kg' => $totalWeightKg,
                    'net_profit' => $netProfit,
                    'result' => $result,
                    'fcr' => $fcr,
                    'avg_weight_kg' => $avgWeightKg,
                    'mortality_rate' => $mortalityRate,
                    'profit_per_kg' => $profitPerKg,
                ]);

                // تحديث حالة الدورة
                $cycle->update(['status' => 'closed', 'end_date' => now()]);

                // إشعار بنجاح الإغلاق
                \Filament\Notifications\Notification::make()
                    ->title('تم إغلاق الدورة بنجاح')
                    ->body('يمكنك الآن عرض تقارير الدورة')
                    ->success()
                    ->send();
            })
            ->visible(fn(Model $record): bool => $record->status === 'active' && ! $record->cycleClosing()->exists());
    }
}
