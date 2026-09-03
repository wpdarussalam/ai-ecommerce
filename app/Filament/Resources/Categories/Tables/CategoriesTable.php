<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable // Ubah dari CategoryTable menjadi CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('parent.name')
                    ->label('Induk Kategori')
                    ->placeholder('Utama (Level 1)')
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),

                TextColumn::make('level')
                    ->label('Level')
                    ->sortable(),

                IconColumn::make('status')
                    ->label('Status')
                    ->boolean(),
            ])
            ->filters([
                //
            ]);
    }
}
