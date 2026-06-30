<?php

namespace App\Filament\Admin\Resources\ComplaintResource\Pages;

use App\Filament\Admin\Resources\ComplaintResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class ChatComplaint extends Page
{
    use InteractsWithRecord;

    protected static string $resource = ComplaintResource::class;

    protected static string $view = 'filament.admin.resources.complaint-resource.pages.chat-complaint';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getTitle(): string
    {
        return 'Chat - ' . $this->record->ticket_number;
    }
}