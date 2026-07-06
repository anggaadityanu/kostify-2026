<?php

namespace App\Livewire\Tenant;

use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompleteProfile extends Component
{
    use WithFileUploads;
    public string $nik = '';
    public string $phone = '';
    public string $gender = '';
    public string $occupation = '';
    public string $address_origin = '';
    public string $emergency_contact_name = '';
    public string $emergency_contact_phone = '';
    public string $emergency_contact_relation = '';
    public $ktp_file = null;
    public $kk_file = null;

    /**
     * Load data existing kalau sudah pernah isi
     */
    public function mount(): void
    {
        $tenant = Auth::user()->tenant;
        if ($tenant) {
            $this->nik = $tenant->nik ?? '';
            $this->phone = $tenant->phone ?? '';
            $this->gender = $tenant->gender ?? '';
            $this->occupation = $tenant->occupation ?? '';
            $this->address_origin = $tenant->address_origin ?? '';
            $this->emergency_contact_name = $tenant->emergency_contact_name ?? '';
            $this->emergency_contact_phone = $tenant->emergency_contact_phone ?? '';
            $this->emergency_contact_relation = $tenant->emergency_contact_relation ?? '';
        }
    }

    /**
     * Simpan data profil tenant
     * Logika: validasi → simpan/update → redirect dashboard
     */
    public function save(): void
    {
        $this->validate([
            'nik' => 'required|digits:16',
            'phone' => 'required|min:10',
            'gender' => 'required|in:male,female',
            'emergency_contact_name' => 'required',
            'emergency_contact_phone' => 'required',
            'emergency_contact_relation' => 'required',
            'ktp_file' => 'nullable|image|max:2048',
            'kk_file' => 'nullable|image|max:2048',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus 16 digit.',
            'phone.required' => 'No. HP wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'ktp_file.image' => 'File KTP harus berupa gambar.',
            'ktp_file.max' => 'File KTP maksimal 2MB.',
            'kk_file.image' => 'File KK harus berupa gambar.',
            'kk_file.max' => 'File KK maksimal 2MB.',
        ]);

        $tenant = Auth::user()->tenant;
        $ktpPath = $tenant?->ktp_file;
        $kkPath = $tenant?->kk_file;

        if ($this->ktp_file) {
            if ($ktpPath) {
                Storage::disk('public')->delete($ktpPath);
            }

            $ktpPath = $this->ktp_file->store('tenant-documents/ktp', 'public');
        }

        if ($this->kk_file) {
            if ($kkPath) {
                Storage::disk('public')->delete($kkPath);
            }

            $kkPath = $this->kk_file->store('tenant-documents/kk', 'public');
        }

        Tenant::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'nik' => $this->nik,
                'phone' => $this->phone,
                'gender' => $this->gender,
                'occupation' => $this->occupation,
                'address_origin' => $this->address_origin,
                'emergency_contact_name' => $this->emergency_contact_name,
                'emergency_contact_phone' => $this->emergency_contact_phone,
                'emergency_contact_relation' => $this->emergency_contact_relation,
                'ktp_file' => $ktpPath,
                'kk_file' => $kkPath,
            ]
        );

        session()->flash('success', 'Profil berhasil dilengkapi!');
        $this->redirect(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.tenant.complete-profile')
            ->layout('layouts.makaan');
    }
}
