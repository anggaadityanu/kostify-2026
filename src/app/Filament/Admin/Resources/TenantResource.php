<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TenantResource\Pages;
use App\Models\Tenant;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Tenant';
    protected static ?string $modelLabel = 'Tenant';
    protected static ?string $navigationGroup = 'Manajemen Tenant';
    protected static ?int $navigationSort = 1;

    /**
     * Form Create & Edit tenant
     * Logika: pilih user → isi data KTP & kontak darurat
     */
    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Akun User')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->label('User')
                        ->options(function () {
                            return User::whereHas('roles', fn ($q) => $q->where('name', 'tenant')
                            )
                                ->whereDoesntHave('tenant')
                                ->pluck('name', 'id');
                        })
                        ->required()
                        ->helperText('Pilih user dengan role tenant'),
                ]),

            Forms\Components\Section::make('Data Pribadi')
                ->schema([
                    Forms\Components\TextInput::make('nik')
                        ->label('NIK (Nomor KTP)')
                        ->placeholder('3271234567890001')
                        ->maxLength(16)
                        ->minLength(16)
                        ->required()
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('phone')
                        ->label('Nomor HP')
                        ->placeholder('08123456789')
                        ->tel()
                        ->required(),

                    Forms\Components\Select::make('gender')
                        ->label('Jenis Kelamin')
                        ->options([
                            'male' => 'Laki-laki',
                            'female' => 'Perempuan',
                        ])
                        ->required(),

                    Forms\Components\TextInput::make('occupation')
                        ->label('Pekerjaan')
                        ->placeholder('Mahasiswa, Karyawan, dll'),

                    Forms\Components\Textarea::make('address_origin')
                        ->label('Alamat Asal')
                        ->placeholder('Alamat KTP')
                        ->rows(2)
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('ktp_file')
                        ->label('Foto KTP')
                        ->image()
                        ->disk('public')
                        ->directory('tenant-documents/ktp')
                        ->visibility('public')
                        ->openable()
                        ->downloadable(),

                    Forms\Components\FileUpload::make('kk_file')
                        ->label('Foto KK')
                        ->image()
                        ->disk('public')
                        ->directory('tenant-documents/kk')
                        ->visibility('public')
                        ->openable()
                        ->downloadable(),
                ])->columns(2),

            Forms\Components\Section::make('Kontak Darurat')
                ->schema([
                    Forms\Components\TextInput::make('emergency_contact_name')
                        ->label('Nama Kontak Darurat')
                        ->placeholder('Nama orang tua/kerabat')
                        ->required(),

                    Forms\Components\TextInput::make('emergency_contact_phone')
                        ->label('No. HP Kontak Darurat')
                        ->placeholder('08123456789')
                        ->tel()
                        ->required(),

                    Forms\Components\TextInput::make('emergency_contact_relation')
                        ->label('Hubungan')
                        ->placeholder('Orang Tua, Kakak, dll')
                        ->required(),
                ])->columns(3),
        ]);
    }

    /**
     * Table list tenant
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('No. HP')
                    ->searchable(),

                Tables\Columns\TextColumn::make('gender')
                    ->label('Kelamin')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'male' => 'info',
                        'female' => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'male' => 'Laki-laki',
                        'female' => 'Perempuan',
                    }),

                Tables\Columns\TextColumn::make('occupation')
                    ->label('Pekerjaan')
                    ->searchable(),

                Tables\Columns\ImageColumn::make('ktp_file')
                    ->label('KTP')
                    ->disk('public')
                    ->toggleable(),

                Tables\Columns\ImageColumn::make('kk_file')
                    ->label('KK')
                    ->disk('public')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'male' => 'Laki-laki',
                        'female' => 'Perempuan',
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
            'view' => Pages\ViewTenant::route('/{record}'),
        ];
    }
}
