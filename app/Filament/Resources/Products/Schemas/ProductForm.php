<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        // Kolom Kiri (Lebar 2)
                        Group::make()
                            ->schema([
                                Section::make('Informasi Utama')
                                    ->schema([
                                        Select::make('category_id')
                                            ->relationship('category', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->label('Kategori'),

                                        TextInput::make('sku')
                                            ->label('SKU')
                                            ->required()
                                            ->unique(ignoreRecord: true),

                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->label('Nama Produk')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(
                                                fn (string $operation, $state, $set) =>
                                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                                            ),

                                        TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true)
                                            ->label('Slug'),

                                        TextInput::make('short_description')
                                            ->maxLength(255)
                                            ->label('Deskripsi Singkat')
                                            ->columnSpanFull(),

                                        MarkdownEditor::make('description')
                                            ->columnSpanFull()
                                            ->label('Deskripsi Lengkap'),
                                    ])->columns(2),

                                Section::make('Harga & Inventaris')
                                    ->schema([
                                        TextInput::make('price')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->required()
                                            ->label('Harga Utama'),

                                        TextInput::make('old_price')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->label('Harga Coret (Lama)'),

                                        TextInput::make('stock')
                                            ->numeric()
                                            ->default(0)
                                            ->required()
                                            ->label('Jumlah Stok'),
                                    ])->columns(3),

                                Section::make('Galeri Gambar Produk')
                                    ->schema([
                                        Repeater::make('images')
                                            ->relationship('images')
                                            ->schema([
                                                FileUpload::make('image')
                                                    ->label('Foto Produk')
                                                    ->image()
                                                    ->disk('public')
                                                    ->directory('products')
                                                    ->visibility('public')
                                                    ->columnSpanFull(),

                                                TextInput::make('position')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->label('Urutan Tampil'),

                                                Toggle::make('is_primary')
                                                    ->label('Gambar Utama Galeri'),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(0)
                                            ->addActionLabel('Tambah Foto Galeri'),
                                    ]),
                            ])
                            ->columnSpan(2),

                        // Kolom Kanan (Lebar 1)
                        Group::make()
                            ->schema([
                                Section::make('Gambar Cover')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->image()
                                            ->disk('public')
                                            ->directory('products/thumbnails')
                                            ->visibility('public')
                                            ->label('Thumbnail Utama'),
                                    ]),

                                Section::make('Status & Visibilitas')
                                    ->schema([
                                        Toggle::make('status')
                                            ->default(true)
                                            ->label('Status Aktif'),

                                        Toggle::make('featured')
                                            ->default(false)
                                            ->label('Produk Unggulan (Featured)'),
                                    ]),

                                Section::make('SEO Optimization')
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->maxLength(255)
                                            ->label('Meta Title'),

                                        TextInput::make('meta_description')
                                            ->maxLength(255)
                                            ->label('Meta Description'),

                                        TextInput::make('meta_keywords')
                                            ->maxLength(255)
                                            ->label('Meta Keywords'),
                                    ])
                                    ->collapsible(),
                                Section::make('SEO Optimization')
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->maxLength(255)
                                            ->label('Meta Title'),

                                        TextInput::make('meta_description')
                                            ->maxLength(255)
                                            ->label('Meta Description'),

                                        TextInput::make('meta_keywords')
                                            ->maxLength(255)
                                            ->label('Meta Keywords'),
                                    ])
                                    ->collapsible(),
                            ]),
                    ]),
            ]); // <-- Pastikan diakhiri dengan titik koma (;), bukan koma (,)
    }
}                            
                    
            

