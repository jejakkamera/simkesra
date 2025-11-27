<div>
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-user-plus me-2"></i> Tambah Pengajuan Penerima
            </h5>
            <a href="{{ route(session('active_role') . '.PemenanganDatalist', ['periode' => $periode]) }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card-body">
            {{-- ⚠️ Pesan Periode Tidak Aktif --}}
            @if (!$periodeAktif)
                <div class="alert alert-warning fw-bold text-center">
                    {{ $periodeMessage }}
                </div>
            @endif

            {{-- ✅ Pesan sukses / error --}}
            @if (session('message'))
                <div class="alert alert-success text-center fw-bold">{{ session('message') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger text-center fw-bold">{{ session('error') }}</div>
            @endif

            {{-- Form utama --}}
            {{ $this->form }}

            {{-- ✅ Profil ditemukan --}}
            @if ($selectedProfile && !$isNewProfile)
                <div class="card mt-3 border border-success">
                    <div class="card-body">
                        <h6 class="text-success fw-bold mb-2">
                            <i class="fas fa-id-card me-1"></i> Data Penerima
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li><strong>Nama:</strong> {{ $selectedProfile->nama_lengkap }}</li>
                            <li><strong>Alamat:</strong> {{ $selectedProfile->alamat }}</li>
                            <li><strong>Kelurahan:</strong> {{ $selectedProfile->id_kelurahan }}</li>
                            <li><strong>Kecamatan:</strong> {{ $selectedProfile->kecamatan->nm_wil ?? '-' }}</li>
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Tombol Scan KTP --}}
            <div class="mt-3 text-center">
                <input type="file" wire:model="fotoKtp" accept="image/*" capture="environment" style="display:none" id="ktpInput">
                <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('ktpInput').click()">
                    📸 Scan KTP
                </button>
                <div wire:loading wire:target="fotoKtp" class="text-muted mt-2">
                    ⏳ Sedang mengirim ke server OCR...
                </div>
            </div>

            {{-- Pesan hasil OCR --}}
            @if ($nikMessage)
                <div class="alert mt-3 {{ $isNewProfile ? 'alert-warning' : 'alert-success' }}">
                    {{ $nikMessage }}
                </div>
            @endif

            {{-- Tombol Simpan --}}
            <div class="mt-4 text-end">
                <button wire:click="save" class="btn btn-success" @disabled(!$periodeAktif)>
                    <i class="fas fa-save"></i> Simpan Pengajuan
                </button>
            </div>
        </div>
    </div>

    {{-- Modal jika profil belum ada --}}
    @if ($showCreateProfileModal)
        <div key="create-profile-modal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">
                            <i class="fas fa-id-card me-2"></i> Profil Belum Ada
                        </h5>
                        <button type="button" class="btn-close" wire:click="$set('showCreateProfileModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            NIK <strong>{{ $data['nik'] ?? '-' }}</strong> belum terdaftar di sistem.
                            Apakah kamu ingin membuat profil baru sekarang?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showCreateProfileModal', false)">
                            Batal
                        </button>
                        <a href="{{ route('validator.ProfileCreate', ['nik' => $data['nik'] ?? null,'periode'=>$this->periode]) }}" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Buat Profil Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
