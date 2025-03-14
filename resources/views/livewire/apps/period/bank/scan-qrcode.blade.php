<div>
    <livewire:Apps.Period.Bank.Dashboard />
    <hr>
<meta name="csrf-token" content="your_csrf_token_value_here">
<form id="myForm" action="/submit" method="POST">
    @csrf <!-- Menyertakan token CSRF dalam formulir -->
</form>
    <h1>QR Code Scanner</h1>
    <select id="camera-list"></select>
    <button class="btn btn-outline-primary" onclick="changeCamera()">Change Camera</button>
    <button class="btn btn-outline-info" onclick="startCamera()">Start Camera</button>

    <br>
    <br>
        <video id="qr-video" width="60%" height="auto" playsinline></video>
    <div id="result"></div>

     <script src="{{ asset('') }}js/jsQR.min.js"></script>
     <script>
        let currentStream;
        let currentCameraId;
        const video = document.getElementById("qr-video");
        const resultDiv = document.getElementById("result");
        const cameraList = document.getElementById("camera-list");
        const csrfToken = document.getElementById('myForm').querySelector('input[name="_token"]').value;

        document.addEventListener("DOMContentLoaded", function () {
            enumerateCameras();
            const savedCameraId = getSavedCameraId();
            if (savedCameraId) {
                startCamera(savedCameraId);
            }

            window.changeCamera = function () {
                const selectedCameraId = cameraList.value;
                stopCamera();
                startCamera(selectedCameraId);
                saveSelectedCameraId(selectedCameraId);
            };


        });

        function enumerateCameras() {
            navigator.mediaDevices.enumerateDevices()
                .then(devices => {
                    const videoDevices = devices.filter(device => device.kind === 'videoinput');
                    if (videoDevices.length > 0) {
                        videoDevices.forEach(device => {
                            const option = document.createElement("option");
                            option.value = device.deviceId;
                            option.text = device.label || `Camera ${cameraList.options.length + 1}`;
                            const savedCameraId = getSavedCameraId();
                            if (device.deviceId === savedCameraId) {
                                option.selected = true;
                            }
                            cameraList.appendChild(option);
                        });
                        const selectedCameraId = getSavedCameraId();
                        if (selectedCameraId) {
                            startCamera(selectedCameraId);
                        }
                        // startCamera(videoDevices[0].deviceId); // Start with the first camera
                    } else {
                        console.error("No video devices found.");
                    }
                })
                .catch((error) => {
                    console.error("Error enumerating devices:", error);
                });
        }

        function saveSelectedCameraId(deviceId) {
            localStorage.setItem('selectedCameraId', deviceId);
        }

        // Fungsi untuk mengambil deviceId dari penyimpanan lokal
        function getSavedCameraId() {
            return localStorage.getItem('selectedCameraId');
        }

        function startCamera(deviceId) {
            const constraints = {
                video: {
                    deviceId: { exact: deviceId },
                },
            };

            navigator.mediaDevices.getUserMedia(constraints)
                .then((stream) => {
                    video.srcObject = stream;
                    currentStream = stream;

                    video.addEventListener("loadedmetadata", () => {
                        video.play();
                        detectQRCode();
                    });
                })
                .catch((error) => {
                    console.error("Error accessing camera:", error);
                });
        }

        function stopCamera() {
            if (currentStream) {
                const tracks = currentStream.getTracks();
                tracks.forEach(track => track.stop());
                video.srcObject = null;
            }
        }

        function detectQRCode() {
            try {
                const canvas = document.createElement("canvas");
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const context = canvas.getContext("2d");
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, canvas.width, canvas.height);

                if (code.data) {

                    const content = code.data;
                    stopCamera();
                    showLoadingAlert();
                    sendDataToServer(content);

                }


            } catch (error) {
                // console.error("Error detecting QR Code:", error);
            }

            requestAnimationFrame(detectQRCode);

        }

        function sendDataToServer(data) {
            // Use AJAX to send data to the server
            // Replace the URL with your server endpoint
            const url = "{{ url(strtolower(auth()->user()->role).'/apps/qr/scan-qr') }}";

            const xhr = new XMLHttpRequest();
            xhr.open("POST", url, true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);
            if (data !== undefined && data !== null) {
            const parts = data.split('|');
            if (parts.length === 2) {
                // Mendapatkan nilai dari pendaftar dan periode
                const pendaftarValue = parts[0].split(':')[1].trim();
                const periodeValue = parts[1].split(':')[1].trim();

                const jsonData = JSON.stringify({ pendaftar: pendaftarValue,
                                                    periode: periodeValue });
                resultDiv.innerHTML = `<p>Detected QR Code: ${pendaftarValue}</p>`;

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        // Handle the response from the server if needed
                        console.log(xhr.responseText);

                        // Close SweetAlert loading after the data is sent
                        closeLoadingAlert();
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);

                            if (response.success) {
                                // Jika success true, arahkan ke halaman biodata
                                window.location.href = response.redirectUrl;
                            } else {
                                // Jika success false, tampilkan pesan
                                alert(response.message);
                            }
                        } else {
                            // Tangani kesalahan lainnya jika ada
                            console.error("Error:", xhr.status, xhr.statusText);
                        }
                    }
                };

                xhr.send(jsonData);

            }else{
                alert("ID QR Salah");
                closeLoadingAlert();
                startCamera();
            }
        }else{
            alert("Invalid QR Code format");
            closeLoadingAlert();
            startCamera();
        }
        }

        function showLoadingAlert() {
            Swal.fire({
                title: 'Loading...',
                html: 'Please wait.',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        function closeLoadingAlert() {
            Swal.close();
        }
    </script>
</div>
