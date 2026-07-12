<?php

namespace App\Livewire\Tenant;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    /**
     * Load semua data tenant untuk dashboard
     * Logika: ambil data berdasarkan user yang login
     */
    public function render()
    {
        $user   = Auth::user();
        $tenant = $user->tenant;

        $activeBooking   = null;
        $pendingBookings = collect();
        $unpaidPayments  = collect();
        $openComplaints  = collect();
        $recentPayments  = collect();

        if ($tenant) {
            // Booking aktif saat ini
            $activeBooking = Booking::where('tenant_id', $tenant->id)
                ->where('status', 'active')
                ->with('room.property')
                ->latest()
                ->first();

            // Booking yang masih menunggu approve admin / menunggu bayar
            $pendingBookings = Booking::where('tenant_id', $tenant->id)
                ->whereIn('status', ['pending', 'approved'])
                ->with('room.property')
                ->latest()
                ->get();

            // Tagihan yang belum dibayar
            $unpaidPayments = Payment::whereHas('booking', fn ($q) =>
                $q->where('tenant_id', $tenant->id)
            )
            ->whereIn('status', ['unpaid', 'overdue'])
            ->with('booking.room.property')
            ->orderBy('due_date')
            ->get();

            // Komplain yang masih open
            $openComplaints = Complaint::where('tenant_id', $tenant->id)
                ->whereIn('status', ['open', 'in_progress'])
                ->latest()
                ->get();

            // Riwayat pembayaran terakhir
            $recentPayments = Payment::whereHas('booking', fn ($q) =>
                $q->where('tenant_id', $tenant->id)
            )
            ->where('status', 'paid')
            ->latest('paid_date')
            ->limit(5)
            ->get();
        }

        // Tagihan bulan berjalan = tagihan belum bayar dengan jatuh tempo paling dekat
        $currentPayment = $unpaidPayments->first();

        // Pembayaran terakhir yang lunas, buat status "Lancar / Terlambat"
        $lastPaidPayment = $recentPayments->first();

        // Gabungan 5 transaksi terbaru (lunas + belum) buat tabel "Pembayaran Terbaru"
        $latestPayments = $recentPayments
            ->concat($unpaidPayments)
            ->sortByDesc(fn ($p) => $p->due_date)
            ->take(5)
            ->values();

        return view('livewire.tenant.dashboard', [
            'tenant'          => $tenant,
            'activeBooking'   => $activeBooking,
            'pendingBookings' => $pendingBookings,
            'unpaidPayments'  => $unpaidPayments,
            'openComplaints'  => $openComplaints,
            'recentPayments'  => $recentPayments,
            'currentPayment'  => $currentPayment,
            'lastPaidPayment' => $lastPaidPayment,
            'latestPayments'  => $latestPayments,
        ])->layout('layouts.tenant-portal');
    }
}