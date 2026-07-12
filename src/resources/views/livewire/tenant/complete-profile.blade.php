@section("page-title", "Profil Saya")

<div class="container py-5" style="max-width: 700px;">
    <h2 class="fw-bold mb-2">Lengkapi Profil</h2>
    <p class="text-muted mb-4">Data ini diperlukan untuk proses booking kamar.</p>

    <form wire:submit="save">

        {{-- Data Pribadi --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">Data Pribadi</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">NIK (16 digit) *</label>
                        <input wire:model="nik" type="text" maxlength="16"
                            placeholder="3271234567890001"
                            class="form-control @error('nik') is-invalid @enderror" />
                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. HP *</label>
                        <input wire:model="phone" type="tel"
                            placeholder="08123456789"
                            class="form-control @error('phone') is-invalid @enderror" />
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Kelamin *</label>
                        <select wire:model="gender"
                            class="form-select @error('gender') is-invalid @enderror">
                            <option value="">Pilih...</option>
                            <option value="male">Laki-laki</option>
                            <option value="female">Perempuan</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pekerjaan</label>
                        <input wire:model="occupation" type="text"
                            placeholder="Mahasiswa, Karyawan, dll"
                            class="form-control" />
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat Asal</label>
                        <textarea wire:model="address_origin" rows="2"
                            placeholder="Alamat sesuai KTP"
                            class="form-control"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Upload Foto KTP</label>
                        <input wire:model="ktp_file" type="file" accept="image/*"
                            class="form-control" />
                        @error('ktp_file')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        @if($ktp_file)
                            <img src="{{ $ktp_file->temporaryUrl() }}"
                                class="img-thumbnail mt-2" style="max-height: 100px;">
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Upload Foto KK (Kartu Keluarga)</label>
                        <input wire:model="kk_file" type="file" accept="image/*"
                            class="form-control" />
                        @error('kk_file')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        @if($kk_file)
                            <img src="{{ $kk_file->temporaryUrl() }}"
                                class="img-thumbnail mt-2" style="max-height: 100px;">
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Kontak Darurat --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">Kontak Darurat</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Nama *</label>
                        <input wire:model="emergency_contact_name" type="text"
                            placeholder="Nama orang tua/kerabat"
                            class="form-control @error('emergency_contact_name') is-invalid @enderror" />
                        @error('emergency_contact_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">No. HP *</label>
                        <input wire:model="emergency_contact_phone" type="tel"
                            placeholder="08123456789"
                            class="form-control @error('emergency_contact_phone') is-invalid @enderror" />
                        @error('emergency_contact_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Hubungan *</label>
                        <input wire:model="emergency_contact_relation" type="text"
                            placeholder="Orang Tua, Kakak, dll"
                            class="form-control @error('emergency_contact_relation') is-invalid @enderror" />
                        @error('emergency_contact_relation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-3">
            <span wire:loading.remove>Simpan Profil</span>
            <span wire:loading>Menyimpan...</span>
        </button>
    </form>
</div>