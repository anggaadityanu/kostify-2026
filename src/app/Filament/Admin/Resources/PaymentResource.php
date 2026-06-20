<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Pembayaran';
    protected static ?string $modelLabel = 'Pembayaran';
    protected static ?string $navigationGroup = 'Keuangan';
    protected static ?int $navigationSort = 1;

    /**
     * Form Create & Edit payment
     * Logika: pilih booking → isi jumlah → 
     * total otomatis terhitung dengan denda
     */
    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Informasi Tagihan')
                ->schema([
                    Forms\Components\TextInput::make('invoice_number')
                        ->label('Nomor Invoice')
                        ->disabled()
                        ->placeholder('Auto-generate'),

                    Forms\Components\Select::make('booking_id')
                        ->label('Booking')
                        ->options(fn () => Booking::with('tenant.user', 'room')
                            ->where('status', 'active')
                            ->get()
                            ->mapWithKeys(fn ($booking) => [
                                $booking->id => $booking->booking_code . ' - ' . $booking->tenant->user->name
                            ])
                        )
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            if ($state) {
                                $booking = Booking::find($state);
                                $set('amount', $booking->room->price_monthly);
                                $set('total_amount', $booking->room->price_monthly);
                            }
                        }),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'unpaid'    => 'Belum Bayar',
                            'pending'   => 'Menunggu Konfirmasi',
                            'paid'      => 'Lunas',
                            'overdue'   => 'Menunggak',
                            'cancelled' => 'Dibatalkan',
                        ])
                        ->default('unpaid')
                        ->required(),

                    Forms\Components\Select::make('payment_method')
                        ->label('Metode Pembayaran')
                        ->options([
                            'transfer'  => 'Transfer Bank',
                            'qris'      => 'QRIS',
                            'cash'      => 'Tunai',
                            'midtrans'  => 'Midtrans',
                        ])
                        ->nullable(),
                ])->columns(2),

            Forms\Components\Section::make('Detail Pembayaran')
                ->schema([
                    Forms\Components\TextInput::make('amount')
                        ->label('Jumlah Tagihan (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, $get, $set) {
                            $set('total_amount', $state + ($get('fine_amount') ?? 0));
                        }),

                    Forms\Components\TextInput::make('fine_amount')
                        ->label('Denda (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0)
                        ->live()
                        ->afterStateUpdated(function ($state, $get, $set) {
                            $set('total_amount', ($get('amount') ?? 0) + $state);
                        }),

                    Forms\Components\TextInput::make('total_amount')
                        ->label('Total Bayar (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->disabled()
                        ->helperText('Otomatis = tagihan + denda'),

                    Forms\Components\DatePicker::make('due_date')
                        ->label('Jatuh Tempo')
                        ->required(),

                    Forms\Components\DatePicker::make('paid_date')
                        ->label('Tanggal Bayar')
                        ->nullable(),
                ])->columns(2),
        ]);
    }

    /**
     * Table list pembayaran
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('No. Invoice')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('booking.tenant.user.name')
                    ->label('Tenant')
                    ->searchable(),

                Tables\Columns\TextColumn::make('booking.room.property.name')
                    ->label('Properti')
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Tagihan')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('fine_amount')
                    ->label('Denda')
                    ->money('IDR')
                    ->color('danger'),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->color(fn ($record) => $record->isOverdue() ? 'danger' : null),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'unpaid',
                        'info'    => 'pending',
                        'success' => 'paid',
                        'danger'  => 'overdue',
                        'gray'    => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'unpaid'    => 'Belum Bayar',
                        'pending'   => 'Menunggu',
                        'paid'      => 'Lunas',
                        'overdue'   => 'Menunggak',
                        'cancelled' => 'Dibatalkan',
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        return \Maatwebsite\Excel\Facades\Excel::download(
                            new \App\Exports\PaymentExport(),
                            'laporan-pembayaran-' . now()->format('Y-m-d') . '.xlsx'
                        );
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'unpaid'  => 'Belum Bayar',
                        'pending' => 'Menunggu',
                        'paid'    => 'Lunas',
                        'overdue' => 'Menunggak',
                    ]),
            ])
            ->actions([
                // Konfirmasi pembayaran manual
                Tables\Actions\Action::make('confirm')
                    ->label('Konfirmasi Lunas')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Payment $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (Payment $record) {
                        $record->update([
                            'status'    => 'paid',
                            'paid_date' => now(),
                        ]);
                        Notification::make()
                            ->title('Pembayaran dikonfirmasi!')
                            ->success()
                            ->send();
                    }),

                // Tandai overdue
                Tables\Actions\Action::make('overdue')
                    ->label('Tandai Menunggak')
                    ->icon('heroicon-o-exclamation-circle')
                    ->color('danger')
                    ->visible(fn (Payment $record) => $record->status === 'unpaid' && $record->due_date->isPast())
                    ->action(function (Payment $record) {
                        $fine = $record->calculateFine();
                        $record->update([
                            'status'      => 'overdue',
                            'fine_amount' => $fine,
                            'total_amount' => $record->amount + $fine,
                        ]);
                        Notification::make()
                            ->title('Ditandai menunggak! Denda: Rp ' . number_format($fine, 0, ',', '.'))
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

    /**
     * Scope: Owner hanya lihat payment dari properti miliknya
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
            'index'  => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit'   => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}