<div>
    <livewire:Apps.Period.Bank.Dashboard />

    {{-- PivotTable CSS --}}
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/pivot.min.css'>

    <div class="container-xxl flex-grow-1 container-p-y">
        {{-- Report Card --}}
        <div class="card shadow-sm">
            {{-- Card Header --}}
            <div class="card-header bg-primary text-white py-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div>
                        <h5 class="mb-1">
                            <i class="fas fa-chart-bar me-2"></i> Laporan Pivot Data Penerima
                        </h5>
                        <small class="opacity-75">Analisis data dengan drag & drop</small>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-light btn-sm" onclick="exportTableToExcel('pvtTable')">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
                        </button>
                    </div>
                </div>
            </div>

            {{-- Preset Management --}}
            <div class="card-body border-bottom bg-light py-3">
                <div class="row g-3 align-items-end">
                    {{-- Preset Selection --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label fw-semibold small text-muted">
                            <i class="fas fa-bookmark me-1"></i> Preset Tersimpan
                        </label>
                        <select id="preset-select" class="form-select">
                            <option value="">-- Pilih Preset --</option>
                        </select>
                    </div>

                    {{-- Preset Name Input --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label fw-semibold small text-muted">
                            <i class="fas fa-edit me-1"></i> Nama Preset Baru
                        </label>
                        <input type="text" id="preset-name" class="form-control" placeholder="Contoh: Laporan Bulanan">
                    </div>

                    {{-- Action Buttons --}}
                    <div class="col-12 col-md-4">
                        <div class="btn-group w-100" role="group">
                            <button class="btn btn-success" onclick="savePreset()" title="Simpan konfigurasi saat ini">
                                <i class="fas fa-save me-1"></i> Simpan
                            </button>
                            <button class="btn btn-primary" onclick="loadPreset()" title="Muat preset yang dipilih">
                                <i class="fas fa-upload me-1"></i> Muat
                            </button>
                            <button class="btn btn-danger" onclick="deletePreset()" title="Hapus preset yang dipilih">
                                <i class="fas fa-trash me-1"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Quick Presets --}}
                <div class="mt-3">
                    <small class="text-muted fw-semibold">
                        <i class="fas fa-bolt me-1"></i> Preset Cepat:
                    </small>
                    <div class="btn-group ms-2" role="group">
                        <button class="btn btn-outline-secondary btn-sm" onclick="applyQuickPreset('byJudul')">
                            Per Bantuan
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="applyQuickPreset('byWilayah')">
                            Per Wilayah
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="applyQuickPreset('byStatus')">
                            Status Verifikasi
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="applyQuickPreset('byNominal')">
                            Total Nominal
                        </button>
                    </div>
                </div>
            </div>

            {{-- Pivot Table Output --}}
            <div class="card-body">
                <div id="output" class="pivot-container">
                    {{-- Loading state --}}
                    <div id="pivot-loading" class="pivot-loading">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p>Memuat Pivot Table...</p>
                    </div>
                </div>
            </div>

            {{-- Footer Info --}}
            <div class="card-footer bg-light">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i> 
                        Drag & drop field untuk mengubah tampilan. Klik pada header untuk mengurutkan.
                    </small>
                    <small class="text-muted">
                        <i class="fas fa-database me-1"></i> 
                        Total Data: <strong id="total-records">{{ count($pivotData) }}</strong> records
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom Styles --}}
    <style>
        /* Loading overlay */
        .pivot-loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 300px;
            color: #6c757d;
        }
        
        .pivot-loading .spinner-border {
            width: 3rem;
            height: 3rem;
        }
        
        /* Pivot Table Styling */
        .pivot-container {
            overflow-x: auto;
            min-height: 400px;
        }

        .pvtUi {
            font-size: 0.875rem;
        }

        .pvtUi select, .pvtUi input {
            font-size: 0.8rem;
            padding: 4px 8px;
        }

        .pvtAxisContainer, .pvtVals {
            background: #f8f9fa;
            border: 1px dashed #dee2e6;
            border-radius: 8px;
            padding: 10px;
            min-height: 80px;
        }

        .pvtAxisContainer li span.pvtAttr {
            background: #696cff;
            color: white;
            border-radius: 4px;
            padding: 4px 10px;
            font-size: 0.75rem;
            cursor: move;
        }

        .pvtAxisContainer li span.pvtAttr:hover {
            background: #5a5edd;
        }

        .pvtTable {
            border-collapse: collapse;
            font-size: 0.8rem;
        }

        .pvtTable th, .pvtTable td {
            border: 1px solid #dee2e6;
            padding: 6px 10px;
        }

        .pvtTable th {
            background: #f1f3f5;
            font-weight: 600;
        }

        .pvtTable tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .pvtTable tbody tr:hover {
            background: #e9ecef;
        }

        .pvtTotal, .pvtGrandTotal {
            font-weight: bold;
            background: #e7f1ff !important;
        }

        .pvtRendererArea {
            padding: 15px;
        }

        /* Dropdown styling */
        .pvtDropdown {
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }

        /* Aggregator dropdown */
        .pvtAggregator {
            margin-right: 10px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .pvtUi td {
                display: block;
                width: 100%;
            }

            .pvtAxisContainer {
                margin-bottom: 10px;
            }
        }
    </style>

    {{-- Load jQuery and dependencies first --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/pivot.min.js'></script>

    <script>
        // Store reference to our jQuery before it gets overwritten
        var pivotJQuery = jQuery.noConflict(true);
        
        // Data from PHP - transform field names to user-friendly labels
        var rawData = @json($pivotData);
        
        // Field name mapping (database field -> user-friendly label)
        var fieldLabels = {
            'uuid': 'ID',
            'no_rekening': 'Nomor Rekening',
            'jenis_rekening': 'Jenis Rekening',
            'tipe_rekening': 'Tipe Rekening',
            'id_verif_teller': 'ID Verifikasi Teller',
            'tanggal_verif_teller': 'Tanggal Verifikasi',
            'verif_teller': 'Status Verifikasi',
            'name_period': 'Periode',
            'tempat_mengajar': 'Tempat Mengajar',
            'nama_lengkap': 'Nama Lengkap',
            'nik': 'NIK',
            'desa': 'Desa/Kelurahan',
            'nm_wil': 'Kecamatan',
            'judul': 'Jenis Bantuan',
            'nominal': 'Nominal',
            'wilayah': 'Wilayah'
        };

        // Transform data to use user-friendly field names
        var salesPivotData = rawData.map(function(row) {
            var newRow = {};
            Object.keys(row).forEach(function(key) {
                var newKey = fieldLabels[key] || key;
                newRow[newKey] = row[key];
            });
            return newRow;
        });
        
        // Basic renderers
        var renderers = pivotJQuery.pivotUtilities.renderers;

        // Storage key for presets
        var PRESETS_KEY = 'pivot_presets_{{ request()->query("periode") }}';

        // Default configuration (using user-friendly field names)
        var defaultConfig = {
            renderers: renderers,
            vals: ["Nominal"],
            aggregatorName: "Count",
            rows: ['Jenis Bantuan', 'Kecamatan'],
            cols: ['Status Verifikasi'],
            rendererName: "Table"
        };

        // Quick presets definitions (using user-friendly field names)
        var quickPresets = {
            byJudul: {
                vals: ["Nominal"],
                aggregatorName: "Count",
                rows: ['Jenis Bantuan'],
                cols: ['Status Verifikasi'],
                rendererName: "Table"
            },
            byWilayah: {
                vals: ["Nominal"],
                aggregatorName: "Count", 
                rows: ['Kecamatan', 'Desa/Kelurahan'],
                cols: ['Jenis Bantuan'],
                rendererName: "Table"
            },
            byStatus: {
                vals: ["Nominal"],
                aggregatorName: "Count",
                rows: ['Status Verifikasi', 'Jenis Bantuan'],
                cols: [],
                rendererName: "Table"
            },
            byNominal: {
                vals: ["Nominal"],
                aggregatorName: "Sum",
                rows: ['Jenis Bantuan', 'Kecamatan'],
                cols: ['Status Verifikasi'],
                rendererName: "Table Barchart"
            }
        };

        // Hide loading and initialize pivot immediately
        document.getElementById('pivot-loading').style.display = 'none';
        
        pivotJQuery("#output").pivotUI(salesPivotData, defaultConfig);

        function initializePivot(config) {
            config.renderers = renderers;
            pivotJQuery("#output").pivotUI(salesPivotData, config, true);
        }

        function getCurrentConfig() {
            var config = pivotJQuery("#output").data("pivotUIOptions");
            if (!config) return {};
            var configCopy = JSON.parse(JSON.stringify(config));
            delete configCopy["aggregators"];
            delete configCopy["renderers"];
            delete configCopy["localeStrings"];
            return configCopy;
        }

        function loadPresetList() {
            var presets = getPresets();
            var select = document.getElementById('preset-select');
            // Remove all options except the first one
            while (select.options.length > 1) {
                select.remove(1);
            }
            
            Object.keys(presets).forEach(function(name) {
                var option = document.createElement('option');
                option.value = name;
                option.text = name;
                select.add(option);
            });
        }

        function getPresets() {
            var stored = localStorage.getItem(PRESETS_KEY);
            return stored ? JSON.parse(stored) : {};
        }

        function savePresets(presets) {
            localStorage.setItem(PRESETS_KEY, JSON.stringify(presets));
        }

        function savePreset() {
            var name = document.getElementById('preset-name').value.trim();
            if (!name) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nama Preset Diperlukan',
                    text: 'Masukkan nama untuk preset ini',
                    customClass: { confirmButton: 'btn btn-primary' },
                    buttonsStyling: false
                });
                return;
            }

            var presets = getPresets();
            presets[name] = getCurrentConfig();
            savePresets(presets);
            loadPresetList();
            
            document.getElementById('preset-name').value = '';
            document.getElementById('preset-select').value = name;

            Swal.fire({
                icon: 'success',
                title: 'Preset Disimpan!',
                text: 'Preset "' + name + '" berhasil disimpan',
                timer: 2000,
                showConfirmButton: false
            });
        }

        function loadPreset() {
            var name = document.getElementById('preset-select').value;
            if (!name) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Preset',
                    text: 'Pilih preset yang ingin dimuat',
                    customClass: { confirmButton: 'btn btn-primary' },
                    buttonsStyling: false
                });
                return;
            }

            var presets = getPresets();
            if (presets[name]) {
                initializePivot(presets[name]);
                Swal.fire({
                    icon: 'success',
                    title: 'Preset Dimuat!',
                    text: 'Preset "' + name + '" berhasil dimuat',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        }

        function deletePreset() {
            var name = document.getElementById('preset-select').value;
            if (!name) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Preset',
                    text: 'Pilih preset yang ingin dihapus',
                    customClass: { confirmButton: 'btn btn-primary' },
                    buttonsStyling: false
                });
                return;
            }

            Swal.fire({
                title: 'Hapus Preset?',
                text: 'Preset "' + name + '" akan dihapus permanen',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: { 
                    confirmButton: 'btn btn-danger me-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    var presets = getPresets();
                    delete presets[name];
                    savePresets(presets);
                    loadPresetList();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Dihapus!',
                        text: 'Preset berhasil dihapus',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }

        function applyQuickPreset(presetKey) {
            if (quickPresets[presetKey]) {
                initializePivot(quickPresets[presetKey]);
            }
        }

        function exportTableToExcel(tableClass, filename) {
            filename = filename || 'Laporan_Pivot';
            var table = document.getElementsByClassName(tableClass)[0];
            if (!table) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Ada Data',
                    text: 'Buat pivot table terlebih dahulu',
                    customClass: { confirmButton: 'btn btn-primary' },
                    buttonsStyling: false
                });
                return;
            }

            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            var tableHTML = table.outerHTML.replace(/ /g, '%20');
            
            var date = new Date().toISOString().slice(0,10);
            filename = filename + '_' + date + '.xls';

            downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);

            if(navigator.msSaveOrOpenBlob){
                var blob = new Blob(['\ufeff', tableHTML], { type: dataType });
                navigator.msSaveOrOpenBlob(blob, filename);
            } else {
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
                downloadLink.download = filename;
                downloadLink.click();
            }
            
            document.body.removeChild(downloadLink);

            Swal.fire({
                icon: 'success',
                title: 'Export Berhasil!',
                text: 'File ' + filename + ' sedang diunduh',
                timer: 2000,
                showConfirmButton: false
            });
        }

        // Load preset list
        loadPresetList();
    </script>
</div>