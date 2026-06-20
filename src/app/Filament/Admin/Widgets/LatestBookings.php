<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class LatestBookings extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    /**
     * Tampilkan 5 booking terbaru di dashboard
     */
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->columns([
                Tables\Columns\TextColumn::make('booking_code')
                    ->label('Kode Booking'),

                Tables\Columns\TextColumn::make('tenant.user.name')
                    ->label('Tenant'),

                Tables\Columns\TextColumn::make('room.property.name')
                    ->label('Properti'),

                Tables\Columns\TextColumn::make('room.room_number')
                    ->label('Kamar'),

                Tables\Columns\TextColumn::make('check_in_date')
                    ->label('Masuk')
                    ->date('d M Y'),

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
            ->defaultSort('created_at', 'desc')
            ->paginated(false)
            ->heading('Booking Terbaru');
    }

    protected function getQuery(): Builder
    {
        $query = Booking::query()->latest()->limit(5);

        if (Auth::user()->isOwner()) {
            return $query->whereHas('room.property', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        return $query;
    }
}