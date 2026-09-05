<?php

namespace App\Filament\Resources\ShippingRates\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ShippingRateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('city_name')
                    ->label('Nama Kota/Kabupaten')
                    ->required()
                    ->maxLength(255),

                TextInput::make('province')
                    ->label('Provinsi')
                    ->maxLength(255),

                TextInput::make('cost')
                    ->label('Biaya Pengiriman')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),
            ]);
    }
}