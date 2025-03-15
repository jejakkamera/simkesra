<div>
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
    
                    <form id="formbiodata" wire:submit="save" method="POST" enctype="multipart/form-data">
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
                            <div>
                                <label for="nik">Desa<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="desa" wire:model="desa"
                                      value="{{ old('desa',$pemenangan->desa) }}" required>
                            </div>
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
    </div>
</div>
