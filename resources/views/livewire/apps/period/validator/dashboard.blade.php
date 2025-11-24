<div>
    {{-- ======================
        MENU DASHBOARD ATAS
    ====================== --}}
    <div class="d-flex flex-wrap gap-3 justify-content-start justify-content-md-between mb-4">
        {{-- Dashboard --}}
        <div class="flex-fill" style="min-width: 150px;">
            <div class="card text-bg-primary text-center shadow-sm" wire:click="dashboard" style="cursor: pointer;">
                <div class="card-body py-3">
                    <h6 class="mb-0 fw-bold text-white">Dashboard</h6>
                </div>
            </div>
        </div>

        {{-- Ajukan Penerima --}}
        <div class="flex-fill" style="min-width: 150px;">
            <div class="card text-bg-success text-center shadow-sm" wire:click="penerima" style="cursor: pointer;">
                <div class="card-body py-3">
                    <h6 class="mb-0 fw-bold text-white">Daftar</h6>
                </div>
            </div>
        </div>

        {{-- Ajukan Penerima --}}
        <div class="flex-fill" style="min-width: 150px;">
            <div class="card text-bg-warning text-center shadow-sm" wire:click="ajukan" style="cursor: pointer;">
                <div class="card-body py-3">
                    <h6 class="mb-0 fw-bold text-white">Ajukan Penerima</h6>
                </div>
            </div>
        </div>

        {{-- Report --}}
        <div class="flex-fill" style="min-width: 150px;">
            <div class="card text-bg-secondary text-center shadow-sm" wire:click="pivotFlaging" style="cursor: pointer;">
                <div class="card-body py-3">
                    <h6 class="mb-0 fw-bold text-white">Report</h6>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================
        LAPORAN RINGKAS
    ====================== --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light d-flex align-items-center">
            <h6 class="card-title mb-0 fw-bold">
                <i class="fas fa-chart-line text-success me-2"></i> Perbandingan Penerima vs Diajukan
            </h6>
        </div>

        <div class="card-body py-3">
            <div class="row text-center mb-3">
                <div class="col-md-4 col-12 mb-3 mb-md-0">
                    <div class="text-muted small">Total Penerima</div>
                    <h3 class="fw-bold text-primary mb-0">{{ number_format($totalProfiles ?? 0) }}</h3>
                </div>
                <div class="col-md-4 col-12 mb-3 mb-md-0">
                    <div class="text-muted small">Sudah Diajukan</div>
                    <h3 class="fw-bold text-success mb-0">{{ number_format($totalPemenang ?? 0) }}</h3>
                </div>
                <div class="col-md-4 col-12">
                    <div class="text-muted small">Belum Diajukan</div>
                    <h3 class="fw-bold text-danger mb-0">{{ number_format(($totalProfiles ?? 0) - ($totalPemenang ?? 0)) }}</h3>
                </div>
            </div>

            {{-- Progress bar visual --}}
            <div class="px-3">
                <div class="progress" style="height: 12px; background-color: #f3f3f3;">
                    <div
                        class="progress-bar bg-success"
                        role="progressbar"
                        style="width: {{ $persentase ?? 0 }}%; transition: width 0.8s;"
                        aria-valuenow="{{ $persentase ?? 0 }}"
                        aria-valuemin="0"
                        aria-valuemax="100">
                    </div>
                </div>
                <p class="text-center text-muted mt-2 mb-0 small">
                    <strong>{{ $persentase ?? 0 }}%</strong> dari total penerima sudah diajukan pada periode ini.
                </p>
            </div>
        </div>
    </div>

</div>
