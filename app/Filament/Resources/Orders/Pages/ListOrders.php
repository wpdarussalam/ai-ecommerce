<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            // Tombol Export Excel / CSV
            ExportAction::make()
                ->label('Export Laporan')
                ->color('success')
                ->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->withFilename('Laporan-Penjualan-' . date('Y-m-d'))
                        ->withColumns([
                            Column::make('order_number')->heading('No. Order'),
                            Column::make('customer.name')->heading('Nama Pelanggan'),
                            Column::make('status')->heading('Status Pesanan'),
                            Column::make('payment_status')->heading('Status Pembayaran'),
                            Column::make('courier')->heading('Ekspedisi'),
                            Column::make('tracking_number')->heading('No. Resi'),
                            Column::make('total_amount')->heading('Total Bayar'),
                            Column::make('created_at')->heading('Tanggal Transaksi'),
                        ]),
                ]),
        ];
    }
}