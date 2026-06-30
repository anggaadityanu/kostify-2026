<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ComplaintResource\Pages;
use App\Models\Complaint;
use App\Models\Tenant;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ComplaintResource extends Resource
{
    protected static ?string $model = Complaint::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected static ?string $navigationLabel = 'Komplain';
    protected static ?string $modelLabel = 'Komplain';
    protected static ?string $navigationGroup = 'Manajemen Tenant';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Informasi Komplain')
                ->schema([
                    Forms\Components\TextInput::make('ticket_number')
                        ->label('Nomor Tiket')
                        ->disabled()
                        ->placeholder('Auto-generate'),

                    Forms\Components\Select::make('tenant_id')
                        ->label('Tenant')
                        ->options(fn () => Tenant::with('user')
                            ->get()
                            ->pluck('user.name', 'id')
                        )
                        ->required(),

                    Forms\Components\Select::make('room_id')
                        ->label('Kamar')
                        ->options(fn () => Room::with('property')
                            ->get()
                            ->mapWithKeys(fn ($room) => [
                                $room->id => $room->property->name . ' - ' . $room->room_number
                            ])
                        )
                        ->required(),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'open'        => 'Open',
                            'in_progress' => 'In Progress',
                            'resolved'    => 'Resolved',
                            'closed'      => 'Closed',
                        ])
                        ->default('open')
                        ->required(),

                    Forms\Components\Select::make('priority')
                        ->label('Prioritas')
                        ->options([
                            'low'    => 'Rendah',
                            'medium' => 'Sedang',
                            'high'   => 'Tinggi',
                        ])
                        ->default('medium')
                        ->required(),

                    Forms\Components\Select::make('category')
                        ->label('Kategori')
                        ->options([
                            'electrical'  => 'Listrik',
                            'plumbing'    => 'Air/Pipa',
                            'furniture'   => 'Furnitur',
                            'cleanliness' => 'Kebersihan',
                            'security'    => 'Keamanan',
                            'other'       => 'Lainnya',
                        ])
                        ->required(),
                ])->columns(2),

            Forms\Components\Section::make('Detail Keluhan')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul Keluhan')
                        ->placeholder('AC rusak di kamar A1')
                        ->required()
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->placeholder('Jelaskan masalah secara detail...')
                        ->rows(4)
                        ->required()
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->label('No. Tiket')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('tenant.user.name')
                    ->label('Tenant')
                    ->searchable(),

                Tables\Columns\TextColumn::make('room.room_number')
                    ->label('Kamar')
                    ->searchable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Keluhan')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\BadgeColumn::make('category')
                    ->label('Kategori')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'electrical'  => 'Listrik',
                        'plumbing'    => 'Air/Pipa',
                        'furniture'   => 'Furnitur',
                        'cleanliness' => 'Kebersihan',
                        'security'    => 'Keamanan',
                        'other'       => 'Lainnya',
                    }),

                Tables\Columns\BadgeColumn::make('priority')
                    ->label('Prioritas')
                    ->colors([
                        'success' => 'low',
                        'warning' => 'medium',
                        'danger'  => 'high',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'low'    => 'Rendah',
                        'medium' => 'Sedang',
                        'high'   => 'Tinggi',
                    }),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'open',
                        'info'    => 'in_progress',
                        'success' => 'resolved',
                        'gray'    => 'closed',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'open'        => 'Open',
                        'in_progress' => 'In Progress',
                        'resolved'    => 'Resolved',
                        'closed'      => 'Closed',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'open'        => 'Open',
                        'in_progress' => 'In Progress',
                        'resolved'    => 'Resolved',
                        'closed'      => 'Closed',
                    ]),

                Tables\Filters\SelectFilter::make('priority')
                    ->label('Prioritas')
                    ->options([
                        'low'    => 'Rendah',
                        'medium' => 'Sedang',
                        'high'   => 'Tinggi',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('process')
                    ->label('Proses')
                    ->icon('heroicon-o-wrench')
                    ->color('warning')
                    ->visible(fn (Complaint $record) => $record->status === 'open')
                    ->action(function (Complaint $record) {
                        $record->update(['status' => 'in_progress']);
                        Notification::make()
                            ->title('Komplain sedang diproses!')
                            ->warning()
                            ->send();
                    }),

                Tables\Actions\Action::make('resolve')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Complaint $record) => $record->status === 'in_progress')
                    ->requiresConfirmation()
                    ->action(function (Complaint $record) {
                        $record->update(['status' => 'resolved']);
                        Notification::make()
                            ->title('Komplain berhasil diselesaikan!')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('chat')
                    ->label('Chat')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('info')
                    ->url(fn (Complaint $record) => ComplaintResource::getUrl('chat', ['record' => $record])),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index'  => Pages\ListComplaints::route('/'),
            'create' => Pages\CreateComplaint::route('/create'),
            'edit'   => Pages\EditComplaint::route('/{record}/edit'),
            'view'   => Pages\ViewComplaint::route('/{record}'),
            'chat'   => Pages\ChatComplaint::route('/{record}/chat'),
        ];
    }
}