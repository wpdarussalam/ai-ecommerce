<?php

namespace App\Filament\Resources\ShippingRates;

use App\Filament\Resources\ShippingRates\Pages\CreateShippingRate;
use App\Filament\Resources\ShippingRates\Pages\EditShippingRate;
use App\Filament\Resources\ShippingRates\Pages\ListShippingRates;
use App\Filament\Resources\ShippingRates\Schemas\ShippingRateForm;
use App\Filament\Resources\ShippingRates\Tables\ShippingRatesTable;
use App\Models\ShippingRate;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ShippingRateResource extends Resource
{
    protected static ?string $model = ShippingRate::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Shop';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-truck';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Ongkos Kirim';

    public static function form(Schema $schema): Schema
    {
        return ShippingRateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShippingRatesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShippingRates::route('/'),
            'create' => CreateShippingRate::route('/create'),
            'edit' => EditShippingRate::route('/{record}/edit'),
        ];
    }
}