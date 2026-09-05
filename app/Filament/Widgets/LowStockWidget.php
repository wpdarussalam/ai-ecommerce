<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LowStockWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $outOfStockCount = Product::where('stock', '<=', 0)->count();
        $lowStockCount = Product::where('stock', '>', 0)->where('stock', '<=', 5)->count();

        return [
            Stat::make('Produk Stok Habis', $outOfStockCount)
                ->description('Perlu segera di-restock')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($outOfStockCount > 0 ? 'danger' : 'success'),

            Stat::make('Produk Stok Menipis', $lowStockCount)
                ->description('Sisa 5 item atau kurang')
                ->descriptionIcon('heroicon-m-archive-box-x-mark')
                ->color($lowStockCount > 0 ? 'warning' : 'success'),
        ];
    }
}