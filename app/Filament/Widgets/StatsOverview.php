<?php

namespace App\Filament\Widgets;

use App\Models\Listing;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverview extends BaseWidget
{
    private function getPercentage(int $from, int $to)
    {
        if ($from == 0) {
            return $to == 0 ? 0 : 100; // Jika from 0 dan to tidak 0, berarti ada perubahan 100%
        }

        $percentage = (($to - $from) / $from) * 100;

        // Membulatkan hasil ke 2 desimal
        return round($percentage, 2);
    }

    protected function getStats(): array
    {
        $newListing = Listing::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $newTransaction = Transaction::whereStatus('success')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);

        $prevTransaction = Transaction::whereStatus('success')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year);

        $transactionCount     = $newTransaction->count();
        $prevTransactionCount = $prevTransaction->count();

        $transactionRevenue     = $newTransaction->sum('total_price');
        $prevTransactionRevenue = $prevTransaction->sum('total_price');

        $transactionPercentage = $this->getPercentage($prevTransactionCount, $transactionCount);
        $revenuePercentage     = $this->getPercentage($prevTransactionRevenue, $transactionRevenue);

        return [
            Stat::make('New Listing This Month', $newListing)
                ->description('Listings created this month')
                ->icon('heroicon-o-document-plus')
                ->color('primary'),

            Stat::make('Transactions This Month', $transactionCount)
                ->description($transactionPercentage > 0
                    ? "{$transactionPercentage}% increased from last month"
                    : "{$transactionPercentage}% decreased from last month")
                ->descriptionIcon($transactionPercentage > 0
                    ? 'heroicon-m-arrow-trending-up'
                    : 'heroicon-m-arrow-trending-down')
                ->color($transactionPercentage > 0 ? 'success' : 'danger'),

            Stat::make('Revenue This Month', Number::currency($transactionRevenue, 'USD'))
                ->description($revenuePercentage > 0
                    ? "{$revenuePercentage}% growth from last month"
                    : "{$revenuePercentage}% drop from last month")
                ->descriptionIcon($revenuePercentage > 0
                    ? 'heroicon-m-arrow-trending-up'
                    : 'heroicon-m-arrow-trending-down')
                ->color($revenuePercentage > 0 ? 'success' : 'danger'),
        ];
    }
}