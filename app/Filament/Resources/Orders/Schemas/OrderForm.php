<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ShippingRate;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('customer_id')
                    ->label('Pelanggan')
                    ->options(Customer::where('is_active', true)->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('shipping_rate_id')
                    ->label('Kota Tujuan / Ongkir')
                    ->options(ShippingRate::where('is_active', true)->pluck('city_name', 'id'))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $rate = ShippingRate::find($state);
                        $shippingCost = $rate ? $rate->cost : 0;
                        $set('shipping_cost', $shippingCost);

                        $subtotal = $get('subtotal') ?? 0;
                        $set('total_amount', $subtotal + $shippingCost);
                    })
                    ->required(),

                Select::make('status')
                    ->label('Status Pesanan')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->default('pending')
                    ->required(),

                Textarea::make('shipping_address')
                    ->label('Alamat Lengkap Pengiriman')
                    ->columnSpanFull(),

                Repeater::make('items')
                    ->label('Daftar Produk Pesanan')
                    ->relationship('items')
                    ->schema([
                        Select::make('product_id')
                            ->label('Produk')
                            ->options(Product::pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $product = Product::find($state);
                                if ($product) {
                                    $price = $product->price;
                                    $qty = $get('quantity') ?? 1;
                                    $set('unit_price', $price);
                                    $set('total_price', $price * $qty);
                                }
                            })
                            ->required()
                            ->columnSpan(5),

                        TextInput::make('quantity')
                            ->label('Jumlah')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $unitPrice = $get('unit_price') ?? 0;
                                $set('total_price', $unitPrice * $state);
                            })
                            ->required()
                            ->columnSpan(2),

                        TextInput::make('unit_price')
                            ->label('Harga Satuan')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly()
                            ->dehydrated()
                            ->columnSpan(2),

                        TextInput::make('total_price')
                            ->label('Total')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly()
                            ->dehydrated()
                            ->columnSpan(3),
                    ])
                    ->columns(12)
                    ->columnSpanFull()
                    ->live()
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        $items = $get('items') ?? [];
                        $subtotal = 0;
                        foreach ($items as $item) {
                            $subtotal += ($item['total_price'] ?? 0);
                        }
                        $set('subtotal', $subtotal);
                        $shippingCost = $get('shipping_cost') ?? 0;
                        $set('total_amount', $subtotal + $shippingCost);
                    }),

                TextInput::make('subtotal')
                    ->label('Subtotal Produk')
                    ->numeric()
                    ->prefix('Rp')
                    ->readOnly()
                    ->dehydrated(),

                TextInput::make('shipping_cost')
                    ->label('Ongkos Kirim')
                    ->numeric()
                    ->prefix('Rp')
                    ->readOnly()
                    ->dehydrated(),

                TextInput::make('total_amount')
                    ->label('Total Bayar')
                    ->numeric()
                    ->prefix('Rp')
                    ->readOnly()
                    ->dehydrated(),
            ]);
    }
}