<?php

namespace App\Exports;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle
{
    public function __construct(
        protected ?string $month = null,
        protected ?string $status = null
    ) {}

    /**
     * Query data untuk export
     * Logika: ambil semua payment → filter → export
     */
    public function collection()
    {
        return Payment::with('booking.tenant.user', 'booking.room.property')
            ->when($this->month, fn ($q) =>
                $q->whereMonth('created_at', date('m', strtotime($this->month)))
                  ->whereYear('created_at', date('Y', strtotime($this->month)))
            )
            ->when($this->status, fn ($q) =>
                $q->where('status', $this->status)
            )
            ->when(Auth::user()->isOwner(), fn ($q) =>
                $q->whereHas('booking.room.property', fn ($q) =>
                    $q->where('user_id', Auth::id())
                )
            )
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No. Invoice',
            'Tenant',
            'Email',
            'Properti',
            'Kamar',
            'Tagihan (Rp)',
            'Denda (Rp)',
            'Total (Rp)',
            'Jatuh Tempo',
            'Tanggal Bayar',
            'Metode',
            'Status',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->invoice_number,
            $payment->booking->tenant->user->name,
            $payment->booking->tenant->user->email,
            $payment->booking->room->property->name,
            $payment->booking->room->room_number,
            $payment->amount,
            $payment->fine_amount,
            $payment->total_amount,
            $payment->due_date->format('d/m/Y'),
            $payment->paid_date?->format('d/m/Y') ?? '-',
            $payment->payment_method ?? '-',
            match($payment->status) {
                'paid'    => 'Lunas',
                'unpaid'  => 'Belum Bayar',
                'overdue' => 'Terlambat',
                'pending' => 'Diproses',
                default   => $payment->status,
            },
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid',
                               'startColor' => ['rgb' => '4F46E5']],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Pembayaran';
    }
}