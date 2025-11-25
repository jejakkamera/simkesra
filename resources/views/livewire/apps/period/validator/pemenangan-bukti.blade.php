<div>
    {{-- 🔔 Notifikasi --}}
    @if (session('error'))
        <div class="alert alert-warning text-center fw-bold">{{ session('error') }}</div>
    @endif

    @if (session('message'))
        <div class="alert alert-success text-center fw-bold">{{ session('message') }}</div>
    @endif

    @if ($pemenangan && $pemenangan->id)
    <div class="card shadow-sm border-0">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-user me-2"></i> Detail Penerima
            </h5>
            <a href="{{ route(session('active_role') . '.PemenanganDatalist', ['periode' => $this->periode]) }}" 
               class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card-body p-4">

            {{-- ================================== --}}
            {{-- 👤 DATA PROFIL PENERIMA --}}
            {{-- ================================== --}}
            <h6 class="fw-bold text-primary mb-2">
                <i class="fas fa-id-card me-1"></i> Data Profil Penerima
            </h6>

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <table class="table table-sm align-middle mb-2">
                        <tr><th class="text-muted small w-40">NIK</th><td>{{ $pemenangan->profile->nik ?? '-' }}</td></tr>
                        <tr><th class="text-muted small">Nama Lengkap</th><td>{{ $pemenangan->profile->nama_lengkap ?? '-' }}</td></tr>
                        <tr>
                            <th class="text-muted small">Tempat, Tanggal Lahir</th>
                            <td>{{ $pemenangan->profile->tempat_lahir ?? '-' }}, 
                                {{ $pemenangan->profile->tanggal_lahir ? \Carbon\Carbon::parse($pemenangan->profile->tanggal_lahir)->format('d M Y') : '-' }}
                            </td>
                        </tr>
                        <tr><th class="text-muted small">Alamat</th><td>{{ $pemenangan->profile->alamat ?? '-' }}</td></tr>
                        <tr><th class="text-muted small">RT/RW</th><td>{{ $pemenangan->profile->rt ?? '-' }}/{{ $pemenangan->profile->rw ?? '-' }}</td></tr>
                    </table>
                </div>

                <div class="col-md-6 col-sm-12">
                    <table class="table table-sm align-middle mb-2">
                        <tr><th class="text-muted small">Kelurahan</th><td>{{ $pemenangan->profile->kelurahan->kemendagri_kelurahan_nama ?? '-' }}</td></tr>
                        <tr><th class="text-muted small">Kecamatan</th><td>{{ $pemenangan->profile->kecamatan->nm_wil ?? '-' }}</td></tr>
                        <tr><th class="text-muted small">Kode Pos</th><td>{{ $pemenangan->profile->kode_pos ?? '-' }}</td></tr>
                        <tr><th class="text-muted small">Tempat Mengajar</th><td>{{ $pemenangan->profile->tempat_mengajar ?? '-' }}</td></tr>
                        <tr><th class="text-muted small">Nama Ibu</th><td>{{ $pemenangan->profile->nama_ibu ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>

            {{-- ================================== --}}
            {{-- 🧾 FORM 1: FOTO IDENTITAS --}}
            {{-- ================================== --}}
            <hr>
            <h6 class="fw-bold text-primary mb-2">
                <i class="fas fa-camera me-1"></i> Foto Identitas
            </h6>

            <form wire:submit.prevent="saveIdentitas">
                <div class="row g-3">
                    {{-- FOTO KTP --}}
                    <div class="col-md-6 text-center">
                        <div class="card border">
                            <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
                                📄 Foto KTP
                                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="toggleKTP">
                                    @if($showKTP)
                                        <i class="fas fa-eye-slash"></i> Sembunyikan
                                    @else
                                        <i class="fas fa-eye"></i> Tampilkan
                                    @endif
                                </button>
                            </div>

                            <div class="card-body">
                                @if ($showKTP)
                                    @if ($foto_ktp)
                                        <img src="{{ $foto_ktp->temporaryUrl() }}" class="bukti-img mb-2">
                                    @elseif ($pemenangan->profile->fotoktp)
                                        <img src="{{ asset('storage/' . $pemenangan->profile->fotoktp) }}" class="bukti-img mb-2">
                                    @else
                                        <span class="text-muted">Belum ada foto KTP</span>
                                    @endif
                                @else
                                    <span class="text-muted d-block mb-2">Foto KTP disembunyikan</span>
                                @endif

                                <input type="file" accept="image/*" capture="camera" wire:model="foto_ktp" class="form-control mt-2">
                            </div>
                        </div>
                    </div>


                    {{-- FOTO DIRI --}}
                    <div class="col-md-6 text-center">
                        <div class="card border">
                            <div class="card-header bg-light fw-bold">🤳 Foto Diri</div>
                            <div class="card-body">
                                @if ($foto_diri)
                                    <img src="{{ $foto_diri->temporaryUrl() }}" class="bukti-img mb-2">
                                @elseif ($pemenangan->profile->fotodiri)
                                    <img src="{{ asset('storage/' . $pemenangan->profile->fotodiri) }}" class="bukti-img mb-2">
                                @else
                                    <span class="text-muted">Belum ada foto diri</span>
                                @endif

                                <input type="file" accept="image/*" capture="camera" wire:model="foto_diri" class="form-control mt-2">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-success fw-bold px-4">
                        <i class="fas fa-save"></i> Simpan Identitas
                    </button>
                </div>
            </form>

            <hr class="my-3">

            {{-- ================================== --}}
            {{-- 💰 INFORMASI BANTUAN --}}
            {{-- ================================== --}}
            <h6 class="fw-bold text-primary mb-2">
                <i class="fas fa-coins me-1"></i> Informasi Bantuan
            </h6>

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <table class="table table-sm align-middle mb-1">
                        <tr><th class="text-muted small">Skema Bantuan</th><td>{{ $pemenangan->skema->judul ?? '-' }}</td></tr>
                        <tr><th class="text-muted small">Periode</th><td>{{ $pemenangan->period->name_period ?? '-' }}</td></tr>
                        <tr><th class="text-muted small">Status Verifikasi</th>
                            <td>
                                @if ($pemenangan->verif_teller === 'Selesai')
                                    <span class="badge bg-success">Terverifikasi</span>
                                @else
                                    <span class="badge bg-warning text-dark">Diajukan</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6 col-sm-12">
                    <table class="table table-sm align-middle mb-1">
                        <tr><th class="text-muted small">Tanggal Verifikasi</th>
                            <td>{{ $pemenangan->tanggal_verif_teller ? \Carbon\Carbon::parse($pemenangan->tanggal_verif_teller)->format('d M Y') : '-' }}</td>
                        </tr>
                        <tr><th class="text-muted small">No Rekening</th><td>{{ $pemenangan->no_rekening ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>

            <hr class="my-3">

            {{-- ================================== --}}
            {{-- 📸 FORM 2: BUKTI KEGIATAN --}}
            {{-- ================================== --}}
            <h6 class="fw-bold text-primary mb-2">
                <i class="fas fa-camera-retro me-1"></i> Bukti Kegiatan
            </h6>

            <div class="card border mb-4">
                <div class="card-header bg-light fw-bold">
                    ✏️ Upload / Perbarui Bukti Kegiatan
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="saveBukti">
                        <div class="row g-3">
                            {{-- FOTO 1 --}}
                            <div class="col-md-4 text-center">
                                <label class="form-label fw-bold">Foto Kegiatan 1</label>
                                <input type="file" accept="image/*" capture="camera" wire:model="foto_kegiatan_1" class="form-control">
                                @if ($foto_kegiatan_1)
                                    <img src="{{ $foto_kegiatan_1->temporaryUrl() }}" class="bukti-img mt-2">
                                @elseif ($pemenangan->foto_kegiatan_1)
                                    <img src="{{ asset('storage/' . $pemenangan->foto_kegiatan_1) }}" class="bukti-img mt-2">
                                @else
                                    <span class="text-muted d-block mt-2">Belum ada foto</span>
                                @endif
                            </div>

                            {{-- FOTO 2 --}}
                            <div class="col-md-4 text-center">
                                <label class="form-label fw-bold">Foto Kegiatan 2</label>
                                <input type="file" accept="image/*" capture="camera" wire:model="foto_kegiatan_2" class="form-control">
                                @if ($foto_kegiatan_2)
                                    <img src="{{ $foto_kegiatan_2->temporaryUrl() }}" class="bukti-img mt-2">
                                @elseif ($pemenangan->foto_kegiatan_2)
                                    <img src="{{ asset('storage/' . $pemenangan->foto_kegiatan_2) }}" class="bukti-img mt-2">
                                @else
                                    <span class="text-muted d-block mt-2">Belum ada foto</span>
                                @endif
                            </div>

                            {{-- SURAT TUGAS --}}
                            <div class="col-md-4 text-center">
                                <label class="form-label fw-bold">Surat Tugas / Keterangan</label>
                                <input type="file" accept="image/*,application/pdf" capture="camera" wire:model="foto_surat_tugas" class="form-control">
                                @if ($foto_surat_tugas)
                                    @if ($foto_surat_tugas->getClientOriginalExtension() === 'pdf')
                                        <span class="text-success d-block mt-2"><i class="fas fa-file-pdf"></i> PDF siap diunggah</span>
                                    @else
                                        <img src="{{ $foto_surat_tugas->temporaryUrl() }}" class="bukti-img mt-2">
                                    @endif
                                @elseif ($pemenangan->foto_surat_tugas)
                                    @if (Str::endsWith($pemenangan->foto_surat_tugas, '.pdf'))
                                        <a href="{{ asset('storage/' . $pemenangan->foto_surat_tugas) }}" target="_blank" class="d-block mt-2">
                                            <i class="fas fa-file-pdf"></i> Lihat Surat Tugas (PDF)
                                        </a>
                                    @else
                                        <img src="{{ asset('storage/' . $pemenangan->foto_surat_tugas) }}" class="bukti-img mt-2">
                                    @endif
                                @else
                                    <span class="text-muted d-block mt-2">Belum ada file</span>
                                @endif
                            </div>
                        </div>

                        <div wire:loading wire:target="foto_kegiatan_1, foto_kegiatan_2, foto_surat_tugas" class="text-center text-primary mt-3">
                            <i class="fas fa-spinner fa-spin"></i> Mengunggah file...
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="fas fa-upload"></i> Simpan Bukti Kegiatan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 📝 KETERANGAN --}}
            <h6 class="fw-bold text-primary mt-4 mb-2">📝 Keterangan</h6>
            <p>{{ $pemenangan->keterangan ?? 'Tidak ada keterangan tambahan.' }}</p>

            <div class="mt-4 text-center">
                <a href="{{ route(session('active_role') . '.PemenanganDatalist', ['periode' => $this->periode]) }}"
                   class="btn btn-outline-primary px-4 fw-bold">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
    @endif


<style nonce="@cspNonce">
    th { width: 40%; }
    .table-sm td, .table-sm th { padding: 0.25rem 0.5rem !important; }
    hr.my-3 { margin-top: 0.8rem !important; margin-bottom: 1rem !important; }

    /* 🔹 Semua gambar bukti */
    .bukti-img {
        width: 100%;
        height: 180px;
        object-fit: contain;
        object-position: center;
        background-color: #f8f9fa;
        border-radius: 6px;
        padding: 4px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }


    /* Responsif di mobile */
    @media (max-width: 768px) {
        .bukti-img {
            height: 150px;
        }
    }

    /* Efek hover elegan di desktop */
    .bukti-img:hover {
        transform: scale(1.01);
        transition: all 0.2s ease-in-out;
    }
</style>

</div>