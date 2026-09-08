<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // 1. Hitung Total Penjualan (Hanya order yang tidak cancelled)
        $totalPenjualan = Order::where('status', '!=', 'cancelled')->sum('total_amount');

        // 2. Hitung Pesanan yang Perlu Diproses (status processing / pending)
        $pesananPending = Order::whereIn('status', ['pending', 'processing'])->count();

        // 3. Hitung Total Pelanggan
        $totalPelanggan = Customer::count();

        // 4. Hitung Produk Stok Menipis (stok <= 3)
        $stokMenipis = Product::where('stock', '<=', 3)->count();

        return [
            Stat::make('Total Penjualan', 'IDR ' . number_format($totalPenjualan, 0, ',', '.'))
                ->description('Semua pesanan aktif')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Pesanan Diproses', $pesananPending)
                ->description('Butuh tindakan')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),

            Stat::make('Total Pelanggan', $totalPelanggan)
                ->description('Pelanggan terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Stok Menipis', $stokMenipis)
                ->description('Stok <= 3 pcs')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}