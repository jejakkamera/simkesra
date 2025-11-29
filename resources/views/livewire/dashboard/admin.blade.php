<div class="container py-4">

    {{-- ========================================= --}}
    {{-- 📋 FILTER DATA PER PERIODE --}}
    {{-- ========================================= --}}
    @php
        $selectedPeriodLabel = collect($periodOptions)
            ->firstWhere('id', (int) $selectedPeriod)['name'] ?? 'Periode belum dipilih';
        $totalPenerimaPeriode = $penerimaBySkema['total_value'] ?? 0;
        $totalSkemaAktif = isset($penerimaBySkema['labels']) ? count($penerimaBySkema['labels']) : 0;
        $totalPenerimaAgregat = $penerimaPerPeriode['total_value'] ?? 0;
        $totalPeriodeTerlihat = isset($penerimaPerPeriode['labels']) ? count($penerimaPerPeriode['labels']) : 0;
        $summaryCards = [
            [
                'label' => 'Penerima disetujui',
                'value' => number_format($totalPenerimaPeriode),
                'meta' => $selectedPeriodLabel,
                'icon' => 'ti ti-users'
            ],
            [
                'label' => 'Skema aktif',
                'value' => $totalSkemaAktif,
                'meta' => 'Memiliki realisasi pada periode ini',
                'icon' => 'ti ti-category'
            ],
            [
                'label' => 'Agregat 6 periode',
                'value' => number_format($totalPenerimaAgregat),
                'meta' => $totalPeriodeTerlihat . ' periode terakhir',
                'icon' => 'ti ti-calendar-stats'
            ],
        ];
    @endphp


    {{-- ========================================= --}}
    {{-- 📋 DATA PER SKEMA & PERIODE (DEBUG) --}}
    {{-- ========================================= --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="fw-semibold mb-0">Data mentah penerima per skema</h6>
                            <small class="text-muted">Periode: {{ $selectedPeriodLabel }}</small>
                        </div>
                        <span class="badge {{ $hasSkemaChartData ? 'bg-label-success text-success' : 'bg-label-secondary' }}">
                            {{ $hasSkemaChartData ? 'Ada data' : 'Kosong' }}
                        </span>
                    </div>

                    @if (!empty($penerimaBySkema['labels']))
                        <div class="table-responsive">
                            <table class="table table-sm table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Skema</th>
                                        <th class="text-end">Penerima</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penerimaBySkema['labels'] as $idx => $label)
                                        <tr>
                                            <td>{{ $label }}</td>
                                            <td class="text-end">{{ number_format($penerimaBySkema['values'][$idx] ?? 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p class="text-muted small mt-2 mb-0">Total penerima terdata: {{ number_format($penerimaBySkema['total_value'] ?? 0) }}</p>
                    @else
                        <p class="text-muted mb-0">Belum ada pemenangan dengan status disetujui untuk periode ini.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="fw-semibold mb-0">Data perbandingan antar periode</h6>
                            <small class="text-muted">Menampilkan maksimal 6 periode terbaru.</small>
                        </div>
                        <span class="badge {{ $hasPeriodChartData ? 'bg-label-success text-success' : 'bg-label-secondary' }}">
                            {{ $hasPeriodChartData ? 'Ada data' : 'Kosong' }}
                        </span>
                    </div>

                    @if (!empty($penerimaPerPeriode['labels']))
                        <div class="table-responsive">
                            <table class="table table-sm table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Periode</th>
                                        <th class="text-end">Penerima</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penerimaPerPeriode['labels'] as $idx => $label)
                                        <tr>
                                            <td>{{ $label }}</td>
                                            <td class="text-end">{{ number_format($penerimaPerPeriode['values'][$idx] ?? 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p class="text-muted small mt-2 mb-0">Total penerima seluruh periode: {{ number_format($penerimaPerPeriode['total_value'] ?? 0) }}</p>
                    @else
                        <p class="text-muted mb-0">Belum ada agregasi penerima yang cocok dengan filter saat ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================= --}}
    {{-- 📊 RANGKUMAN TAHUNAN ADMIN --}}
    {{-- ========================================= --}}
    @if ($showBantuanByYear && !empty($bantuanPerYear))
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
                    <div>
                        <h5 class="fw-semibold mb-1">
                            <i class="ti ti-chart-bar me-2"></i> Distribusi penyaluran tahunan
                        </h5>
                        <p class="text-muted small mb-0">Menunjukkan jumlah penerima yang berhasil disetujui per tahun (maksimal 5 tahun terakhir).</p>
                    </div>
                    @if (!empty($yearlySummary))
                        <div class="text-md-end">
                            <p class="text-muted small mb-1">Tahun terbaru {{ $yearlySummary['latest_year'] }}</p>
                            <div class="fs-3 fw-bold mb-1">{{ number_format($yearlySummary['latest_total']) }}</div>
                            @if (!empty($yearlySummary['trend']))
                                <span class="badge bg-label-primary">{{ $yearlySummary['trend'] }}</span>
                            @endif
                        </div>
                    @endif
                </div>

                @forelse ($bantuanPerYear as $row)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-semibold d-block">{{ $row['year'] }}</span>
                                <small class="text-muted">{{ number_format($row['total']) }} penerima</small>
                            </div>
                            <span class="badge bg-label-secondary">{{ $row['percentage'] }}%</span>
                        </div>
                        <div class="progress bantuan-progress mt-2" role="progressbar" aria-valuenow="{{ $row['percentage'] }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar" style="width: {{ $row['percentage'] }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="empty-chart py-4">
                        Belum ada rekap penyaluran tahunan yang tercatat.
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    {{-- ========================================= --}}
    {{-- 🖼️ SLIDESHOW GAMBAR INFORMASI --}}
    {{-- ========================================= --}}
    @if ($pictures->isNotEmpty())
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="section-header d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                    <div>
                        <h5 class="fw-semibold mb-1">Informasi visual terbaru</h5>
                        <p class="text-muted small mb-0">Slideshow untuk menyampaikan pengumuman internal.</p>
                    </div>
                    <span class="badge bg-label-info text-primary mt-2 mt-md-0">{{ $pictures->count() }} gambar</span>
                </div>

                <div id="dashboardCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner rounded shadow-sm">
                        @foreach ($pictures as $index => $pic)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $pic->file_path) }}"
                                     class="d-block w-100 dashboard-slide-img"
                                     alt="{{ $pic->description }}">
                                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                                    <h5 class="text-white fw-bold mb-1">{{ $pic->description }}</h5>
                                    <small class="text-light">
                                        {{ $pic->created_at->format('d M Y, H:i') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#dashboardCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Sebelumnya</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#dashboardCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Berikutnya</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ========================================= --}}
    {{-- 📎 FILE / DOWNLOAD INFORMATION --}}
    {{-- ========================================= --}}
    @if ($downloads->isNotEmpty())
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="section-header d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                    <div>
                        <h5 class="fw-semibold mb-1 text-primary">
                            <i class="fas fa-file-download me-2"></i> Dokumen / File Terkait
                        </h5>
                        <p class="text-muted small mb-0">Kumpulan berkas yang dapat diunduh oleh admin.</p>
                    </div>
                    <span class="badge bg-label-primary mt-2 mt-md-0">{{ $downloads->count() }} file</span>
                </div>

                <div class="row g-3">
                    @foreach ($downloads as $file)
                        <div class="col-md-4 col-sm-6">
                            <div class="card file-card h-100 border-0 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <p class="fw-bold text-dark mb-2">
                                        📄 {{ $file->description }}
                                    </p>
                                    <small class="text-muted mb-3 d-flex align-items-center gap-1">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $file->created_at->format('d M Y') }}
                                    </small>

                                    <div class="mt-auto d-flex gap-2">
                                        <a href="{{ asset('storage/' . $file->file_path) }}"
                                           target="_blank"
                                           class="btn btn-outline-primary btn-sm flex-fill">
                                            <i class="fas fa-download"></i> Unduh
                                        </a>

                                        <button type="button"
                                                class="btn btn-outline-secondary btn-sm flex-fill"
                                                data-bs-toggle="modal"
                                                data-bs-target="#fileModal{{ $file->id }}">
                                            <i class="fas fa-eye"></i> Lihat
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- MODAL PREVIEW --}}
                        <div class="modal fade" id="fileModal{{ $file->id }}" tabindex="-1" aria-labelledby="fileModalLabel{{ $file->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="fileModalLabel{{ $file->id }}">
                                            {{ $file->description }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        @if (Str::endsWith($file->file_path, '.pdf'))
                                            <iframe src="{{ asset('storage/' . $file->file_path) }}"
                                                    width="100%" height="500px"></iframe>
                                        @else
                                            <img src="{{ asset('storage/' . $file->file_path) }}"
                                                 class="img-fluid rounded shadow">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <style>
.dashboard-slide-img {
    max-height: 400px;
    object-fit: contain;
    background-color: #f8f9fa;
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s;
}
.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.summary-card .summary-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background-color: rgba(115, 103, 240, 0.15);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    color: #7367f0;
}

.section-header .badge {
    font-weight: 600;
}

.file-card .btn {
    min-width: 90px;
}

.bantuan-progress {
    height: 10px;
    background-color: #e9ecef;
}
.bantuan-progress .progress-bar {
    background: linear-gradient(90deg, #0d6efd, #00b4d8);
}
.empty-chart {
    padding: 1rem;
    text-align: center;
    color: #6c757d;
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    font-size: 0.9rem;
}
    </style>
</div>
