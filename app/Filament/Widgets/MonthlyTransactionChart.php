<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;

class MonthlyTransactionChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Transaction Chart';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $trend = Trend::model(Transaction::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Transaction created',
                    'data' => $trend->map(fn($item) => $item->aggregate)->toArray(),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $trend->map(fn($item) => $item->date)->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}