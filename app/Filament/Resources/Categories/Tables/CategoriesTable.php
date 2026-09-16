<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Category;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
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
            ])
            ->actions([
                // Tombol Pintas ke Halaman Produk Ter-filter
                Action::make('lihat_produk')
                    ->label('Lihat Produk')
                    ->icon('heroicon-o-shopping-bag')
                    ->color('info')
                    ->url(fn (Category $record): string => ProductResource::getUrl('index', [
                        'tableFilters' => [
                            'category_id' => [
                                'value' => $record->id,
                            ],
                        ],
                    ])),

                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}