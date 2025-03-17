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
            <select id="camera-list"></select>
            <button class="btn btn-outline-primary" onclick="changeCamera()">Change Camera</button>
            <button class="btn btn-outline-info" onclick="startCamera()">Start Camera</button>
            <hr>
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
                Sekema : {{ $pemenangan->skema->judul }} <br>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
            @php
                }
            @endphp
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    // Referensi ke elemen dropdown kamera
    const cameraList = document.getElementById("camera-list");

    // Fungsi untuk menyimpan kamera yang dipilih ke localStorage
    function saveSelectedCameraId(cameraId) {
        localStorage.setItem('selectedCameraId', cameraId);
    }

    // Fungsi untuk mengambil kamera yang dipilih dari localStorage
    function getSavedCameraId() {
        return localStorage.getItem('selectedCameraId');
    }

    // Fungsi enumerasi semua kamera yang tersedia
    function enumerateCameras() {
        navigator.mediaDevices.enumerateDevices()
            .then(devices => {
                const videoDevices = devices.filter(device => device.kind === 'videoinput');
                cameraList.innerHTML = ''; // Kosongkan list supaya tidak duplikat

                if (videoDevices.length > 0) {
                    videoDevices.forEach((device, index) => {
                        const option = document.createElement("option");
                        option.value = device.deviceId;
                        option.text = device.label || `Camera ${index + 1}`;
                        cameraList.appendChild(option);
                    });

                    const savedCameraId = getSavedCameraId();

                    if (savedCameraId && videoDevices.find(d => d.deviceId === savedCameraId)) {
                        startCamera(savedCameraId);
                        cameraList.value = savedCameraId;
                    } else {
                        startCamera(videoDevices[0].deviceId);
                    }
                } else {
                    console.error("No video devices found.");
                }
            })
            .catch((error) => {
                console.error("Error enumerating devices:", error);
            });
    }

    // Fungsi untuk mulai kamera
    function startCamera(cameraId) {
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 70,
            constraints: {
                deviceId: cameraId ? { exact: cameraId } : undefined
            }
        });

        Webcam.attach('#webcam-container');
        Webcam.attach('#webcam-container-foto-diri');
    }

    // Fungsi untuk stop kamera (tidak wajib untuk WebcamJS, tapi didefinisikan kalau mau dikembangkan)
    function stopCamera() {
        Webcam.reset();
    }

    // Fungsi untuk mengganti kamera ketika user memilih dari dropdown
    window.changeCamera = function () {
        const selectedCameraId = cameraList.value;
        stopCamera();
        startCamera(selectedCameraId);
        saveSelectedCameraId(selectedCameraId);
    };

    // Inisialisasi kamera setelah izin diberikan
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(() => enumerateCameras())
        .catch(err => console.error("Camera permission denied!", err));

    // Konfigurasi WebcamJS untuk KTP
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 70
    });
    Webcam.attach('#webcam-container');

    // Tombol ambil foto KTP
    document.getElementById('capture-button-ktp').addEventListener('click', function () {
        Webcam.snap(function (data_uri) {
            document.getElementById('snapshot-ktp').src = data_uri;
            document.getElementById('snapshot-ktp').style.display = 'block';
            document.getElementById('ktp_image').value = data_uri;
        });
    });

    // Konfigurasi WebcamJS untuk Foto Diri
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 70
    });
    Webcam.attach('#webcam-container-foto-diri');

    // Tombol ambil foto diri
    document.getElementById('capture-button-foto-diri').addEventListener('click', function () {
        Webcam.snap(function (data_uri) {
            document.getElementById('snapshot-foto-diri').src = data_uri;
            document.getElementById('snapshot-foto-diri').style.display = 'block';
            document.getElementById('foto_diri_image').value = data_uri;
        });
    });

    // Validasi sebelum formaction submit
    document.querySelector("#formaction").addEventListener("submit", function (event) {
        event.preventDefault(); // Stop auto submit

        var verifTeller = document.querySelector('select[name="verif_teller"]').value;
        var noRekening = document.querySelector('input[name="no_rekening"]').value;

        var fotoDiriImage = document.querySelector('#snapshot-foto-diri');
        var isFotoDiriEmpty = !fotoDiriImage || fotoDiriImage.src === "" || fotoDiriImage.src === "data:," || fotoDiriImage.style.display === 'none';

        var fotoktpImage = document.querySelector('#snapshot-ktp');
        var isKtpImageEmpty = !fotoktpImage || fotoktpImage.src === "" || fotoktpImage.src === "data:," || fotoktpImage.style.display === 'none';

        // Debug console
        console.log("Verif Teller:", verifTeller);
        console.log("No Rekening:", noRekening);
        console.log("Foto Diri kosong:", isFotoDiriEmpty);
        console.log("KTP kosong:", isKtpImageEmpty);

        // Validasi input form
        if (verifTeller === "" || noRekening === "" || isFotoDiriEmpty || isKtpImageEmpty) {
            Swal.fire({
                title: 'Gagal!',
                text: 'Semua field harus diisi! Pastikan Anda sudah mengupload foto KTP dan Foto Diri.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        } else {
            Swal.fire({
                title: 'Penyaluran Dana',
                html: `Konfirmasi proses penyaluran. Apakah Anda yakin data ini sudah sesuai? Pastikan kembali data sesuai.<hr>
                <ul>
                    <li>Besaran dana: <h1 style="color: red;">Rp. {{ number_format($pemenangan->skema->nominal) }}</h1></li>
                    <li>Kepada: <h3 style="color: green;">{{ $pemenangan->nama_lengkap }}</h3></li>
                    <li>No. NIK: <h3 style="color: green;">{{ $pemenangan->nik }}</h3></li>
                </ul>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim form jika user konfirmasi
                    document.querySelector("#formaction").submit();
                } else {
                    Swal.fire({
                        title: 'Dibatalkan',
                        text: 'Proses dibatalkan!',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
</script>



</div>
