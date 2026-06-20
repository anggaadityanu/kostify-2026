<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RoomResource\Pages;
use App\Models\Room;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Kamar';
    protected static ?string $modelLabel = 'Kamar';
    protected static ?string $navigationGroup = 'Manajemen Properti';
    protected static ?int $navigationSort = 2;

    /**
     * Form Create & Edit kamar
     * Logika: pilih properti dulu → isi detail kamar
     */
    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Informasi Kamar')
                ->schema([
                    Forms\Components\Select::make('property_id')
                    ->label('Properti')
                    ->options(function () {
                        if (Auth::user()->isOwner()) {
                            return Property::where('user_id', Auth::id())
                                ->pluck('name', 'id');
                        }
                        return Property::pluck('name', 'id');
                    })
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($state, $set) => $set('room_number', null)),

                    Forms\Components\TextInput::make('room_number')
                        ->label('Nomor Kamar')
                        ->placeholder('A1, B2, 101')
                        ->required()
                        ->maxLength(50),

                    Forms\Components\Select::make('type')
                        ->label('Tipe Kamar')
                        ->options([
                            'standard' => 'Standard',
                            'deluxe'   => 'Deluxe',
                            'vip'      => 'VIP',
                        ])
                        ->required(),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'available'   => 'Tersedia',
                            'occupied'    => 'Terisi',
                            'maintenance' => 'Maintenance',
                        ])
                        ->default('available')
                        ->required(),

                    Forms\Components\TextInput::make('capacity')
                        ->label('Kapasitas (orang)')
                        ->numeric()
                        ->default(1)
                        ->minValue(1)
                        ->required(),

                    Forms\Components\TextInput::make('size')
                        ->label('Ukuran (m²)')
                        ->numeric()
                        ->placeholder('3x4'),

                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Harga Sewa')
                ->schema([
                    Forms\Components\TextInput::make('price_monthly')
                        ->label('Harga Per Bulan (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->placeholder('1500000')
                        ->required(),

                    Forms\Components\TextInput::make('price_yearly')
                        ->label('Harga Per Tahun (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->placeholder('15000000')
                        ->helperText('Opsional, isi jika ada diskon tahunan'),
                ])->columns(2),

            Forms\Components\Section::make('Fasilitas Kamar')
                ->schema([
                    Forms\Components\CheckboxList::make('facilities')
                        ->label('Fasilitas Kamar')
                        ->options([
                            'ac'            => '❄️ AC',
                            'wifi'          => '📶 WiFi',
                            'bathroom'      => '🚿 Kamar Mandi Dalam',
                            'wardrobe'      => '🪞 Lemari',
                            'desk'          => '🪑 Meja Belajar',
                            'bed'           => '🛏️ Kasur',
                            'tv'            => '📺 TV',
                            'window'        => '🪟 Jendela',
                            'balcony'       => '🌿 Balkon',
                            'water_heater'  => '🔥 Water Heater',
                        ])
                        ->columns(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    /**
     * Table list kamar
     * Logika: tampilkan kamar + properti + status dengan warna
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('property.name')
                    ->label('Properti')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('room_number')
                    ->label('No. Kamar')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'standard' => 'gray',
                        'deluxe'   => 'info',
                        'vip'      => 'warning',
                    }),

                Tables\Columns\TextColumn::make('price_monthly')
                    ->label('Harga/Bulan')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('capacity')
                    ->label('Kapasitas')
                    ->suffix(' orang'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'available',
                        'danger'  => 'occupied',
                        'warning' => 'maintenance',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'available'   => 'Tersedia',
                        'occupied'    => 'Terisi',
                        'maintenance' => 'Maintenance',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('property_id')
                    ->label('Properti')
                    ->relationship('property', 'name'),

                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'standard' => 'Standard',
                        'deluxe'   => 'Deluxe',
                        'vip'      => 'VIP',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'available'   => 'Tersedia',
                        'occupied'    => 'Terisi',
                        'maintenance' => 'Maintenance',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Scope: Owner hanya lihat kamar dari properti miliknya
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()->isOwner()) {
            return $query->whereHas('property', function ($q) {
                $q->where('user_id', Auth::id());
            });
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
            'index'  => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit'   => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}