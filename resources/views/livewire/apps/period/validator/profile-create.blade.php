<div wire:ignore.self>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Tambah Profil Peserta</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form wire:submit.prevent="save" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="col-md-12 mb-3">
                                <label>Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="nama_lengkap" required>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label>NIK <span class="text-danger">*</span></label>
                                <input maxlength="16" type="text" class="form-control" wire:model="nik" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="tempat_lahir" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" wire:model="tanggal_lahir" required>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label>RT</label>
                                <input type="text" class="form-control" wire:model="rt" maxlength="3" inputmode="numeric" placeholder="001">
                                <small class="text-muted">3 digit</small>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label>RW</label>
                                <input type="text" class="form-control" wire:model="rw" maxlength="3" inputmode="numeric" placeholder="001">
                                <small class="text-muted">3 digit</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Kode Pos</label>
                                <input type="text" class="form-control" wire:model="kode_pos">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Alamat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="alamat" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Kecamatan <span class="text-danger">*</span></label>
                                <select class="form-select" wire:model.lazy="id_kec" wire:change="$refresh" required>
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach ($kecamatans as $kec)
                                        <option value="{{ $kec->id_wil }}">{{ $kec->nm_wil }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Desa / Kelurahan <span class="text-danger">*</span></label>
                                <select class="form-select"
                                        wire:model="id_kelurahan"
                                        wire:key="desa-{{ $id_kec }}"
                                        required
                                        {{ empty($kelurahans) ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Desa/Kelurahan --</option>
                                    @foreach ($kelurahans as $kel)
                                        <option value="{{ $kel->id_kelurahan }}">{{ $kel->kemendagri_kelurahan_nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Nama Ibu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="nama_ibu" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Tempat Mengajar</label>
                                <input type="text" class="form-control" wire:model="tempat_mengajar">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label>Alamat Mengajar</label>
                                <input type="text" class="form-control" wire:model="Alamat_mengajar">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Upload Foto KTP</label>
                                <input type="file" class="form-control" wire:model="fotoktp" accept="image/*">
                                @if ($fotoktp)
                                    <img src="{{ $fotoktp->temporaryUrl() }}" class="img-fluid mt-2 rounded shadow-sm" style="max-height:200px;">
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Upload Foto Diri</label>
                                <input type="file" class="form-control" wire:model="fotodiri" accept="image/*">
                                @if ($fotodiri)
                                    <img src="{{ $fotodiri->temporaryUrl() }}" class="img-fluid mt-2 rounded shadow-sm" style="max-height:200px;">
                                @endif
                            </div>

                            <div class="col-md-12 mt-3 text-center">
                                <button type="submit" class="btn btn-info">Simpan Biodata</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
