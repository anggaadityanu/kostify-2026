<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    /**
     * Generate & download invoice PDF
     * Logika: ambil data payment → render view →
     * convert ke PDF → download
     */
    public function download(int $paymentId): Response
    {
        $payment = Payment::with([
            'booking.tenant.user',
            'booking.room.property',
            'booking.contract',
        ])->findOrFail($paymentId);

        // Pastikan hanya tenant pemilik yang bisa download
        if (auth()->user()->hasRole('tenant')) {
            abort_if(
                $payment->booking->tenant->user_id !== auth()->id(),
                403
            );
        }

        $pdf = Pdf::loadView('pdf.invoice', [
            'payment' => $payment,
        ])->setPaper('a4');

        return $pdf->download('invoice-' . $payment->invoice_number . '.pdf');
    }
}