<x-filament-panels::page>
    <div class="mb-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
        <div class="mb-2 flex items-center gap-2">
            <span class="text-sm font-semibold">{{ $record->ticket_number }}</span>
        </div>
        <h2 class="mb-1 text-lg font-bold">{{ $record->title }}</h2>
        <p class="mb-2 text-sm text-gray-500">
            {{ $record->tenant->user->name }} &middot;
            {{ $record->room->property->name }} - Kamar {{ $record->room->room_number }}
        </p>
        <p>{{ $record->description }}</p>
    </div>

    <livewire:complaint-chat :complaint="$record" :key="'admin-chat-'.$record->id" />
</x-filament-panels::page>