<?php

namespace App\Filament\Resources\FinancialRecords\Tables;

use App\Filament\Exports\FinancialRecordsExporter;
use Filament\Actions\Action;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FinancialRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cycle.name')
                    ->label('الدورة')
                    ->searchable(),
                TextColumn::make('party_name')
                    ->label('اسم الطرف')
                    ->searchable(),
                TextColumn::make('party_type')
                    ->label('نوع الطرف'),
                TextColumn::make('type')
                    ->label('النوع')
                    ->badge(),
                TextColumn::make('original_amount')
                    ->label('المبلغ الأصلي'),
                TextColumn::make('paid_amount')
                    ->label('المدفوع'),
                TextColumn::make('remaining')
                    ->label('المتبقي'),
                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('add_payment')
                    ->label('تسجيل دفعة')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn($record) => $record->status === 'active')
                    ->form([
                        TextInput::make('amount')
                            ->label('المبلغ')
                            ->numeric()
                            ->required(),
                        DatePicker::make('payment_date')
                            ->label('التاريخ')
                            ->default(now()),
                        TextInput::make('payment_method')
                            ->label('طريقة الدفع')
                            ->placeholder('كاش / تحويل'),
                    ])
                    ->action(function ($record, array $data) {
                        \App\Models\FinancialPayment::create([
                            'financial_record_id' => $record->id,
                            'amount' => $data['amount'],
                            'payment_date' => $data['payment_date'],
                            'payment_method' => $data['payment_method'] ?? null,
                        ]);

                        $record->paid_amount += $data['amount'];
                        $record->remaining = max(0, $record->original_amount - $record->paid_amount);
                        $record->status = $record->remaining <= 0 ? 'settled' : 'active';
                        $record->save();

                        // Update source model
                        if ($record->source_type && $record->source_id) {
                            $sourceModel = match ($record->source_type) {
                                'feed_purchase' => \App\Models\FeedPurchase::find($record->source_id),
                                'medicine_invoice' => \App\Models\MedicineInvoice::find($record->source_id),
                                'gas_invoice' => \App\Models\GasInvoice::find($record->source_id),
                                'sawdust_purchase' => \App\Models\SawdustPurchase::find($record->source_id),
                                'sale' => \App\Models\Sale::find($record->source_id),
                                'worker_cycle' => \App\Models\WorkerCycle::find($record->source_id),
                                default => null,
                            };

                            $sourceModel?->update([
                                'paid_amount' => $record->paid_amount,
                                'remaining' => $record->remaining,
                                'payment_status' => $record->remaining <= 0 ? 'paid'
                                    : ($record->paid_amount > 0 ? 'partial' : 'unpaid'),
                            ]);
                        }
                    }),
            ])
            ->toolbarActions([
                ExportBulkAction::make('export')
                    ->label('تصدير')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(FinancialRecordsExporter::class),
            ]);
    }
}
