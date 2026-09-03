<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kategori')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn(string $operation, $state, $set) =>
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Select::make('parent_id')
                            ->label('Induk Kategori')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->placeholder('Pilih Induk (Kosongkan jika Kategori Utama)')
                            ->nullable(),

                        TextInput::make('position')
                            ->label('Urutan Position')
                            ->numeric()
                            ->default(1),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),

                        FileUpload::make('image')
                            ->label('Gambar Kategori')
                            ->image()
                            ->disk('public')
                            ->directory('categories')
                            ->visibility('public')
                            ->columnSpanFull(),

                        Toggle::make('status')
                            ->label('Status Aktif')
                            ->default(true),
                    ])->columns(2),
            ]);
    }
}
