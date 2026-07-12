@php
    $settings = \App\Models\Setting::current();
@endphp

@section('page-title', 'Beranda')

<div class="space-y-6">

    @if(!$tenant)
        <div class="rounded-xl bg-amber-50 border border-amber-200 px-5 py-4 flex flex-col sm:flex-row sm:items-center gap-3">
            <i class="bi bi-exclamation-triangle text-amber-500 text-xl"></i>
            <div class="flex-1">
                <p class="font-semibold text-amber-800">Profil belum lengkap!</p>
                <p class="text-sm text-amber-700">Lengkapi data diri untuk bisa booking kamar.</p>
            </div>
            <a href="{{ route('profile.complete') }}" class="shrink-0 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold px-4 py-2 rounded-lg">
                Lengkapi Sekarang
            </a>
        </div>
    @endif

    {{-- Welcome banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-primary-50 to-primary-100 px-6 py-6 sm:px-8 sm:py-8">
        <div class="relative z-10 max-w-lg">
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Selamat datang kembali, {{ Auth::user()->name }}! 👋</h2>
            <p class="mt-2 text-sm text-slate-500">Kelola informasi kost dan pembayaran Anda dengan mudah melalui dashboard ini.</p>
        </div>
        <i class="bi bi-house-heart-fill absolute -right-4 -bottom-6 text-primary-200/60 text-[9rem] hidden sm:block"></i>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Tagihan bulan ini --}}
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="w-9 h-9 rounded-lg bg-primary-50 text-primary flex items-center justify-center mb-3">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <p class="text-xs text-slate-500">Tagihan Bulan Ini</p>
            @if($currentPayment)
                <p class="text-lg font-bold text-slate-900 mt-1">Rp {{ number_format($currentPayment->total_amount, 0, ',', '.') }}</p>
                <span class="inline-block mt-2 text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $currentPayment->isOverdue() ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600' }}">
                    {{ $currentPayment->isOverdue() ? 'Terlambat' : 'Belum Dibayar' }}
                </span>
                <p class="text-xs text-slate-400 mt-1">Jatuh tempo {{ $currentPayment->due_date->translatedFormat('d M Y') }}</p>
            @else
                <p class="text-lg font-bold text-slate-900 mt-1">Rp 0</p>
                <span class="inline-block mt-2 text-[11px] font-semibold px-2 py-0.5 rounded-full bg-green-50 text-green-600">Tidak ada tagihan</span>
            @endif
        </div>

        {{-- Status pembayaran --}}
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="w-9 h-9 rounded-lg bg-green-50 text-green-600 flex items-center justify-center mb-3">
                <i class="bi bi-check-circle"></i>
            </div>
            <p class="text-xs text-slate-500">Status Pembayaran</p>
            <p class="text-lg font-bold text-slate-900 mt-1">{{ $unpaidPayments->contains(fn($p) => $p->isOverdue()) ? 'Terlambat' : 'Lancar' }}</p>
            <p class="text-xs text-slate-400 mt-1">
                @if($lastPaidPayment)
                    Terakhir dibayar pada {{ $lastPaidPayment->paid_date->translatedFormat('d M Y') }}
                @else
                    Belum ada riwayat bayar
                @endif
            </p>
        </div>

        {{-- Kamar --}}
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="w-9 h-9 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center mb-3">
                <i class="bi bi-door-closed"></i>
            </div>
            <p class="text-xs text-slate-500">Kamar</p>
            @if($activeBooking)
                <p class="text-lg font-bold text-slate-900 mt-1">{{ $activeBooking->room->room_number }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ $activeBooking->room->property->name }}</p>
            @else
                <p class="text-lg font-bold text-slate-900 mt-1">-</p>
                <p class="text-xs text-slate-400 mt-1">Belum ada kamar aktif</p>
            @endif
        </div>

        {{-- Keluhan aktif --}}
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center mb-3">
                <i class="bi bi-chat-dots"></i>
            </div>
            <p class="text-xs text-slate-500">Keluhan Aktif</p>
            <p class="text-lg font-bold text-slate-900 mt-1">{{ $openComplaints->count() }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ $openComplaints->count() > 0 ? 'Dalam proses penanganan' : 'Tidak ada keluhan aktif' }}</p>
        </div>
    </div>

    {{-- Bottom grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Pembayaran terbaru --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-900">Pembayaran Terbaru</h3>
                <a href="{{ route('payments.index') }}" class="text-sm text-primary font-medium hover:underline">Lihat Semua</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-slate-400 uppercase tracking-wide">
                            <th class="px-5 py-3 font-medium">Bulan</th>
                            <th class="px-5 py-3 font-medium">Tagihan</th>
                            <th class="px-5 py-3 font-medium">Tanggal Bayar</th>
                            <th class="px-5 py-3 font-medium">Status</th>
                            <th class="px-5 py-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($latestPayments as $payment)
                            <tr>
                                <td class="px-5 py-3 text-slate-700">{{ $payment->due_date->translatedFormat('F Y') }}</td>
                                <td class="px-5 py-3 text-slate-700">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-slate-500">{{ $payment->paid_date?->translatedFormat('d M Y') ?? '-' }}</td>
                                <td class="px-5 py-3">
                                    @if($payment->status === 'paid')
                                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-green-50 text-green-600">Lunas</span>
                                    @elseif($payment->isOverdue())
                                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-red-50 text-red-600">Terlambat</span>
                                    @else
                                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-amber-50 text-amber-600">Belum Dibayar</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('payments.index') }}"
                                       class="inline-block text-xs font-semibold px-3 py-1.5 rounded-lg {{ $payment->status === 'paid' ? 'bg-slate-100 text-slate-600 hover:bg-slate-200' : 'bg-primary text-white hover:bg-primary-700' }}">
                                        {{ $payment->status === 'paid' ? 'Lihat' : 'Bayar' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada data pembayaran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-3 border-t border-slate-100">
                <a href="{{ route('payments.index') }}" class="flex items-center justify-between text-sm text-slate-500 hover:text-primary">
                    Lihat Riwayat Pembayaran <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Sidebar kanan: info kost + keluhan --}}
        <div class="space-y-6">

            <div class="bg-white rounded-xl border border-slate-200">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-900">Informasi Kost</h3>
                </div>
                <div class="px-5 py-4 space-y-3 text-sm">
                    @if($activeBooking)
                        <div class="flex items-start gap-3">
                            <i class="bi bi-house-door text-slate-400 mt-0.5"></i>
                            <div>
                                <p class="text-slate-400 text-xs">Nama Kost</p>
                                <p class="text-slate-700 font-medium">{{ $activeBooking->room->property->name }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="bi bi-geo-alt text-slate-400 mt-0.5"></i>
                            <div>
                                <p class="text-slate-400 text-xs">Alamat</p>
                                <p class="text-slate-700 font-medium">{{ $activeBooking->room->property->address }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="bi bi-person text-slate-400 mt-0.5"></i>
                            <div>
                                <p class="text-slate-400 text-xs">Pemilik Kost</p>
                                <p class="text-slate-700 font-medium">{{ $activeBooking->room->property->owner->name ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="bi bi-telephone text-slate-400 mt-0.5"></i>
                            <div>
                                <p class="text-slate-400 text-xs">No. Telepon</p>
                                <p class="text-slate-700 font-medium">{{ $settings->phone ?? '-' }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-slate-400 text-sm">Belum ada kost aktif.</p>
                    @endif
                </div>
                @if($activeBooking)
                    <div class="px-5 py-3 border-t border-slate-100">
                        <a href="{{ route('rooms.show', $activeBooking->room->id) }}" class="flex items-center justify-between text-sm text-slate-500 hover:text-primary">
                            Lihat Detail Kost <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-xl border border-slate-200">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-900">Pengajuan Keluhan Terbaru</h3>
                    <a href="{{ route('complaints.index') }}" class="text-sm text-primary font-medium hover:underline">Lihat Semua</a>
                </div>
                <div class="px-5 py-4">
                    @forelse($openComplaints->take(1) as $complaint)
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-full bg-red-50 text-red-500 flex items-center justify-center shrink-0">
                                <i class="bi bi-tools"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-800">{{ $complaint->title }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">Diajukan {{ $complaint->created_at->translatedFormat('d M Y') }} &bull; ID: {{ $complaint->ticket_number }}</p>
                            </div>
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 shrink-0">
                                {{ $complaint->status === 'in_progress' ? 'Dalam Proses' : 'Terbuka' }}
                            </span>
                        </div>
                    @empty
                        <p class="text-slate-400 text-sm">Belum ada keluhan.</p>
                    @endforelse
                </div>
                <div class="px-5 py-3 border-t border-slate-100">
                    <a href="{{ route('complaints.index') }}" class="flex items-center justify-between text-sm text-slate-500 hover:text-primary">
                        Buat Keluhan Baru <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
