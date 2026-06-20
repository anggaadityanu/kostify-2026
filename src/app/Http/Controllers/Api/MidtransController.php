<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MidtransController extends Controller
{
    public function __construct(
        protected MidtransService $midtrans
    ) {}

    /**
     * Generate Snap Token untuk frontend
     * Logika: frontend request token → 
     * kita buatkan transaksi di Midtrans → return token
     */
    public function getSnapToken(Request $request): JsonResponse
    {
        $request->validate([
            'payment_id' => ['required', 'exists:payments,id'],
        ]);

        $payment = Payment::findOrFail($request->payment_id);

        // Pastikan payment milik tenant yang login
        if ($payment->booking->tenant->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $snapToken = $this->midtrans->createTransaction($payment);

            return response()->json([
                'snap_token' => $snapToken,
                'client_key' => config('midtrans.client_key'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle notification dari Midtrans server
     * Logika: Midtrans POST ke sini setelah pembayaran
     * → kita proses & update DB
     */
    public function notification(Request $request): JsonResponse
    {
        try {
            $result = $this->midtrans->handleNotification();
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}