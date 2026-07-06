<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Payment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class OverduePayments extends BaseWidget
{
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('No. Invoice'),

                Tables\Columns\TextColumn::make('booking.tenant.user.name')
                    ->label('Tenant'),

                Tables\Columns\TextColumn::make('booking.room.room_number')
                    ->label('Kamar'),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->color('danger'),
            ])
            ->defaultSort('due_date', 'asc')
            ->paginated(false)
            ->heading('Pembayaran Overdue');
    }

    protected function getQuery(): Builder
    {
        $query = Payment::query()
            ->where('status', 'overdue')
            ->latest()
            ->limit(5);

        if (Auth::user()->isOwner()) {
            return $query->whereHas('booking.room.property', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        return $query;
    }
}