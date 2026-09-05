<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingRate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pelanggan & Pengiriman')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->label('Pelanggan')
                            ->options(Customer::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $customer = Customer::find($state);
                                if ($customer && isset($customer->address)) {
                                    $set('shipping_address', $customer->address);
                                }
                            }),

                        Forms\Components\Select::make('shipping_rate_id')
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

                        Forms\Components\Select::make('status')
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

                        Forms\Components\Textarea::make('shipping_address')
                            ->label('Alamat Lengkap Pengiriman')
                            ->columnSpanFull(),
                    ])->columns(3),

                Forms\Components\Section::make('Detail Item Pesanan')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->label('Daftar Produk')
                            ->relationship('items')
                            ->schema([
                                Forms\Components\Select::make('product_id')
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

                                Forms\Components\TextInput::make('quantity')
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

                                Forms\Components\TextInput::make('unit_price')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->dehydrated()
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('total_price')
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
                    ]),

                Forms\Components\Section::make('Rincian Pembayaran')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal Produk')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('shipping_cost')
                            ->label('Ongkos Kirim')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Bayar')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly()
                            ->dehydrated(),
                    ])->columns(3),
            ]);
    }

    // ... sisanya (table, getPages, dll) biarkan tetap seperti semula
}