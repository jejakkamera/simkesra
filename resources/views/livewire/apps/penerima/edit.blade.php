<div wire:ignore.self>
    <div class="row">

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Detail Profil Peserta : <b><h4>{{ ($pemenangan->nama_lengkap) }}</h4></b>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
    
                    {{-- <form id="formbiodata" wire:submit="save" method="POST" enctype="multipart/form-data"> --}}
                        <form id="formbiodata" wire:submit.prevent="save">

                        @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div>
                                <label for="nik">Nama <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="nama_lengkap" wire:model="nama_lengkap" required>

                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div>
                                <label for="nik">NIK <span class="text-danger">*</span> </label>
                                <input maxlength="16" type="text" class="form-control" id="nik" wire:model="nik"
                                      value="{{ old('nik',$pemenangan->nik) }}" required>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div>
                                <label for="nik">Tempat Lahir <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="tempat_lahir" wire:model="tempat_lahir"
                                      value="{{ old('tempat_lahir',$pemenangan->tempat_lahir) }}" required>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div>
                                <label for="nik">Tanggal Lahir <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="tanggal_lahir" wire:model="tanggal_lahir"
                                      value="{{ old('tanggal_lahir',$pemenangan->tanggal_lahir) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div>
                                <label for="nik">Alamat<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="alamat" wire:model="alamat"
                                      value="{{ old('alamat',$pemenangan->alamat) }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="id_kec">Kecamatan <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_kec"
                                    wire:model.lazy="id_kec"
                                    wire:change="$refresh"
                                    required>
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach ($kecamatans as $kec)
                                    <option value="{{ $kec->id_wil }}">{{ $kec->nm_wil }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="id_kelurahan">Desa / Kelurahan <span class="text-danger">*</span></label>
                            <select class="form-select" 
                                    id="id_kelurahan" 
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
                            <div>
                                <label for="nik">Nama Ibu<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="nama_ibu" wire:model="nama_ibu"
                                      value="{{ old('nama_ibu',$pemenangan->nama_ibu) }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info">Update Biodata</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Foto
                </div>
                <div class="card-body">
                   
    
                   
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Foto KTP</label>
                            <div class="border p-3 rounded shadow-sm text-center">
                                <img src="{{ url('storage/'.$pemenangan->fotoktp) }}" alt="Foto KTP" 
                                    class="img-fluid rounded" 
                                    style="max-height: 300px; object-fit: cover;">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Foto Diri</label>
                            <div class="border p-3 rounded shadow-sm text-center">
                                <img src="{{ url('storage/'.$pemenangan->fotodiri) }}" alt="Foto KTP" 
                                    class="img-fluid rounded" 
                                    style="max-height: 300px; object-fit: cover;">
                            </div>
                        </div>
                        
                        
               
                    </div>
                </div>
            </div>
        </div>

    </div>
   
</div>
