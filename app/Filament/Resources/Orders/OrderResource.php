<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static string|\UnitEnum|null $navigationGroup = 'Shop';

    public static function form(Schema $form): Schema
    {
        return $form
            ->components([
                \Filament\Schemas\Components\Group::make()
                    ->schema([
                        \Filament\Schemas\Components\Section::make('Informasi Pelanggan')
                            ->schema([
                                Forms\Components\TextInput::make('order_number')
                                    ->default('ORD-' . strtoupper(\Illuminate\Support\Str::random(10)))
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                                Forms\Components\TextInput::make('customer_name')
                                    ->required(),
                                Forms\Components\TextInput::make('customer_email')
                                    ->email()
                                    ->required(),
                                Forms\Components\TextInput::make('customer_phone')
                                    ->tel()
                                    ->required(),
                                Forms\Components\Textarea::make('shipping_address')
                                    ->required()
                                    ->columnSpanFull(),
                            ])->columns(2),

                        \Filament\Schemas\Components\Section::make('Detail Item Pesanan')
                            ->schema([
                                Forms\Components\Repeater::make('items')
                                    ->relationship('items')
                                    ->schema([
                                        Forms\Components\Select::make('product_id')
                                            ->relationship('product', 'name')
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function ($state, $set, $get) {
                                                $price = \App\Models\Product::find($state)?->price ?? 0;
                                                $quantity = (int) ($get('quantity') ?? 1);

                                                $set('unit_price', $price);
                                                $set('subtotal', $price * $quantity);
                                            }),

                                        Forms\Components\TextInput::make('quantity')
                                            ->numeric()
                                            ->default(1)
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function ($state, $set, $get) {
                                                $unitPrice = (float) ($get('unit_price') ?? 0);
                                                $quantity = (int) ($state ?? 1);

                                                $set('subtotal', $unitPrice * $quantity);
                                            }),

                                        Forms\Components\TextInput::make('unit_price')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->required()
                                            ->dehydrated(),

                                        Forms\Components\TextInput::make('subtotal')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->required()
                                            ->dehydrated(),
                                    ])
                                    ->columns(4)
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(2),

                \Filament\Schemas\Components\Group::make()
                    ->schema([
                        \Filament\Schemas\Components\Section::make('Status & Pembayaran')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'processing' => 'Processing',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->default('pending')
                                    ->required(),
                                Forms\Components\TextInput::make('grand_total')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),
                            ]),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('grand_total')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                
                Action::make('pdf')
                    ->label('Invoice')
                    ->color('success')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function (Order $record) {
                        $record->load('items.product');
                        
                        $pdf = Pdf::loadView('pdf.invoice', ['order' => $record]);
                        
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            "Invoice-{$record->order_number}.pdf"
                        );
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}