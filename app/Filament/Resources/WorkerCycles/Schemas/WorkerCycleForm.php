<?php

namespace App\Filament\Resources\WorkerCycles\Schemas;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class WorkerCycleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Select::make('cycle_id')
                            ->label('الدورة')
                            ->relationship('cycle', 'name')
                            ->preload()
                            ->searchable()
                            ->required(),
                        Select::make('worker_id')
                            ->label('العامل')
                            ->relationship('worker', 'name')
                            ->preload()
                            ->searchable()
                            ->required(),
                        DatePicker::make('work_start_date')
                            ->label('تاريخ بدء العمل')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($get, $set) => self::updateWages($get, $set)),
                        DatePicker::make('work_end_date')
                            ->label('تاريخ انتهاء العمل')
                            ->reactive()
                            ->afterStateUpdated(fn ($get, $set) => self::updateWages($get, $set)),
                        TextInput::make('daily_wage')
                            ->label('الأجر اليومي')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($get, $set) => self::updateWages($get, $set)),
                        
                        \Filament\Forms\Components\Repeater::make('advances')
                            ->label('السلفيات')
                            ->relationship('advances')
                            ->schema([
                                TextInput::make('amount')
                                    ->label('المبلغ')
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn ($get, $set) => self::updateWages($get, $set)),
                                DatePicker::make('advance_date')
                                    ->label('التاريخ')
                                    ->default(now())
                                    ->required(),
                                TextInput::make('reason')
                                    ->label('السبب'),
                            ])
                            ->columns(3)
                            ->reactive()
                            ->afterStateUpdated(fn ($get, $set) => self::updateWages($get, $set)),

                        TextInput::make('actual_days')
                            ->label('عدد الأيام الفعلية')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),
                        TextInput::make('gross_wage')
                            ->label('الأجر الإجمالي')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),
                        TextInput::make('total_advances')
                            ->label('إجمالي السلف')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->default(0),
                        TextInput::make('net_wage')
                            ->label('الصافي المستحق')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),
                        TextInput::make('paid_amount')
                            ->label('المبلغ المدفوع (إضافي)')
                            ->numeric()
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(fn ($get, $set) => self::updateWages($get, $set)),
                        TextInput::make('remaining')
                            ->label('المتبقي للوفاء')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->default(0),
                        Select::make('payment_status')
                            ->label('حالة الدفع')
                            ->options([
                                'paid' => 'مسدد بالكامل',
                                'partial' => 'مسدد جزئياً',
                                'unpaid' => 'غير مسدد',
                            ])
                            ->required(),
                        Textarea::make('notes')
                            ->label('ملاحظات')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function updateWages($get, $set)
    {
        $start = $get('work_start_date');
        $end = $get('work_end_date');
        $dailyWage = floatval($get('daily_wage') ?? 0);
        
        $days = 0;
        if ($start && $end) {
            $startDate = \Carbon\Carbon::parse($start);
            $endDate = \Carbon\Carbon::parse($end);
            $days = max(0, $startDate->diffInDays($endDate) + 1);
        }
        
        $gross = $days * $dailyWage;
        $set('actual_days', $days);
        $set('gross_wage', $gross);
        
        // حساب السلف من الـ Repeater
        $advances = $get('advances') ?? [];
        $totalAdvances = 0;
        foreach ($advances as $advance) {
            $totalAdvances += floatval($advance['amount'] ?? 0);
        }

        // إضافة المصروفات النثرية المرتبطة بالعامل والسلف المنفصلة
        $workerId = $get('worker_id');
        $cycleId = $get('cycle_id');
        if ($workerId && $cycleId) {
            $miscSum = \App\Models\MiscellaneousExpense::where('worker_id', $workerId)
                ->where('cycle_id', $cycleId)
                ->sum('amount');
            $totalAdvances += $miscSum;
        }

        $set('total_advances', $totalAdvances);
        
        $net = max($gross - $totalAdvances, 0);
        $set('net_wage', $net);
        
        $paid = floatval($get('paid_amount') ?? 0);
        $remaining = max($net - $paid, 0);
        $set('remaining', $remaining);

        // تحديث حالة الدفع تلقائياً
        if ($remaining <= 0 && $gross > 0) {
            $set('payment_status', 'paid');
        } elseif ($paid > 0 || $totalAdvances > 0) {
            $set('payment_status', 'partial');
        } else {
            $set('payment_status', 'unpaid');
        }
    }
}
