<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    // Interval refresh otomatis (non-static)
    protected ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // 1. Hitung Total Penjualan Bulan Ini (Hanya status completed atau paid)
        $totalPenjualanBulanIni = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where(function ($query) {
                $query->where('status', 'completed')
                      ->orWhere('payment_status', 'paid');
            })
            ->sum('total_amount');

        // 2. Hitung Pesanan Pending (Butuh Tindakan)
        $pesananPending = Order::where('status', 'pending')->count();

        // 3. Hitung Total Pelanggan
        $totalPelanggan = Customer::count();

        // 4. Hitung Total Produk
        $totalProduk = Product::count();

        return [
            Stat::make('Penjualan Bulan Ini', 'Rp ' . number_format($totalPenjualanBulanIni, 0, ',', '.'))
                ->description('Total omset bulan ' . now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Pesanan Pending', $pesananPending . ' Pesanan')
                ->description('Menunggu diproses/dikonfirmasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pesananPending > 0 ? 'warning' : 'gray'),

            Stat::make('Total Pelanggan', $totalPelanggan . ' Orang')
                ->description('Pelanggan terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Total Produk', $totalProduk . ' Item')
                ->description('Jumlah variasi produk aktif')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),
        ];
    }
}