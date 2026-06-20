<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ContractResource\Pages;
use App\Models\Contract;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Kontrak';
    protected static ?string $modelLabel = 'Kontrak';
    protected static ?string $navigationGroup = 'Keuangan';
    protected static ?int $navigationSort = 2;

    /**
     * Form Create & Edit kontrak
     * Logika: pilih booking aktif → isi detail kontrak
     * → end_date otomatis dari start_date + durasi booking
     */
    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Informasi Kontrak')
                ->schema([
                    Forms\Components\TextInput::make('contract_number')
                        ->label('Nomor Kontrak')
                        ->disabled()
                        ->placeholder('Auto-generate'),

                    Forms\Components\Select::make('booking_id')
                        ->label('Booking')
                        ->options(fn () => Booking::with('tenant.user', 'room')
                            ->where('status', 'active')
                            ->whereDoesntHave('contract')
                            ->get()
                            ->mapWithKeys(fn ($booking) => [
                                $booking->id => $booking->booking_code . ' - ' .
                                    $booking->tenant->user->name . ' (' .
                                    $booking->room->room_number . ')'
                            ])
                        )
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            if ($state) {
                                $booking = Booking::with('room')->find($state);
                                // Auto-fill dari data booking
                                $set('start_date', $booking->check_in_date);
                                $set('monthly_rent', $booking->room->price_monthly);
                                // Hitung end_date dari durasi booking
                                $endDate = $booking->check_in_date
                                    ->addMonths($booking->duration_months);
                                $set('end_date', $endDate->format('Y-m-d'));
                            }
                        }),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'active'     => 'Aktif',
                            'expired'    => 'Kadaluarsa',
                            'terminated' => 'Diakhiri',
                        ])
                        ->default('active')
                        ->required(),
                ])->columns(2),

            Forms\Components\Section::make('Detail Kontrak')
                ->schema([
                    Forms\Components\DatePicker::make('start_date')
                        ->label('Tanggal Mulai')
                        ->required(),

                    Forms\Components\DatePicker::make('end_date')
                        ->label('Tanggal Selesai')
                        ->required(),

                    Forms\Components\TextInput::make('monthly_rent')
                        ->label('Harga Sewa/Bulan (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),

                    Forms\Components\TextInput::make('deposit_amount')
                        ->label('Deposit (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0)
                        ->helperText('Uang jaminan yang dikembalikan saat keluar'),
                ])->columns(2),

            Forms\Components\Section::make('Syarat & Ketentuan')
                ->schema([
                    Forms\Components\Textarea::make('terms')
                        ->label('Syarat & Ketentuan')
                        ->rows(6)
                        ->default(self::defaultTerms())
                        ->columnSpanFull(),
                ]),
        ]);
    }

    /**
     * Default syarat & ketentuan kontrak
     */
    protected static function defaultTerms(): string
    {
        return "SYARAT DAN KETENTUAN SEWA\n\n" .
               "1. Penyewa wajib membayar sewa setiap tanggal 1 setiap bulannya.\n" .
               "2. Keterlambatan pembayaran dikenakan denda Rp 5.000/hari.\n" .
               "3. Penyewa wajib menjaga kebersihan dan ketertiban.\n" .
               "4. Dilarang membawa tamu menginap tanpa izin pengelola.\n" .
               "5. Kerusakan fasilitas akibat kelalaian penyewa menjadi tanggung jawab penyewa.\n" .
               "6. Pemberitahuan keluar minimal 30 hari sebelumnya.\n" .
               "7. Deposit dikembalikan setelah pemeriksaan kamar saat keluar.";
    }

    /**
     * Table list kontrak
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('contract_number')
                    ->label('No. Kontrak')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('booking.tenant.user.name')
                    ->label('Tenant')
                    ->searchable(),

                Tables\Columns\TextColumn::make('booking.room.room_number')
                    ->label('Kamar')
                    ->searchable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date('d M Y'),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date('d M Y')
                    ->color(fn ($record) => $record->isExpiringSoon() ? 'warning' : null),

                Tables\Columns\TextColumn::make('monthly_rent')
                    ->label('Sewa/Bulan')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('deposit_amount')
                    ->label('Deposit')
                    ->money('IDR'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'gray'    => 'expired',
                        'danger'  => 'terminated',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active'     => 'Aktif',
                        'expired'    => 'Kadaluarsa',
                        'terminated' => 'Diakhiri',
                    }),

                Tables\Columns\TextColumn::make('days_remaining')
                    ->label('Sisa Hari')
                    ->getStateUsing(fn ($record) => $record->daysRemaining() . ' hari')
                    ->color(fn ($record) => $record->daysRemaining() <= 30 ? 'warning' : 'success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active'     => 'Aktif',
                        'expired'    => 'Kadaluarsa',
                        'terminated' => 'Diakhiri',
                    ]),
            ])
            ->actions([
                // Perpanjang kontrak
                Tables\Actions\Action::make('extend')
                    ->label('Perpanjang')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->visible(fn (Contract $record) => $record->status === 'active')
                    ->form([
                        Forms\Components\TextInput::make('extend_months')
                            ->label('Perpanjang (Bulan)')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required(),
                    ])
                    ->action(function (Contract $record, array $data) {
                        $record->update([
                            'end_date' => $record->end_date->addMonths($data['extend_months']),
                        ]);
                        Notification::make()
                            ->title('Kontrak diperpanjang ' . $data['extend_months'] . ' bulan!')
                            ->success()
                            ->send();
                    }),

                // Akhiri kontrak
                Tables\Actions\Action::make('terminate')
                    ->label('Akhiri')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Contract $record) => $record->status === 'active')
                    ->requiresConfirmation()
                    ->action(function (Contract $record) {
                        $record->update(['status' => 'terminated']);
                        // Update booking & kamar
                        $record->booking->update(['status' => 'completed']);
                        $record->booking->room->update(['status' => 'available']);
                        Notification::make()
                            ->title('Kontrak diakhiri, kamar kembali tersedia!')
                            ->warning()
                            ->send();
                    }),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Scope: Owner hanya lihat kontrak dari properti miliknya
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()->isOwner()) {
            return $query->whereHas('booking.room.property', function ($q) {
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
            'index'  => Pages\ListContracts::route('/'),
            'create' => Pages\CreateContract::route('/create'),
            'edit'   => Pages\EditContract::route('/{record}/edit'),
            'view'   => Pages\ViewContract::route('/{record}'),
        ];
    }
}