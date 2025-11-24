<div>
    @livewire('Apps.Period.Validator.Dashboard')
    {{-- =======================
         LIST SKEMA / BANTUAN
    ======================== --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table ">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th>Nama Skema</th>
                            <th>Wilayah</th>
                            <th>Kelurahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($filterIds as $key => $item)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $item->judul }}</td>
                                <td>{{ $item->wilayah ?? '-' }}</td>
                                <td>{{ $item->nama_kelurahan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Tidak ada bantuan yang terkait dengan user ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- =======================
    🔍 PERBEDAAN DATA ANTAR PERIODE
    ======================= --}}
    @if ($perubahanData->count() > 0)
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-warning text-dark fw-bold d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-random me-2"></i> Penerima dengan Perbedaan Data Antar Periode
            </h5>
            <small class="text-muted">Hanya data di wilayah & bantuan user</small>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-warning text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Lengkap</th>
                            <th>NIK</th>
                            <th>Periode</th>
                            <th>Skema Bantuan</th>
                            <th>Kelurahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($perubahanData as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $item->nama_lengkap }}</td>
                                <td class="text-center">{{ $item->nik }}</td>
                                <td>{{ $item->daftar_periode }}</td>
                                <td>{{ $item->daftar_bantuan }}</td>
                                <td>{{ $item->daftar_kelurahan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif



    <br>
    {{-- =======================
          GRAFIK CHART.JS
    ======================== --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0 fw-bold">
                📊 Jumlah Profile per Kelurahan 
            </h5>
            <small class="opacity-75">Sumber: Data Profil & Bantuan</small>
        </div>

        <div class="card-body">
            <div style="height: 350px;">
                <canvas id="penerimaChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- =======================
       SCRIPT CHART.JS
======================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('penerimaChart').getContext('2d');
        const labels = @json($labels);
        const counts = @json($counts);

        // Cegah chart kosong
        if (labels.length === 0) {
            ctx.font = '16px Arial';
            ctx.fillStyle = '#888';
            ctx.fillText('Tidak ada data penerima di wilayah bantuan ini.', 20, 50);
            return;
        }

        // Buat warna acak untuk tiap batang
        const colors = labels.map(() => {
            const r = Math.floor(Math.random() * 255);
            const g = Math.floor(Math.random() * 255);
            const b = Math.floor(Math.random() * 255);
            return `rgba(${r}, ${g}, ${b}, 0.7)`;
        });

        // Render Chart
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Penerima',
                    data: counts,
                    backgroundColor: colors,
                    borderColor: colors.map(c => c.replace('0.7', '1')),
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: 10 },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 },
                        title: { display: true, text: 'Jumlah Penerima', font: { size: 13 } },
                        grid: { color: '#f0f0f0' }
                    },
                    x: {
                        ticks: { autoSkip: false, maxRotation: 45, minRotation: 0 },
                        title: { display: true, text: 'Kelurahan (Wilayah)', font: { size: 13 } },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#333',
                        titleFont: { size: 14, weight: 'bold' },
                        callbacks: {
                            label: (ctx) => `${ctx.parsed.y} penerima`
                        }
                    }
                },
                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart'
                }
            }
        });
    });
</script>
