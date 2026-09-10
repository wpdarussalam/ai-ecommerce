<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Penjualan', 'IDR ' . number_format(Order::where('status', '!=', 'cancelled')->sum('total_amount'), 0, ',', '.'))
                ->description('Semua pesanan aktif')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Pesanan Diproses', Order::whereIn('status', ['pending', 'processing'])->count())
                ->description('Butuh tindakan')
                ->descriptionIcon('heroicon-m-lock-closed')
                ->color('warning'),

            Stat::make('Total Pelanggan', User::count())
                ->description('Pelanggan terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Stok Menipis', Product::where('stock', '<=', 5)->count())
                ->description('Stok <= 5 pcs')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}