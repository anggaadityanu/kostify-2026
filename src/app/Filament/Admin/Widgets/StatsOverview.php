<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Room;
use App\Models\Tenant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    /**
     * Tampilkan statistik utama di dashboard
     * Logika: query DB → hitung → tampilkan sebagai card
     */
    protected function getStats(): array
    {
        $user = Auth::user();

        // Scope query berdasarkan role
        if ($user->isOwner()) {
            $propertyIds = Property::where('user_id', $user->id)->pluck('id');
            $roomIds     = Room::whereIn('property_id', $propertyIds)->pluck('id');

            $totalProperties  = $propertyIds->count();
            $totalRooms       = $roomIds->count();
            $availableRooms   = Room::whereIn('id', $roomIds)->where('status', 'available')->count();
            $occupiedRooms    = Room::whereIn('id', $roomIds)->where('status', 'occupied')->count();
            $totalTenants     = Booking::whereIn('room_id', $roomIds)->where('status', 'active')->count();
            $pendingBookings  = Booking::whereIn('room_id', $roomIds)->where('status', 'pending')->count();
            $monthlyRevenue   = Payment::whereHas('booking', fn ($q) => $q->whereIn('room_id', $roomIds))
                                    ->where('status', 'paid')
                                    ->whereMonth('paid_date', now()->month)
                                    ->sum('total_amount');
            $overduePayments  = Payment::whereHas('booking', fn ($q) => $q->whereIn('room_id', $roomIds))
                                    ->where('status', 'overdue')
                                    ->count();
        } else {
            // Super Admin lihat semua
            $totalProperties  = Property::count();
            $totalRooms       = Room::count();
            $availableRooms   = Room::where('status', 'available')->count();
            $occupiedRooms    = Room::where('status', 'occupied')->count();
            $totalTenants     = Tenant::count();
            $pendingBookings  = Booking::where('status', 'pending')->count();
            $monthlyRevenue   = Payment::where('status', 'paid')
                                    ->whereMonth('paid_date', now()->month)
                                    ->sum('total_amount');
            $overduePayments  = Payment::where('status', 'overdue')->count();
        }

        // Hitung occupancy rate
        $occupancyRate = $totalRooms > 0
            ? round(($occupiedRooms / $totalRooms) * 100, 1)
            : 0;

        return [
            Stat::make('Total Properti', $totalProperties)
                ->description('Properti terdaftar')
                ->descriptionIcon('heroicon-o-building-office')
                ->color('info'),

            Stat::make('Total Kamar', $totalRooms)
                ->description($availableRooms . ' tersedia · ' . $occupiedRooms . ' terisi')
                ->descriptionIcon('heroicon-o-home')
                ->color('success'),

            Stat::make('Occupancy Rate', $occupancyRate . '%')
                ->description('Tingkat hunian')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color($occupancyRate >= 80 ? 'success' : ($occupancyRate >= 50 ? 'warning' : 'danger')),

            Stat::make('Total Tenant', $totalTenants)
                ->description('Tenant aktif')
                ->descriptionIcon('heroicon-o-users')
                ->color('info'),

            Stat::make('Pending Booking', $pendingBookings)
                ->description('Menunggu approval')
                ->descriptionIcon('heroicon-o-clock')
                ->color($pendingBookings > 0 ? 'warning' : 'success'),

            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'))
                ->description(now()->format('F Y'))
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Payment Overdue', $overduePayments)
                ->description('Perlu ditindaklanjuti')
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->color($overduePayments > 0 ? 'danger' : 'success'),
        ];
    }
}