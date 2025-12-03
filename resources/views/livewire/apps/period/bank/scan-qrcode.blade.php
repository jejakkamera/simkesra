<div>
    <livewire:Apps.Period.Bank.Dashboard />

    {{-- Hidden form for CSRF token --}}
    <form id="myForm" action="/submit" method="POST" class="d-none">
        @csrf
    </form>

    <div class="container-xxl flex-grow-1 container-p-y">
        {{-- Success Alert --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Main Scanner Card --}}
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-6">
                <div class="card shadow-lg">
                    {{-- Card Header --}}
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h4 class="mb-0">
                            <i class="fas fa-qrcode me-2"></i>
                            QR Code Scanner
                        </h4>
                        <p class="mb-0 mt-2 opacity-75 small">Arahkan kamera ke QR Code penerima bantuan</p>
                    </div>

                    <div class="card-body p-4">
                        {{-- Camera Controls --}}
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-sm-6">
                                <label for="camera-list" class="form-label fw-semibold">
                                    <i class="fas fa-camera me-1"></i> Pilih Kamera
                                </label>
                                <select id="camera-list" class="form-select form-select-lg"></select>
                            </div>
                            <div class="col-12 col-sm-6 d-flex align-items-end gap-2">
                                <button class="btn btn-outline-primary flex-grow-1" onclick="changeCamera()" id="btn-change">
                                    <i class="fas fa-sync-alt me-1"></i> Ganti
                                </button>
                                <button class="btn btn-success flex-grow-1" onclick="toggleCamera()" id="btn-toggle">
                                    <i class="fas fa-play me-1" id="toggle-icon"></i>
                                    <span id="toggle-text">Mulai</span>
                                </button>
                            </div>
                        </div>

                        {{-- Scanner View --}}
                        <div class="scanner-container position-relative rounded-4 overflow-hidden bg-dark mb-4">
                            {{-- Video Element --}}
                            <video id="qr-video" autoplay playsinline muted></video>

                            {{-- Scanning Overlay --}}
                            <div id="scan-overlay" class="scanner-overlay">
                                <div class="scan-frame">
                                    <div class="scan-line"></div>
                                </div>
                            </div>

                            {{-- Placeholder when camera is off --}}
                            <div id="camera-placeholder" class="camera-placeholder">
                                <i class="fas fa-video-slash display-1 mb-3 opacity-50"></i>
                                <p class="mb-0 opacity-75">Kamera tidak aktif</p>
                                <small class="opacity-50">Klik "Mulai" untuk mengaktifkan</small>
                            </div>
                        </div>

                        {{-- Status & Result --}}
                        <div id="scanner-status" class="text-center mb-3">
                            <span class="badge bg-secondary fs-6 px-4 py-2">
                                <i class="fas fa-qrcode me-1"></i> Siap untuk scan
                            </span>
                        </div>

                        <div id="result" class="text-center"></div>

                        {{-- Instructions --}}
                        <div class="alert alert-light border mt-4 mb-0">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-info-circle text-primary me-1"></i> Petunjuk:
                            </h6>
                            <ol class="mb-0 ps-3 small text-muted">
                                <li>Pilih kamera yang akan digunakan (belakang untuk HP)</li>
                                <li>Klik tombol <strong>"Mulai"</strong> untuk mengaktifkan kamera</li>
                                <li>Arahkan kamera ke QR Code penerima bantuan</li>
                                <li>Sistem akan otomatis mendeteksi dan memproses data</li>
                            </ol>
                        </div>

                        {{-- iPhone/iOS Instructions (Collapsible) --}}
                        <div class="alert alert-warning border mt-3 mb-0" id="ios-instructions" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center" style="cursor: pointer;" onclick="toggleIOSDetails()">
                                <h6 class="alert-heading mb-0">
                                    <i class="fab fa-apple me-1"></i> Pengguna iPhone/iPad
                                </h6>
                                <i class="fas fa-chevron-down" id="ios-toggle-icon" style="font-size: 1rem; transition: transform 0.3s;"></i>
                            </div>
                            <div id="ios-details" style="display: none; margin-top: 0.75rem;">
                                <p class="small text-muted mb-2">Jika kamera tidak muncul, ikuti langkah berikut:</p>
                                <ol class="mb-0 ps-3 small text-muted">
                                    <li>Buka <strong>Settings (Pengaturan)</strong> di iPhone</li>
                                    <li>Scroll ke bawah, cari dan tap <strong>Safari</strong></li>
                                    <li>Scroll ke bagian <strong>"Settings for Websites"</strong></li>
                                    <li>Tap <strong>Camera</strong></li>
                                    <li>Pilih <strong>"Allow"</strong> atau <strong>"Ask"</strong></li>
                                    <li>Kembali ke halaman ini dan refresh</li>
                                </ol>
                                <hr class="my-2">
                                <p class="small text-muted mb-0">
                                    <i class="fas fa-lightbulb me-1"></i> <strong>Tips:</strong> Pastikan juga Anda menekan <strong>"Allow"</strong> saat muncul popup izin kamera di Safari.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom Styles --}}
    <style>
        .scanner-container {
            min-height: 350px;
            border: 3px solid #696cff;
            position: relative;
        }

        #qr-video {
            width: 100%;
            height: 100%;
            min-height: 350px;
            object-fit: cover;
            display: block;
        }

        .scanner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: none;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .scanner-overlay.active {
            display: flex;
        }

        .camera-placeholder {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2c3e50 0%, #1a1a2e 100%);
            color: white;
        }

        .camera-placeholder.hidden {
            display: none;
        }

        .scan-frame {
            width: 250px;
            height: 250px;
            border: 3px solid #fff;
            border-radius: 20px;
            position: relative;
            box-shadow: 0 0 0 4000px rgba(0, 0, 0, 0.4);
        }

        .scan-frame::before,
        .scan-frame::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            border-color: #28c76f;
            border-style: solid;
        }

        .scan-frame::before {
            top: -3px;
            left: -3px;
            border-width: 4px 0 0 4px;
            border-radius: 20px 0 0 0;
        }

        .scan-frame::after {
            top: -3px;
            right: -3px;
            border-width: 4px 4px 0 0;
            border-radius: 0 20px 0 0;
        }

        .scan-line {
            position: absolute;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #28c76f, transparent);
            top: 0;
            animation: scan 2s linear infinite;
        }

        @keyframes scan {
            0% { top: 0; }
            50% { top: calc(100% - 3px); }
            100% { top: 0; }
        }

        .card {
            border: none;
            border-radius: 1rem;
        }

        .card-header {
            border-radius: 1rem 1rem 0 0 !important;
        }

        @media (max-width: 576px) {
            .scan-frame {
                width: 200px;
                height: 200px;
            }

            .scanner-container,
            #qr-video {
                min-height: 280px;
            }
        }
    </style>

    <script src="{{ asset('') }}js/jsQR.min.js"></script>
    <script>
        let currentStream = null;
        let isScanning = false;
        let isCameraActive = false;

        const video = document.getElementById("qr-video");
        const resultDiv = document.getElementById("result");
        const cameraList = document.getElementById("camera-list");
        const csrfToken = document.getElementById('myForm').querySelector('input[name="_token"]').value;
        const placeholder = document.getElementById("camera-placeholder");
        const scanOverlay = document.getElementById("scan-overlay");
        const scannerStatus = document.getElementById("scanner-status");
        const toggleBtn = document.getElementById("btn-toggle");
        const toggleIcon = document.getElementById("toggle-icon");
        const toggleText = document.getElementById("toggle-text");
        const iosInstructions = document.getElementById("ios-instructions");

        // Detect iOS device
        function isIOS() {
            return /iPad|iPhone|iPod/.test(navigator.userAgent) || 
                   (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
        }

        // Show iOS instructions
        function showIOSInstructions() {
            if (iosInstructions) {
                iosInstructions.style.display = 'block';
            }
        }

        // Toggle iOS details visibility
        window.toggleIOSDetails = function() {
            const details = document.getElementById('ios-details');
            const icon = document.getElementById('ios-toggle-icon');
            if (details.style.display === 'none') {
                details.style.display = 'block';
                icon.style.transform = 'rotate(180deg)';
            } else {
                details.style.display = 'none';
                icon.style.transform = 'rotate(0deg)';
            }
        };

        document.addEventListener("DOMContentLoaded", function () {
            // Show iOS instructions by default if on iOS
            if (isIOS()) {
                showIOSInstructions();
            }
            enumerateCameras();
        });

        function enumerateCameras() {
            navigator.mediaDevices.enumerateDevices()
                .then(devices => {
                    const videoDevices = devices.filter(device => device.kind === 'videoinput');
                    if (videoDevices.length > 0) {
                        cameraList.innerHTML = '';
                        videoDevices.forEach((device, index) => {
                            const option = document.createElement("option");
                            option.value = device.deviceId;
                            option.text = device.label || 'Kamera ' + (index + 1);
                            const savedCameraId = getSavedCameraId();
                            if (device.deviceId === savedCameraId) {
                                option.selected = true;
                            }
                            cameraList.appendChild(option);
                        });
                    } else {
                        updateStatus('warning', 'Tidak ada kamera ditemukan');
                    }
                })
                .catch((error) => {
                    console.error("Error enumerating devices:", error);
                    updateStatus('danger', 'Gagal mengakses daftar kamera');
                });
        }

        function saveSelectedCameraId(deviceId) {
            localStorage.setItem('selectedCameraId', deviceId);
        }

        function getSavedCameraId() {
            return localStorage.getItem('selectedCameraId');
        }

        window.toggleCamera = function() {
            if (isCameraActive) {
                stopCamera();
            } else {
                const deviceId = cameraList.value || getSavedCameraId();
                if (deviceId) {
                    startCamera(deviceId);
                } else if (cameraList.options.length > 0) {
                    startCamera(cameraList.options[0].value);
                } else {
                    updateStatus('warning', 'Tidak ada kamera tersedia');
                }
            }
        };

        window.changeCamera = function () {
            const selectedCameraId = cameraList.value;
            saveSelectedCameraId(selectedCameraId);
            if (isCameraActive) {
                stopCamera();
                setTimeout(() => startCamera(selectedCameraId), 300);
            }
        };

        function startCamera(deviceId) {
            updateStatus('warning', 'Mengakses kamera...');

            const constraints = {
                video: {
                    deviceId: deviceId ? { exact: deviceId } : undefined,
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                }
            };

            navigator.mediaDevices.getUserMedia(constraints)
                .then((stream) => {
                    console.log('Camera stream obtained');
                    currentStream = stream;
                    video.srcObject = stream;

                    video.onloadedmetadata = function() {
                        video.play().then(() => {
                            console.log('Video playing:', video.videoWidth, 'x', video.videoHeight);
                            isCameraActive = true;
                            isScanning = true;

                            // Update UI
                            placeholder.classList.add('hidden');
                            scanOverlay.classList.add('active');
                            toggleBtn.classList.remove('btn-success');
                            toggleBtn.classList.add('btn-danger');
                            toggleIcon.classList.remove('fa-play');
                            toggleIcon.classList.add('fa-stop');
                            toggleText.textContent = 'Stop';

                            updateStatus('primary', 'Mencari QR Code...');
                            detectQRCode();
                        }).catch(err => {
                            console.error('Error playing video:', err);
                            updateStatus('danger', 'Gagal memulai video');
                        });
                    };

                    saveSelectedCameraId(deviceId);
                })
                .catch((error) => {
                    console.error("Error accessing camera:", error);
                    updateStatus('danger', 'Gagal mengakses kamera');

                    // Show iOS instructions when camera access fails
                    showIOSInstructions();

                    // Custom message for iOS
                    let errorMessage = 'Izinkan akses kamera di browser Anda.';
                    let errorHtml = '';

                    if (isIOS()) {
                        errorHtml = '<p>Izinkan akses kamera di Safari.</p>' +
                            '<hr>' +
                            '<p class="text-start small mb-1"><strong>Cara mengaktifkan:</strong></p>' +
                            '<ol class="text-start small">' +
                            '<li>Buka <strong>Settings</strong> → <strong>Safari</strong></li>' +
                            '<li>Tap <strong>Camera</strong> di bagian "Settings for Websites"</li>' +
                            '<li>Pilih <strong>Allow</strong></li>' +
                            '<li>Kembali dan refresh halaman ini</li>' +
                            '</ol>';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Akses Kamera Ditolak',
                        html: errorHtml || errorMessage,
                        customClass: { confirmButton: 'btn btn-primary' },
                        buttonsStyling: false
                    });
                });
        }

        function stopCamera() {
            isScanning = false;
            isCameraActive = false;

            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
                currentStream = null;
            }
            video.srcObject = null;

            // Update UI
            placeholder.classList.remove('hidden');
            scanOverlay.classList.remove('active');
            toggleBtn.classList.remove('btn-danger');
            toggleBtn.classList.add('btn-success');
            toggleIcon.classList.remove('fa-stop');
            toggleIcon.classList.add('fa-play');
            toggleText.textContent = 'Mulai';

            updateStatus('secondary', 'Siap untuk scan');
        }

        function updateStatus(type, message) {
            const icons = {
                'primary': 'fa-search',
                'success': 'fa-check-circle',
                'warning': 'fa-exclamation-triangle',
                'danger': 'fa-times-circle',
                'secondary': 'fa-qrcode'
            };
            scannerStatus.innerHTML = '<span class="badge bg-' + type + ' fs-6 px-4 py-2"><i class="fas ' + (icons[type] || 'fa-qrcode') + ' me-1"></i> ' + message + '</span>';
        }

        function detectQRCode() {
            if (!isScanning || !video.videoWidth) {
                if (isScanning) requestAnimationFrame(detectQRCode);
                return;
            }

            try {
                const canvas = document.createElement("canvas");
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const context = canvas.getContext("2d");
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, canvas.width, canvas.height);

                if (code && code.data) {
                    isScanning = false;
                    stopCamera();
                    updateStatus('success', 'QR Code terdeteksi!');
                    showLoadingAlert();
                    sendDataToServer(code.data);
                    return;
                }
            } catch (error) {
                // Silent catch
            }

            if (isScanning) {
                requestAnimationFrame(detectQRCode);
            }
        }

        function sendDataToServer(data) {
            const url = "{{ url(strtolower(auth()->user()->role).'/apps/qr/scan-qr') }}";

            const xhr = new XMLHttpRequest();
            xhr.open("POST", url, true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);

            if (data) {
                const parts = data.split('|');
                if (parts.length === 2) {
                    const part0 = parts[0].split(':');
                    const part1 = parts[1].split(':');
                    const pendaftarValue = part0[1] ? part0[1].trim() : null;
                    const periodeValue = part1[1] ? part1[1].trim() : null;

                    if (!pendaftarValue || !periodeValue) {
                        closeLoadingAlert();
                        showError('Format QR Salah', 'Data tidak dapat dibaca dengan benar');
                        return;
                    }

                    const jsonData = JSON.stringify({
                        pendaftar: pendaftarValue,
                        periode: periodeValue
                    });

                    resultDiv.innerHTML = '<div class="alert alert-info"><i class="fas fa-user me-1"></i> ID Penerima: <strong>' + pendaftarValue + '</strong></div>';

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            closeLoadingAlert();

                            if (xhr.status === 200) {
                                try {
                                    const response = JSON.parse(xhr.responseText);
                                    if (response.success) {
                                        window.location.href = response.redirectUrl;
                                    } else {
                                        showError('Perhatian', response.message, 'warning');
                                    }
                                } catch(e) {
                                    showError('Error', 'Response tidak valid');
                                }
                            } else {
                                showError('Error', 'Terjadi kesalahan saat memproses data');
                            }
                        }
                    };

                    xhr.send(jsonData);
                } else {
                    closeLoadingAlert();
                    showError('Format QR Salah', 'QR Code tidak sesuai format yang diharapkan');
                }
            } else {
                closeLoadingAlert();
                showError('QR Code Tidak Valid', 'Tidak dapat membaca data dari QR Code');
            }
        }

        function showError(title, text, icon) {
            icon = icon || 'error';
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                customClass: { confirmButton: 'btn btn-primary' },
                buttonsStyling: false
            }).then(() => {
                toggleCamera();
            });
        }

        function showLoadingAlert() {
            Swal.fire({
                title: 'Memproses...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        function closeLoadingAlert() {
            Swal.close();
        }
    </script>
</div>
