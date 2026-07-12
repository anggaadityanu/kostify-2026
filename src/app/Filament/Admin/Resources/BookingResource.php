<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BookingResource\Pages;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BookingApprovedNotification;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Booking';
    protected static ?string $modelLabel = 'Booking';
    protected static ?string $navigationGroup = 'Manajemen Tenant';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Informasi Booking')
                ->schema([
                    Forms\Components\TextInput::make('booking_code')
                        ->label('Kode Booking')
                        ->disabled()
                        ->placeholder('Auto-generate')
                        ->helperText('Dibuat otomatis oleh sistem'),

                    Forms\Components\Select::make('tenant_id')
                        ->label('Tenant')
                        ->options(fn () => Tenant::with('user')
                            ->get()
                            ->pluck('user.name', 'id')
                        )
                        ->required(),

                    Forms\Components\Select::make('room_id')
                        ->label('Kamar')
                        ->options(function (?Booking $record) {
                            return Room::with('property')
                                ->where(function ($q) use ($record) {
                                    $q->where('status', 'available');
                                    if ($record?->room_id) {
                                        $q->orWhere('id', $record->room_id);
                                    }
                                })
                                ->get()
                                ->mapWithKeys(fn ($room) => [
                                    $room->id => $room->property->name . ' - ' . $room->room_number
                                ]);
                        })
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            if ($state) {
                                $room = Room::find($state);
                                $set('_price_info', 'Harga: Rp ' . number_format($room->price_monthly, 0, ',', '.') . '/bulan');
                            }
                        }),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending'   => 'Pending',
                            'approved'  => 'Disetujui',
                            'active'    => 'Aktif',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                        ])
                        ->default('pending')
                        ->required(),
                ])->columns(2),

            Forms\Components\Section::make('Detail Sewa')
                ->schema([
                    Forms\Components\DatePicker::make('check_in_date')
                        ->label('Tanggal Masuk')
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, $get, $set) {
                            self::calculateTotal($state, $get('duration_months'), $get('room_id'), $set);
                        }),

                    Forms\Components\DatePicker::make('check_out_date')
                        ->label('Tanggal Keluar')
                        ->helperText('Opsional, diisi saat tenant keluar'),

                    Forms\Components\TextInput::make('duration_months')
                        ->label('Durasi (Bulan)')
                        ->numeric()
                        ->default(1)
                        ->minValue(1)
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, $get, $set) {
                            self::calculateTotal($get('check_in_date'), $state, $get('room_id'), $set);
                        }),

                    Forms\Components\TextInput::make('total_price')
                        ->label('Total Harga (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->required()
                        ->helperText('Otomatis dihitung dari harga kamar × durasi'),

                    Forms\Components\Textarea::make('notes')
                        ->label('Catatan')
                        ->rows(2)
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    protected static function calculateTotal($checkIn, $duration, $roomId, $set): void
    {
        if ($roomId && $duration) {
            $room  = Room::find($roomId);
            $total = $room->price_monthly * $duration;
            $set('total_price', $total);
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_code')
                    ->label('Kode Booking')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('tenant.user.name')
                    ->label('Tenant')
                    ->searchable(),

                Tables\Columns\TextColumn::make('room.room_number')
                    ->label('Kamar')
                    ->searchable(),

                Tables\Columns\TextColumn::make('room.property.name')
                    ->label('Properti')
                    ->searchable(),

                Tables\Columns\TextColumn::make('check_in_date')
                    ->label('Tanggal Masuk')
                    ->date('d M Y'),

                Tables\Columns\TextColumn::make('duration_months')
                    ->label('Durasi')
                    ->suffix(' bulan'),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total')
                    ->money('IDR'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'info'    => 'approved',
                        'success' => 'active',
                        'gray'    => 'completed',
                        'danger'  => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending'   => 'Pending',
                        'approved'  => 'Disetujui',
                        'active'    => 'Aktif',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'   => 'Pending',
                        'approved'  => 'Disetujui',
                        'active'    => 'Aktif',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Booking $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalDescription('Setelah disetujui, tagihan pembayaran akan dibuat otomatis sebesar total harga (harga kamar x durasi sewa), jatuh tempo 2 hari dari sekarang.')
                    ->action(function (Booking $record) {
                        $record->refresh();
                        if ($record->status !== 'pending') {
                            return;
                        }

                        $record->update(['status' => 'approved']);

                        app(\App\Services\BillingService::class)->generateInitialInvoices($record);

                        $record->tenant->user->notify(
                            new BookingApprovedNotification($record)
                        );

                        Notification::make()
                            ->title('Booking disetujui, tagihan dibuat & notifikasi terkirim!')
                            ->body('Tagihan sebesar Rp ' . number_format($record->total_price, 0, ',', '.') . " untuk {$record->duration_months} bulan sudah dibuat. Tenant punya waktu 2 hari untuk membayar sebelum booking otomatis dibatalkan.")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('cancel')
                    ->label('Batalkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Booking $record) => in_array($record->status, ['pending', 'approved']))
                    ->requiresConfirmation()
                    ->action(function (Booking $record) {
                        $record->update(['status' => 'cancelled']);

                        $record->payments()
                            ->whereIn('status', ['unpaid', 'pending', 'overdue'])
                            ->update(['status' => 'cancelled']);

                        Notification::make()
                            ->title('Booking dibatalkan!')
                            ->warning()
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),
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
            return $query->whereHas('room.property', function ($q) {
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
            'index'  => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit'   => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}