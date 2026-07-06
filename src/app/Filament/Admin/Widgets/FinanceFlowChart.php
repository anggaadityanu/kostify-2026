<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Room;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class FinanceFlowChart extends ChartWidget
{
    protected static ?string $heading = 'Arus Pendapatan Kostify';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = '2xl';

    protected function getData(): array
    {
        $labels = collect(range(5, 0))->map(fn (int $month) => now()->subMonths($month)->format('M Y'));
        $roomIds = $this->getRoomIds();

        $revenue = $labels->map(function (string $label) use ($roomIds): float {
            $date = now()->createFromFormat('M Y', $label);

            return (float) Payment::query()
                ->when($roomIds !== null, fn ($query) => $query->whereHas('booking', fn ($booking) => $booking->whereIn('room_id', $roomIds)))
                ->where('status', 'paid')
                ->whereMonth('paid_date', $date->month)
                ->whereYear('paid_date', $date->year)
                ->sum('total_amount');
        });

        $bookings = $labels->map(function (string $label) use ($roomIds): int {
            $date = now()->createFromFormat('M Y', $label);

            return Booking::query()
                ->when($roomIds !== null, fn ($query) => $query->whereIn('room_id', $roomIds))
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan',
                    'data' => $revenue->toArray(),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.12)',
                    'fill' => true,
                    'tension' => 0.45,
                ],
                [
                    'label' => 'Booking',
                    'data' => $bookings->toArray(),
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'fill' => true,
                    'tension' => 0.45,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
                'y1' => [
                    'beginAtZero' => true,
                    'position' => 'right',
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
            ],
        ];
    }

    private function getRoomIds()
    {
        if (! Auth::user()->isOwner()) {
            return null;
        }

        $propertyIds = Property::where('user_id', Auth::id())->pluck('id');

        return Room::whereIn('property_id', $propertyIds)->pluck('id');
    }
}
