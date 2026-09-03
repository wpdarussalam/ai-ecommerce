<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer_name')
                    ->label('Nama Pelanggan')
                    ->searchable(),

                TextColumn::make('grand_total')
                    ->label('Total Transaksi')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable(),

                SelectColumn::make('status')
                    ->label('Status Pesanan')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Diproses',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),

                SelectColumn::make('payment_status')
                    ->label('Status Bayar')
                    ->options([
                        'unpaid' => 'Belum Dibayar',
                        'paid' => 'Lunas',
                        'failed' => 'Gagal',
                    ]),

                TextColumn::make('created_at')
                    ->label('Tanggal Pesan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
