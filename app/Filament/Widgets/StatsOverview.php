<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Menghitung omset dari semua order yang tidak dibatalkan
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $totalOrders = Order::count();
        $totalProducts = Product::count();

        return [
            Stat::make('Total Omset', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Total pendapatan pesanan aktif')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Pesanan', $totalOrders)
                ->description('Jumlah seluruh pesanan masuk')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Total Produk', $totalProducts)
                ->description('Jumlah produk yang tersedia')
                ->descriptionIcon('heroicon-m-cube')
                ->color('warning'),
        ];
    }
}