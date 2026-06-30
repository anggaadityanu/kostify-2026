<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $melati  = Property::where('name', 'Kos Putri Melati')->first();
        $mawar   = Property::where('name', 'Kos Putra Mawar')->first();
        $anggrek = Property::where('name', 'Kontrakan Anggrek')->first();

        if (!$melati || !$mawar || !$anggrek) {
            $this->command->warn('Properti belum ada, jalankan PropertySeeder dulu.');
            return;
        }

        $rooms = [
            // Kos Putri Melati
            ['property_id' => $melati->id, 'room_number' => 'A1', 'type' => 'standard', 'status' => 'occupied', 'capacity' => 1, 'size' => 12, 'price_monthly' => 850000, 'facilities' => ['ac', 'wifi', 'bed', 'wardrobe']],
            ['property_id' => $melati->id, 'room_number' => 'A2', 'type' => 'standard', 'status' => 'available', 'capacity' => 1, 'size' => 12, 'price_monthly' => 850000, 'facilities' => ['wifi', 'bed', 'wardrobe']],
            ['property_id' => $melati->id, 'room_number' => 'A3', 'type' => 'deluxe', 'status' => 'available', 'capacity' => 1, 'size' => 16, 'price_monthly' => 1200000, 'facilities' => ['ac', 'wifi', 'bathroom', 'bed', 'wardrobe', 'desk']],
            ['property_id' => $melati->id, 'room_number' => 'A4', 'type' => 'standard', 'status' => 'available', 'capacity' => 1, 'size' => 12, 'price_monthly' => 850000, 'facilities' => ['wifi', 'bed']],

            // Kos Putra Mawar
            ['property_id' => $mawar->id, 'room_number' => 'B1', 'type' => 'standard', 'status' => 'available', 'capacity' => 1, 'size' => 9, 'price_monthly' => 700000, 'facilities' => ['wifi', 'bed']],
            ['property_id' => $mawar->id, 'room_number' => 'B2', 'type' => 'standard', 'status' => 'occupied', 'capacity' => 1, 'size' => 9, 'price_monthly' => 700000, 'facilities' => ['wifi', 'bed', 'wardrobe']],
            ['property_id' => $mawar->id, 'room_number' => 'B3', 'type' => 'vip', 'status' => 'available', 'capacity' => 2, 'size' => 25, 'price_monthly' => 1500000, 'facilities' => ['ac', 'wifi', 'bathroom', 'tv', 'bed', 'wardrobe', 'desk']],

            // Kontrakan Anggrek
            ['property_id' => $anggrek->id, 'room_number' => 'C1', 'type' => 'vip', 'status' => 'available', 'capacity' => 4, 'size' => 36, 'price_monthly' => 2500000, 'price_yearly' => 27000000, 'facilities' => ['ac', 'wifi', 'bathroom', 'tv', 'water_heater']],
            ['property_id' => $anggrek->id, 'room_number' => 'C2', 'type' => 'standard', 'status' => 'maintenance', 'capacity' => 2, 'size' => 20, 'price_monthly' => 1800000, 'facilities' => ['wifi', 'bed']],
        ];

        foreach ($rooms as $data) {
            Room::firstOrCreate(
                ['property_id' => $data['property_id'], 'room_number' => $data['room_number']],
                $data
            );
        }

        $this->command->info('Rooms berhasil dibuat!');
    }
}