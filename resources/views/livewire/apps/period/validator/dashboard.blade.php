<div>
    {{-- ======================
        MENU DASHBOARD MODERN (SEPERTI ADMIN)
    ====================== --}}
    <div class="row g-3 mb-3">
        {{-- Dashboard --}}
        <div class="col-6 col-md-3">
            <div class="menu-card menu-purple" wire:click="dashboard">
                <div class="menu-icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="menu-label">Dashboard</div>
            </div>
        </div>

        {{-- Report --}}
        <div class="col-6 col-md-3">
            <div class="menu-card menu-slate" wire:click="pivotFlaging">
                <div class="menu-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="menu-label">Report</div>
            </div>
        </div>

        {{-- Daftar Penerima --}}
        <div class="col-6 col-md-3">
            <div class="menu-card menu-green" wire:click="penerima">
                <div class="menu-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="menu-label">Penerima</div>
            </div>
        </div>

        {{-- Ajukan Penerima --}}
        <div class="col-6 col-md-3">
            <div class="menu-card menu-orange" wire:click="ajukan">
                <div class="menu-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div class="menu-label">Ajukan</div>
            </div>
        </div>
    </div>

    {{-- ======================
        LAPORAN RINGKAS - COLLAPSIBLE
    ====================== --}}
    <div class="card shadow-sm border-0 mb-3" x-data="{ expanded: false }">
        <div class="card-header bg-light d-flex align-items-center justify-content-between py-2" 
             style="cursor: pointer;" 
             @click="expanded = !expanded">
            <h6 class="card-title mb-0 fw-bold small">
                <i class="fas fa-chart-line text-success me-2"></i> Statistik
            </h6>
            <div class="d-flex align-items-center">
                {{-- Quick stats preview when collapsed --}}
                <span class="badge bg-primary me-2" x-show="!expanded">
                    {{ number_format($totalPemenang ?? 0) }} / {{ number_format($totalProfiles ?? 0) }}
                </span>
                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2">
                    <i class="fas" :class="expanded ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>
            </div>
        </div>

        <div class="card-body py-2" x-show="expanded" x-collapse>
            <div class="row text-center mb-2">
                <div class="col-4">
                    <div class="text-muted small" style="font-size: 0.7rem;">Penerima Tahun Lalu</div>
                    <h5 class="fw-bold text-primary mb-0">{{ number_format($totalProfiles ?? 0) }}</h5>
                </div>
                <div class="col-4">
                    <div class="text-muted small" style="font-size: 0.7rem;">Sudah Diajukan</div>
                    <h5 class="fw-bold text-success mb-0">{{ number_format($totalPemenang ?? 0) }}</h5>
                </div>
                <div class="col-4">
                    <div class="text-muted small" style="font-size: 0.7rem;">Belum Diajukan</div>
                    <h5 class="fw-bold text-danger mb-0">{{ number_format(($totalProfiles ?? 0) - ($totalPemenang ?? 0)) }}</h5>
                </div>
            </div>

            {{-- Progress bar visual --}}
            <div class="progress" style="height: 8px; background-color: #f3f3f3;">
                <div
                    class="progress-bar bg-success"
                    role="progressbar"
                    style="width: {{ $persentase ?? 0 }}%; transition: width 0.8s;"
                    aria-valuenow="{{ $persentase ?? 0 }}"
                    aria-valuemin="0"
                    aria-valuemax="100">
                </div>
            </div>
            <p class="text-center text-muted mt-1 mb-0" style="font-size: 0.75rem;">
                <strong>{{ $persentase ?? 0 }}%</strong> sudah diajukan
            </p>
        </div>
    </div>

    <style>
        /* Modern Menu Cards */
        .menu-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px 15px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            min-height: 100px;
        }

        .menu-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .menu-card:active {
            transform: translateY(0);
        }

        .menu-icon {
            font-size: 1.8rem;
            margin-bottom: 8px;
            color: white;
        }

        .menu-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: white;
            text-align: center;
        }

        /* Color Variants */
        .menu-purple {
            background: linear-gradient(135deg, #7c3aed, #8b5cf6);
        }

        .menu-slate {
            background: linear-gradient(135deg, #475569, #64748b);
        }

        .menu-green {
            background: linear-gradient(135deg, #059669, #10b981);
        }

        .menu-orange {
            background: linear-gradient(135deg, #ea580c, #f97316);
        }

        /* Mobile Responsiveness */
        @media (max-width: 576px) {
            .menu-card {
                padding: 15px 10px;
                min-height: 80px;
            }

            .menu-icon {
                font-size: 1.4rem;
                margin-bottom: 5px;
            }

            .menu-label {
                font-size: 0.75rem;
            }
        }
    </style>
</div>
