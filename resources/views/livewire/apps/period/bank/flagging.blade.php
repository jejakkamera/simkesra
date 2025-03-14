<div>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/webcamjs@1.0.25/webcam.min.js"></script>

    @php
        if($pemenangan->verif_teller=='Selesai'){
    @endphp
            <div class="card bg-warning text-white p-3">
                <figure class="p-3 mb-0">
                <blockquote class="blockquote">
                    <p>Data Sudah Divalidasi.</p>
                </blockquote>
                    {{ $pemenangan->id_verif_teller }} - {{ $pemenangan->tanggal_verif_teller }} : Rekening Pencairan {{ $pemenangan->rek_pencairan }}

                </figure>
            </div>
            <hr>
    @php
        }
    @endphp
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

                <form id="formbiodata" action="{{ $updatebiodata }}" method="POST" enctype="multipart/form-data">
                    @csrf
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div>
                            <label for="nik">Nama <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                  value="{{ old('nama_lengkap',$pemenangan->nama_lengkap) }}" required>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div>
                            <label for="nik">NIK <span class="text-danger">*</span> </label>
                            <input maxlength="16" type="text" class="form-control" id="nik" name="nik"
                                  value="{{ old('nik',$pemenangan->nik) }}" required>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div>
                            <label for="nik">Tempat Lahir <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                                  value="{{ old('tempat_lahir',$pemenangan->tempat_lahir) }}" required>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div>
                            <label for="nik">Tanggal Lahir <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                  value="{{ old('tanggal_lahir',$pemenangan->tanggal_lahir) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="nik">Alamat<span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" id="alamat" name="alamat"
                                  value="{{ old('alamat',$pemenangan->alamat) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="nik">Desa<span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" id="desa" name="desa"
                                  value="{{ old('desa',$pemenangan->desa) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="nik">Nama Wilayah<span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" id="nim" name="name"
                                  disabled value="{{ old('nm_wil',$pemenangan->nm_wil) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="nik">Nama Ibu<span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" id="nama_ibu" name="nama_ibu"
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
                <h5 class="card-title">Validate Teller</h5>
            </div>
            <div class="card-body">
                
            @php
                if($pemenangan->verif_teller!='Selesai'){
            @endphp
            <form id="formaction" action="{{ $action }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-2">
                    <label for="verif_teller" class="form-label">Validasi <span class="text-danger">*</span></label>
                    <select name="verif_teller" class="form-select" required>
                        <option value="">Pilih Validasi</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label for="no_rekening">Rekening Pencairan</label>
                    <input value="{{ $pemenangan->no_rekening }}" type="text" class="form-control" name="no_rekening" id="no_rekening" required>
                </div>

                <!-- Webcam Capture KTP -->
                <div class="mb-2">
                    <label for="ktp">Foto KTP</label>
                    <div id="webcam-container"></div>
                    <button type="button" id="capture-button-ktp" class="btn btn-primary mt-2">Ambil Foto KTP</button>
                    <div id="output-ktp">
                        <h3>Foto KTP</h3>
                        <img id="snapshot-ktp" src="" alt="Snapshot" class="img-fluid" style="max-width: 100%; display: none;">
                    </div>
                    <input type="hidden" name="ktp_image" id="ktp_image">
                </div>

                <!-- Webcam Capture Foto Diri -->
                <div class="mb-2">
                    <label for="foto_diri">Foto Diri</label>
                    <div id="webcam-container-foto-diri"></div>
                    <button type="button" id="capture-button-foto-diri" class="btn btn-primary mt-2">Ambil Foto Diri</button>
                    <div id="output-foto-diri">
                        <h3>Foto Diri</h3>
                        <img id="snapshot-foto-diri" src="" alt="Snapshot" class="img-fluid" style="max-width: 100%; display: none;">
                    </div>
                    <input type="hidden" name="foto_diri_image" id="foto_diri_image">
                </div>

                <h2>Rp. {{ number_format($pemenangan->skema->nominal)  }}</h2>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
            @php
                }
            @endphp
            </div>
        </div>
    </div>
</div>
<script>
    // Initialize WebcamJS for KTP capture
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 70
    });
    
    Webcam.attach('#webcam-container');
    
    document.getElementById('capture-button-ktp').addEventListener('click', function() {
        Webcam.snap(function(data_uri) {
            document.getElementById('snapshot-ktp').src = data_uri;
            document.getElementById('snapshot-ktp').style.display = 'block';
            // Save the image as a hidden input field value
            document.getElementById('ktp_image').value = data_uri;
        });
    });

    // Initialize WebcamJS for Foto Diri capture
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 70
    });
    
    Webcam.attach('#webcam-container-foto-diri');
    
    document.getElementById('capture-button-foto-diri').addEventListener('click', function() {
        Webcam.snap(function(data_uri) {
            document.getElementById('snapshot-foto-diri').src = data_uri;
            document.getElementById('snapshot-foto-diri').style.display = 'block';
            // Save the image as a hidden input field value
            document.getElementById('foto_diri_image').value = data_uri;
        });
    });
</script>

<script type="text/javascript">
    document.querySelector("#formaction").addEventListener("submit", function(event) {
        // Mencegah pengiriman form otomatis
        event.preventDefault();

        // Menangkap semua input yang harus diisi
        var verifTeller = document.querySelector('select[name="verif_teller"]').value;
        var noRekening = document.querySelector('input[name="no_rekening"]').value;
        
        // Memeriksa apakah gambar KTP telah diambil
        var fotoDiriInput = document.querySelector('#snapshot-foto-diri');
        var fotoDiriImage = document.querySelector('#snapshot-foto-diri');
        var isFotoDiriEmpty = !fotoDiriImage || fotoDiriImage.src === "" || fotoDiriImage.src === "data:," || fotoDiriImage.style.display === 'none';
        
        var fotoktpInput = document.querySelector('#snapshot-ktp');
        var fotoktpImage = document.querySelector('#snapshot-ktp');
        var isKtpImageEmpty = !fotoktpImage || fotoktpImage.src === "" || fotoktpImage.src === "data:," || fotoktpImage.style.display === 'none';



        // Menampilkan hasil pemeriksaan di konsol untuk debugging
        console.log("Foto Diri kosong: ", isFotoDiriEmpty);
        console.log("KTP kosong: ", isKtpImageEmpty);
                // Memeriksa apakah semua input sudah diisi
        if (verifTeller === "" || noRekening === "" || isFotoDiriEmpty || isKtpImageEmpty) {
            // Menampilkan SweetAlert jika ada field yang belum diisi
            Swal.fire({
                title: 'Gagal!',
                text: 'Semua field harus diisi! Pastikan Anda sudah mengupload foto KTP dan Foto Diri.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        } else {
            // Menampilkan konfirmasi SweetAlert untuk melanjutkan pengiriman form
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                html: "Data yang Anda masukkan akan disimpan.<br> Sebesar Rp. {{ number_format($pemenangan->skema->nominal) }} kepada {{  $pemenangan->nama_lengkap }} - {{ $pemenangan->nik }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pengguna menekan "Ya, Simpan", maka form akan disubmit
                    event.target.submit();
                }
            });
        }
    });
</script>


</div>
