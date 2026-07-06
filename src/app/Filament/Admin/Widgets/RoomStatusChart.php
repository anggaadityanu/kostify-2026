<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Property;
use App\Models\Room;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class RoomStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Komposisi Kamar';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'xl';

    protected function getData(): array
    {
        $query = Room::query();

        if (Auth::user()->isOwner()) {
            $propertyIds = Property::where('user_id', Auth::id())->pluck('id');
            $query->whereIn('property_id', $propertyIds);
        }

        $available = (clone $query)->where('status', 'available')->count();
        $occupied = (clone $query)->where('status', 'occupied')->count();
        $booked = (clone $query)->where('status', 'booked')->count();
        $maintenance = (clone $query)->where('status', 'maintenance')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kamar',
                    'data' => [$available, $occupied, $booked, $maintenance],
                    'backgroundColor' => ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
                    'borderRadius' => 10,
                    'borderWidth' => 0,
                ],
            ],
            'labels' => ['Tersedia', 'Terisi', 'Booked', 'Maintenance'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}
