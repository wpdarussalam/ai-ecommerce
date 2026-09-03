<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        // Kolom Kiri: Detail Pelanggan & Produk (Lebar 2)
                        Group::make()
                            ->schema([
                                Section::make('Informasi Pelanggan')
                                    ->schema([
                                        TextInput::make('order_number')
                                            ->label('No. Pesanan')
                                            ->default('ORD-' . date('Ymd') . '-' . rand(1000, 9999))
                                            ->required()
                                            ->readOnly(),

                                        TextInput::make('customer_name')
                                            ->label('Nama Pelanggan')
                                            ->required(),

                                        TextInput::make('customer_email')
                                            ->label('Email')
                                            ->email()
                                            ->required(),

                                        TextInput::make('customer_phone')
                                            ->label('No. Telepon/WA')
                                            ->required(),

                                        Textarea::make('shipping_address')
                                            ->label('Alamat Pengiriman')
                                            ->columnSpanFull()
                                            ->required(),
                                    ])->columns(2),

                                Section::make('Item Pesanan')
                                    ->schema([
                                        Repeater::make('items')
                                            ->relationship('items')
                                            ->schema([
                                                Select::make('product_id')
                                                    ->label('Produk')
                                                    ->options(Product::query()->pluck('name', 'id'))
                                                    ->searchable()
                                                    ->preload()
                                                    ->required()
                                                    ->live()
                                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                        $product = Product::find($state);
                                                        $price = $product ? $product->price : 0;
                                                        $qty = (int) ($get('quantity') ?? 1);

                                                        $set('unit_price', $price);
                                                        $set('subtotal', $price * $qty);

                                                        self::updateGrandTotal($get, $set);
                                                    }),

                                                TextInput::make('quantity')
                                                    ->label('Jumlah Qty')
                                                    ->numeric()
                                                    ->default(1)
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                        $unitPrice = (float) ($get('unit_price') ?? 0);
                                                        $qty = (int) ($state ?? 1);

                                                        $set('subtotal', $unitPrice * $qty);

                                                        self::updateGrandTotal($get, $set);
                                                    }),

                                                TextInput::make('unit_price')
                                                    ->label('Harga Satuan')
                                                    ->numeric()
                                                    ->prefix('Rp')
                                                    ->readOnly()
                                                    ->required(),

                                                TextInput::make('subtotal')
                                                    ->label('Subtotal')
                                                    ->numeric()
                                                    ->prefix('Rp')
                                                    ->readOnly()
                                                    ->required(),
                                            ])
                                            ->columns(4)
                                            ->live()
                                            ->afterStateUpdated(function (Get $get, Set $set) {
                                                self::updateGrandTotal($get, $set);
                                            }),
                                    ]),
                            ])
                            ->columnSpan(2),

                        // Kolom Kanan: Status & Total (Lebar 1)
                        Group::make()
                            ->schema([
                                Section::make('Pembayaran & Status')
                                    ->schema([
                                        Select::make('status')
                                            ->label('Status Pesanan')
                                            ->options([
                                                'pending' => 'Pending',
                                                'processing' => 'Diproses',
                                                'completed' => 'Selesai',
                                                'cancelled' => 'Dibatalkan',
                                            ])
                                            ->default('pending')
                                            ->required(),

                                        Select::make('payment_status')
                                            ->label('Status Pembayaran')
                                            ->options([
                                                'unpaid' => 'Belum Dibayar',
                                                'paid' => 'Lunas',
                                                'failed' => 'Gagal',
                                            ])
                                            ->default('unpaid')
                                            ->required(),

                                        Select::make('payment_method')
                                            ->label('Metode Pembayaran')
                                            ->options([
                                                'bank_transfer' => 'Transfer Bank',
                                                'qris' => 'QRIS',
                                                'cod' => 'Bayar di Tempat (COD)',
                                            ]),

                                        TextInput::make('grand_total')
                                            ->label('Total Bayar')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->required()
                                            ->readOnly()
                                            ->default(0),

                                        Textarea::make('notes')
                                            ->label('Catatan Pesanan'),
                                    ]),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public static function updateGrandTotal(Get $get, Set $set): void
    {
        // 1. Ambil data items dari root form (menggunakan path relatif ke atas)
        $items = $get('../../items') ?? $get('items') ?? [];
        $grandTotal = 0;

        // 2. Tentukan subtotal manual jika item baru diubah tapi state repeater belum ter-refresh
        foreach ($items as $item) {
            if (isset($item['subtotal'])) {
                $grandTotal += (float) $item['subtotal'];
            } elseif (isset($item['unit_price'], $item['quantity'])) {
                $grandTotal += ((float) $item['unit_price'] * (int) $item['quantity']);
            }
        }

        // 3. Set grand_total baik di scope child maupun root
        $set('../../grand_total', $grandTotal);
        $set('grand_total', $grandTotal);
    }
}
