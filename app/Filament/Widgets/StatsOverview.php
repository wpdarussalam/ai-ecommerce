<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1; // Memastikan posisi widget paling atas

    protected function getStats(): array
    {
        // Hitung omset dari status completed, default 0 jika null
        $totalRevenue = Order::where('status', 'completed')->sum('grand_total') ?? 0;
        $totalOrders = Order::count();
        $totalProducts = Product::count();

        return [
            Stat::make('Total Omset (Completed)', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Total pendapatan pesanan selesai')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Pesanan', (string) $totalOrders)
                ->description('Jumlah seluruh pesanan masuk')
                ->descriptionIcon('heroicon-o-shopping-bag')
                ->color('info'),

            Stat::make('Total Produk', (string) $totalProducts)
                ->description('Jumlah produk yang tersedia')
                ->descriptionIcon('heroicon-o-archive-box')
                ->color('warning'),
        ];
    }
}