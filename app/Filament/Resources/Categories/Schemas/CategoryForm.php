<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
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
                                fn (string $operation, $state, $set) =>
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Select::make('parent_id')
                            ->label('Induk Kategori')
                            ->relationship('parent', 'name', modifyQueryUsing: function (Builder $query, $record) {
                                // Mencegah kategori memilih dirinya sendiri sebagai Induk
                                if ($record) {
                                    $query->where('id', '!=', $record->id);
                                }
                                // Hanya tampilkan Kategori Utama (Level 1) sebagai pilihan Induk
                                $query->whereNull('parent_id');
                            })
                            ->searchable()
                            ->placeholder('Pilih Induk (Kosongkan jika Kategori Utama)')
                            ->nullable()
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                // Otomatis set level berdasarkan ada/tidaknya parent_id
                                if ($state) {
                                    $set('level', 2);
                                } else {
                                    $set('level', 1);
                                }
                            }),

                        TextInput::make('level')
                            ->label('Level Kategori')
                            ->numeric()
                            ->default(1)
                            ->readOnly()
                            ->dehydrated(),

                        TextInput::make('position')
                            ->label('Urutan Position')
                            ->numeric()
                            ->default(1),

                        Toggle::make('status')
                            ->label('Status Aktif')
                            ->default(true)
                            ->inline(false),

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
                    ])->columns(2),
            ]);
    }
}