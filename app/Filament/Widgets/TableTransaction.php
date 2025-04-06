<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TableTransaction extends BaseWidget
{
    protected static ?string $heading = 'Transaction Table';
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()->whereStatus('pending')->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('listing.title')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_price')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()->color(
                        fn(string $state): string => match ($state) {
                            'pending' => 'warning',
                            'success' => 'primary',
                            'cancel' => 'danger',
                        }
                    )
            ])
            ->actions([
                Action::make('Approve')
                    ->button()
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn(Transaction $transaction) => $transaction->status === 'pending')
                    ->action(function (Transaction $transaction) {
                        $transaction->update(['status' => 'success']);
                        Notification::make()

                            ->title('Transaction Approved')
                            ->body('The transaction has been approved.')
                            ->icon('heroicon-o-check-circle')
                            ->send();
                    }),

            ]);
    }
}