<?php

namespace App\Filament\Exports;

use App\Models\Order;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class OrderExporter extends Exporter
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID Pesanan'),

            ExportColumn::make('customer.name')
                ->label('Nama Pelanggan'),

            ExportColumn::make('total_amount')
                ->label('Total Harga (Rp)'),

            ExportColumn::make('status')
                ->label('Status Pesanan'),

            ExportColumn::make('created_at')
                ->label('Tanggal Transaksi'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Proses export data pesanan telah selesai dan ' . number_format($export->successful_rows) . ' baris data berhasil di-export.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal di-export.';
        }

        return $body;
    }
}