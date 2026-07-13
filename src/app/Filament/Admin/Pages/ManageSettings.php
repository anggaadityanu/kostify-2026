<?php

namespace App\Filament\Admin\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Pengaturan Website';
    protected static ?string $title = 'Pengaturan Website';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?int $navigationSort = 99;

    protected static string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    /**
     * Halaman ini cuma buat Super Admin. Owner cuma review & monitoring,
     * jadi menu "Pengaturan Website" gak ditampilkan & gak bisa diakses
     * langsung lewat URL sekalipun.
     */
    public static function canAccess(): bool
    {
        return Auth::check() && Auth::user()->isSuperAdmin();
    }

    public function mount(): void
    {
        $this->form->fill(Setting::current()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Kontak & Footer')
                    ->description('Informasi ini ditampilkan di footer semua halaman dan halaman Tentang Kami.')
                    ->schema([
                        Forms\Components\TextInput::make('address')
                            ->label('Alamat')
                            ->required(),

                        Forms\Components\TextInput::make('phone')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->required(),

                        Forms\Components\TextInput::make('whatsapp')
                            ->label('Nomor WhatsApp')
                            ->tel()
                            ->helperText('Format: 62812xxxxxxx (tanpa tanda + atau 0 di depan), dipakai untuk link wa.me'),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Halaman Tentang Kami')
                    ->description('Konten ini ditampilkan di halaman "Tentang Kami".')
                    ->schema([
                        Forms\Components\TextInput::make('about_title')
                            ->label('Judul')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('about_description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('about_image')
                            ->label('Gambar')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->imageEditor()
                            ->columnSpanFull(),

                        Forms\Components\Repeater::make('about_features')
                            ->label('Poin Keunggulan')
                            ->simple(
                                Forms\Components\TextInput::make('feature')
                                    ->label('Poin')
                                    ->required()
                            )
                            ->addActionLabel('Tambah Poin')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Halaman Beranda (Home)')
                    ->description('Konten ini ditampilkan di halaman utama website.')
                    ->schema([
                        Forms\Components\TextInput::make('home_hero_title')
                            ->label('Judul Hero')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('home_hero_subtitle')
                            ->label('Subjudul Hero')
                            ->rows(3)
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('home_carousel_images')
                            ->label('Gambar Carousel Hero')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->maxFiles(5)
                            ->disk('public')
                            ->directory('settings')
                            ->imageEditor()
                            ->helperText('Kalau belum diupload, otomatis pakai gambar bawaan template.')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('home_cta_title')
                            ->label('Judul CTA (Call to Action)')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('home_cta_description')
                            ->label('Deskripsi CTA')
                            ->rows(2)
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('home_cta_image')
                            ->label('Gambar CTA')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->imageEditor()
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        Setting::current()->update($this->form->getState());

        Notification::make()
            ->title('Pengaturan berhasil disimpan!')
            ->success()
            ->send();
    }
}