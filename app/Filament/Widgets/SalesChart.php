<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SalesChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Penjualan Bulanan'; // Hilangkan kata 'static'

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = [];
        $months = [];

        for ($month = 1; $month <= 12; $month++) {
            $months[] = Carbon::create()->month($month)->translatedFormat('F');
            
            $data[] = Order::where('status', 'completed')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', date('Y'))
                ->sum('grand_total');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan (Rp)',
                    'data' => $data,
                    'fill' => 'start',
                    'borderColor' => '#fbbf24',
                    'backgroundColor' => 'rgba(251, 191, 36, 0.1)',
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}