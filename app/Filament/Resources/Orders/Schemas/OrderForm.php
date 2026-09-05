<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pelanggan & Pesanan')
                    ->schema([
                        TextInput::make('order_number')
                            ->label('Nomor Pesanan')
                            ->default('ORD-' . strtoupper(uniqid()))
                            ->required()
                            ->readOnly(),

                        Select::make('user_id')
                            ->label('Akun User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->nullable(),

                        TextInput::make('customer_name')
                            ->label('Nama Pelanggan')
                            ->required(),

                        TextInput::make('customer_email')
                            ->label('Email Pelanggan')
                            ->email()
                            ->required(),

                        TextInput::make('customer_phone')
                            ->label('Telepon Pelanggan')
                            ->tel()
                            ->required(),

                        Select::make('status')
                            ->label('Status Pesanan')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->required(),

                        Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'unpaid' => 'Unpaid',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ])
                            ->default('unpaid')
                            ->required(),

                        TextInput::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->default('bank_transfer'),

                        Textarea::make('shipping_address')
                            ->label('Alamat Pengiriman')
                            ->required()
                            ->columnSpanFull(),

                        Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull(),
                    ])->columns(3),

                Section::make('Item Pesanan')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Select::make('product_id')
                                    ->label('Produk')
                                    ->options(Product::where('status', true)->pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $product = Product::find($state);
                                        if ($product) {
                                            $set('unit_price', $product->price);
                                            $quantity = (int) ($get('quantity') ?? 1);
                                            $set('total_price', $product->price * $quantity);
                                        } else {
                                            $set('unit_price', 0);
                                            $set('total_price', 0);
                                        }
                                        self::updateGrandTotal($get, $set);
                                    }),

                                TextInput::make('unit_price')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->readOnly(),

                                TextInput::make('quantity')
                                    ->label('Jumlah (Qty)')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $unitPrice = (float) ($get('unit_price') ?? 0);
                                        $qty = (int) ($state ?? 1);
                                        $set('total_price', $unitPrice * $qty);
                                        self::updateGrandTotal($get, $set);
                                    }),

                                TextInput::make('total_price')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->readOnly(),
                            ])
                            ->columns(4)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateGrandTotal($get, $set);
                            })
                            ->live(),

                        TextInput::make('grand_total')
                            ->label('Grand Total')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->readOnly(),
                    ]),
            ]);
    }

    protected static function updateGrandTotal(Get $get, Set $set): void
    {
        $items = $get('items') ?? [];
        $total = 0;

        foreach ($items as $item) {
            $total += (float) ($item['total_price'] ?? 0);
        }

        $set('grand_total', $total);
    }
}