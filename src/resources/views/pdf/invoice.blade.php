<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }

        .header { background: #4F46E5; color: white; padding: 20px; }
        .header h1 { font-size: 24px; font-weight: bold; }
        .header p { font-size: 12px; opacity: 0.8; margin-top: 4px; }

        .content { padding: 20px; }

        .invoice-info { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .invoice-info .left h2 { font-size: 18px; color: #4F46E5; }
        .invoice-info .right { text-align: right; }

        .badge { display: inline-block; padding: 4px 10px; border-radius: 20px;
                 font-size: 11px; font-weight: bold; }
        .badge-paid { background: #D1FAE5; color: #065F46; }
        .badge-unpaid { background: #FEF3C7; color: #92400E; }
        .badge-overdue { background: #FEE2E2; color: #991B1B; }

        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: #F3F4F6; padding: 10px; text-align: left;
             font-size: 11px; color: #6B7280; border-bottom: 2px solid #E5E7EB; }
        td { padding: 10px; border-bottom: 1px solid #E5E7EB; }

        .total-section { background: #F9FAFB; padding: 15px;
                         border-radius: 8px; margin-top: 15px; }
        .total-row { display: flex; justify-content: space-between;
                     padding: 5px 0; font-size: 12px; }
        .total-final { font-size: 16px; font-weight: bold;
                       color: #4F46E5; border-top: 2px solid #E5E7EB;
                       padding-top: 10px; margin-top: 5px; }

        .footer { margin-top: 30px; padding-top: 15px;
                  border-top: 1px solid #E5E7EB; text-align: center;
                  color: #9CA3AF; font-size: 10px; }

        .info-grid { display: grid; grid-template-columns: 1fr 1fr;
                     gap: 15px; margin: 15px 0; }
        .info-box { background: #F9FAFB; padding: 12px; border-radius: 8px; }
        .info-box label { font-size: 10px; color: #6B7280; display: block; }
        .info-box span { font-weight: bold; font-size: 12px; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <h1>Kostify</h1>
        <p>Sistem Manajemen Kos & Kontrakan</p>
    </div>

    <div class="content">

        {{-- Invoice Info --}}
        <div class="invoice-info">
            <div class="left">
                <h2>INVOICE</h2>
                <p style="color: #6B7280; margin-top: 4px;">
                    {{ $payment->invoice_number }}
                </p>
            </div>
            <div class="right">
                <p>Tanggal: {{ now()->format('d M Y') }}</p>
                <p>Jatuh Tempo: {{ $payment->due_date->format('d M Y') }}</p>
                <br>
                <span class="badge badge-{{ $payment->status }}">
                    {{ match($payment->status) {
                        'paid'    => 'LUNAS',
                        'unpaid'  => 'BELUM BAYAR',
                        'overdue' => 'TERLAMBAT',
                        default   => strtoupper($payment->status),
                    } }}
                </span>
            </div>
        </div>

        {{-- Tenant & Properti Info --}}
        <div class="info-grid">
            <div class="info-box">
                <label>TAGIHAN KEPADA</label>
                <span>{{ $payment->booking->tenant->user->name }}</span>
                <p style="color: #6B7280; font-size: 11px; margin-top: 2px;">
                    {{ $payment->booking->tenant->user->email }}
                </p>
                <p style="color: #6B7280; font-size: 11px;">
                    {{ $payment->booking->tenant->phone ?? '-' }}
                </p>
            </div>
            <div class="info-box">
                <label>DETAIL PROPERTI</label>
                <span>{{ $payment->booking->room->property->name }}</span>
                <p style="color: #6B7280; font-size: 11px; margin-top: 2px;">
                    Kamar: {{ $payment->booking->room->room_number }}
                </p>
                <p style="color: #6B7280; font-size: 11px;">
                    {{ $payment->booking->room->property->address }}
                </p>
            </div>
        </div>

        {{-- Booking Info --}}
        <table>
            <thead>
                <tr>
                    <th>DESKRIPSI</th>
                    <th>PERIODE</th>
                    <th>DURASI</th>
                    <th style="text-align: right">JUMLAH</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        Sewa Kamar {{ $payment->booking->room->room_number }}
                        <br>
                        <small style="color: #6B7280;">
                            Kode Booking: {{ $payment->booking->booking_code }}
                        </small>
                    </td>
                    <td>
                        {{ $payment->booking->check_in_date->format('d M Y') }}
                    </td>
                    <td>{{ $payment->booking->duration_months }} bulan</td>
                    <td style="text-align: right">
                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                    </td>
                </tr>
                @if($payment->fine_amount > 0)
                    <tr>
                        <td colspan="3" style="color: #DC2626">
                            Denda Keterlambatan
                        </td>
                        <td style="text-align: right; color: #DC2626">
                            Rp {{ number_format($payment->fine_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        {{-- Total --}}
        <div class="total-section">
            <div class="total-row">
                <span style="color: #6B7280">Subtotal</span>
                <span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
            </div>
            @if($payment->fine_amount > 0)
                <div class="total-row">
                    <span style="color: #DC2626">Denda</span>
                    <span style="color: #DC2626">
                        Rp {{ number_format($payment->fine_amount, 0, ',', '.') }}
                    </span>
                </div>
            @endif
            <div class="total-row total-final">
                <span>TOTAL BAYAR</span>
                <span>Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        @if($payment->status === 'paid')
            <div style="margin-top: 15px; padding: 10px; background: #D1FAE5;
                        border-radius: 8px; text-align: center;">
                <p style="color: #065F46; font-weight: bold;">
                    Pembayaran telah diterima pada
                    {{ $payment->paid_date?->format('d M Y') }}
                </p>
                @if($payment->payment_method)
                    <p style="color: #065F46; font-size: 11px;">
                        Metode: {{ strtoupper($payment->payment_method) }}
                    </p>
                @endif
            </div>
        @endif

        {{-- Footer --}}
        <div class="footer">
            <p>Dokumen ini digenerate otomatis oleh sistem Kostify</p>
            <p>{{ now()->format('d M Y H:i') }} WIB</p>
        </div>
    </div>
</body>
</html>