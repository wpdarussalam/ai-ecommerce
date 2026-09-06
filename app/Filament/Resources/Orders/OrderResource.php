<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingRate;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// Actions
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static \UnitEnum|string|null $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pelanggan & Pengiriman')
                    ->schema([
                        Select::make('customer_id')
                            ->label('Pelanggan')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->required(),
                                TextInput::make('email')
                                    ->email()
                                    ->required(),
                                TextInput::make('phone')
                                    ->label('No. HP / WA'),
                                Textarea::make('address')
                                    ->label('Alamat'),
                            ])
                            ->afterStateUpdated(function ($state, $set) {
                                $customer = Customer::find($state);
                                if ($customer && isset($customer->address)) {
                                    $set('shipping_address', $customer->address);
                                }
                            }),

                        Select::make('shipping_rate_id')
                            ->label('Kota Tujuan / Ongkir')
                            ->options(ShippingRate::where('is_active', true)->pluck('city_name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $rate = ShippingRate::find($state);
                                $shippingCost = $rate ? (float) $rate->cost : 0;
                                $set('shipping_cost', $shippingCost);

                                self::recalculateTotals($get, $set);
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
                    ])->columns(3),

                Section::make('Detail Item Pesanan')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                // 1. Pilih Produk
                                Select::make('product_id')
                                    ->label('Produk')
                                    ->options(Product::pluck('name', 'id'))
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if ($state) {
                                            $product = Product::find($state);
                                            $price = $product?->price ?? 0;
                                            $quantity = (int) ($get('quantity') ?? 1);

                                            $set('unit_price', $price);
                                            $set('subtotal', $price * $quantity);
                                        }
                                    }),

                                // 2. Harga Satuan (Bisa Edit Manual)
                                TextInput::make('unit_price')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $quantity = (int) ($get('quantity') ?? 1);
                                        $set('subtotal', (float) $state * $quantity);
                                    }),

                                // 3. Qty
                                TextInput::make('quantity')
                                    ->label('Qty')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $price = (float) ($get('unit_price') ?? 0);
                                        $set('subtotal', (int) $state * $price);
                                    }),

                                // 4. Subtotal Item
                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly(),
                            ])
                            ->columns(4)
                            ->live()
                            ->afterStateUpdated(function ($get, $set) {
                                self::recalculateTotals($get, $set);
                            }),
                    ]),

                Section::make('Ringkasan Pembayaran')
                    ->schema([
                        TextInput::make('subtotal')
                            ->label('Subtotal Produk')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly(),

                        TextInput::make('shipping_cost')
                            ->label('Ongkos Kirim')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly(),

                        TextInput::make('total_amount')
                            ->label('Total Bayar')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Order')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Bayar')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Action::make('print')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn (Order $record): string => route('orders.print', $record))
                    ->openUrlInNewTab(),

                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    /**
     * Hitung ulang Subtotal Produk & Total Bayar
     */
    public static function recalculateTotals($get, $set): void
    {
        $items = $get('items') ?? [];
        $subtotal = 0;

        foreach ($items as $item) {
            $unitPrice = (float) ($item['unit_price'] ?? 0);
            $quantity = (int) ($item['quantity'] ?? 1);
            $subtotal += ($unitPrice * $quantity);
        }

        $set('subtotal', $subtotal);

        $shippingCost = (float) ($get('shipping_cost') ?? 0);

        $set('total_amount', $subtotal + $shippingCost);
    }
}