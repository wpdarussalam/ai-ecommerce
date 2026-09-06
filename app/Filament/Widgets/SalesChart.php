<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SalesChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Penjualan';

    protected function getData(): array
    {
        $data = Order::where('status', '!=', 'cancelled')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('total', 'date')
            ->toArray();

        $labels = [];
        $totals = [];

        foreach ($data as $date => $total) {
            $labels[] = Carbon::parse($date)->format('d M Y');
            $totals[] = (float) $total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan (Rp)',
                    'data' => $totals,
                    'borderColor' => '#eab308',
                    'backgroundColor' => 'rgba(234, 179, 8, 0.15)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}