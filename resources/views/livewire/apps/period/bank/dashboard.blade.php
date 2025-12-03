<div>
    {{-- Navigation Menu --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="menu-card menu-dashboard {{ request()->routeIs('*.PeriodDashboardBank') ? 'active' : '' }}" wire:click="dashboard">
                <div class="menu-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <span class="menu-title">Dashboard</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="menu-card menu-report {{ request()->routeIs('*.pivotFlaging') ? 'active' : '' }}" wire:click="pivotFlaging">
                <div class="menu-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <span class="menu-title">Report</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="menu-card menu-penerima {{ request()->routeIs('*.PeriodDatalistBank') ? 'active' : '' }}" wire:click="datalist">
                <div class="menu-icon">
                    <i class="fas fa-users"></i>
                </div>
                <span class="menu-title">Penerima</span>
            </div>
        </div>
        @if(session('active_role') !== 'unit')
        <div class="col-6 col-md-3">
            <div class="menu-card menu-scan {{ request()->routeIs('*.PeriodScanQrcode') ? 'active' : '' }}" wire:click="scanBarcode">
                <div class="menu-icon">
                    <i class="fas fa-qrcode"></i>
                </div>
                <span class="menu-title">Scan QR</span>
            </div>
        </div>
        @endif
    </div>

    <style>
        .menu-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.25rem 1rem;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .menu-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .menu-card:hover::before {
            opacity: 1;
        }

        .menu-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .menu-card:active {
            transform: translateY(-2px);
        }

        .menu-card.active {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        .menu-card.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 4px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 4px 4px 0 0;
        }

        .menu-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .menu-card:hover .menu-icon {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .menu-icon i {
            font-size: 1.5rem;
            color: white;
        }

        .menu-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: white;
            text-align: center;
        }

        /* Color variants */
        .menu-dashboard {
            background: linear-gradient(135deg, #696cff 0%, #5a5edd 100%);
        }

        .menu-report {
            background: linear-gradient(135deg, #8592a3 0%, #6e7b8a 100%);
        }

        .menu-penerima {
            background: linear-gradient(135deg, #28c76f 0%, #1fa85c 100%);
        }

        .menu-scan {
            background: linear-gradient(135deg, #ff9f43 0%, #e68a2e 100%);
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .menu-card {
                padding: 1rem 0.75rem;
            }

            .menu-icon {
                width: 40px;
                height: 40px;
            }

            .menu-icon i {
                font-size: 1.25rem;
            }

            .menu-title {
                font-size: 0.75rem;
            }
        }
    </style>
</div>
