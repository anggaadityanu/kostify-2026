<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PropertyResource\Pages;
use App\Models\Property;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Properti';
    protected static ?string $modelLabel = 'Properti';
    protected static ?string $navigationGroup = 'Manajemen Properti';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Informasi Dasar')
                ->schema([
                    Forms\Components\Select::make('user_id')
                    ->label('Pemilik')
                    ->options(function () {
                        return \App\Models\User::whereHas('roles', function ($q) {
                            $q->where('name', 'owner');
                        })->pluck('name', 'id')->toArray();
                    })
                    ->required()
                    ->visible(fn () => Auth::check() && Auth::user()->isSuperAdmin()),

                    Forms\Components\TextInput::make('name')
                        ->label('Nama Properti')
                        ->placeholder('Kos Putri Melati')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Select::make('type')
                        ->label('Tipe Properti')
                        ->options([
                            'kos_putra'  => 'Kos Putra',
                            'kos_putri'  => 'Kos Putri',
                            'kos_campur' => 'Kos Campur',
                            'kontrakan'  => 'Kontrakan',
                        ])
                        ->required(),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'active'   => 'Aktif',
                            'inactive' => 'Tidak Aktif',
                        ])
                        ->default('active')
                        ->required(),

                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->placeholder('Deskripsi lengkap properti...')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Alamat & Lokasi')
                ->schema([
                    Forms\Components\Textarea::make('address')
                        ->label('Alamat Lengkap')
                        ->required()
                        ->rows(2)
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('province')
                        ->label('Provinsi')
                        ->placeholder('Jawa Barat'),

                    Forms\Components\TextInput::make('city')
                        ->label('Kota/Kabupaten')
                        ->placeholder('Bandung'),

                    Forms\Components\TextInput::make('district')
                        ->label('Kecamatan')
                        ->placeholder('Coblong'),

                    Forms\Components\TextInput::make('google_maps_url')
                        ->label('Link Google Maps')
                        ->placeholder('https://maps.google.com/...')
                        ->url()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('latitude')
                        ->label('Latitude')
                        ->placeholder('-6.914744')
                        ->numeric(),

                    Forms\Components\TextInput::make('longitude')
                        ->label('Longitude')
                        ->placeholder('107.608146')
                        ->numeric(),
                ])->columns(2),

            Forms\Components\Section::make('Foto Properti')
                ->schema([
                    Forms\Components\FileUpload::make('photos')
                        ->label('Foto Properti')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->maxFiles(5)
                        ->disk('public')
                        ->directory('properties')
                        ->imageEditor()
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Fasilitas')
                ->schema([
                    Forms\Components\CheckboxList::make('facilities')
                        ->label('Fasilitas Tersedia')
                        ->options([
                            'wifi'      => 'WiFi',
                            'ac'        => 'AC',
                            'parking'   => 'Parkir',
                            'security'  => 'Keamanan 24 Jam',
                            'laundry'   => 'Laundry',
                            'kitchen'   => 'Dapur Bersama',
                            'tv'        => 'TV',
                            'water'     => 'Air Bersih',
                            'electric'  => 'Listrik',
                            'bathroom'  => 'Kamar Mandi Dalam',
                        ])
                        ->columns(3)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Preview Lokasi')
                ->schema([
                    Forms\Components\Placeholder::make('map_preview')
                        ->label('Peta Lokasi')
                        ->content(function ($record): \Illuminate\Support\HtmlString {
                            if (!$record || !$record->latitude) {
                                return new \Illuminate\Support\HtmlString(
                                    '<p class="text-gray-500">Simpan properti dengan alamat lengkap untuk melihat peta.</p>'
                                );
                            }

                            $embedUrl = app(\App\Services\MapService::class)
                                ->getEmbedUrl($record->latitude, $record->longitude);

                            $directionsUrl = app(\App\Services\MapService::class)
                                ->getDirectionsUrl($record->latitude, $record->longitude);

                            return new \Illuminate\Support\HtmlString("
                                <div>
                                    <iframe
                                        src='{$embedUrl}'
                                        width='100%'
                                        height='300'
                                        style='border:0; border-radius: 8px;'
                                        allowfullscreen
                                        loading='lazy'>
                                    </iframe>
                                    <a href='{$directionsUrl}'
                                        target='_blank'
                                        class='mt-2 inline-flex items-center text-sm text-blue-600 hover:underline'>
                                        Buka di Google Maps
                                    </a>
                                </div>
                            ");
                        })
                        ->columnSpanFull(),
                ])
                ->visibleOn('edit'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photos.0')
                    ->label('Foto')
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl(asset('makaan/img/property-1.jpg')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Properti')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'kos_putra'  => 'info',
                        'kos_putri'  => 'success',
                        'kos_campur' => 'warning',
                        'kontrakan'  => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'kos_putra'  => 'Kos Putra',
                        'kos_putri'  => 'Kos Putri',
                        'kos_campur' => 'Kos Campur',
                        'kontrakan'  => 'Kontrakan',
                    }),

                Tables\Columns\TextColumn::make('city')
                    ->label('Kota')
                    ->searchable(),

                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Pemilik')
                    ->searchable()
                    ->visible(fn () => Auth::user()->isSuperAdmin()),

                Tables\Columns\TextColumn::make('rooms_count')
                    ->label('Total Kamar')
                    ->counts('rooms')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'danger'  => 'inactive',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active'   => 'Aktif',
                        'inactive' => 'Tidak Aktif',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe Properti')
                    ->options([
                        'kos_putra'  => 'Kos Putra',
                        'kos_putri'  => 'Kos Putri',
                        'kos_campur' => 'Kos Campur',
                        'kontrakan'  => 'Kontrakan',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active'   => 'Aktif',
                        'inactive' => 'Tidak Aktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()->isOwner()) {
            return $query->where('user_id', Auth::id());
        }

        return $query;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit'   => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}