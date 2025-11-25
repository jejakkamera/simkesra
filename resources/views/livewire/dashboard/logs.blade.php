<div>
    <div class="container py-4">
        {{-- ========================================= --}}
        {{-- 🔍 LOG VIEWER HEADER --}}
        {{-- ========================================= --}}
        <div class="row mb-4">
            <div class="col-md-8">
                <h4 class="fw-bold text-primary">
                    <i class="fas fa-file-lines me-2"></i> Production Error Logs
                </h4>
                <small class="text-muted">Monitor aplikasi error real-time dari dashboard</small>
            </div>
            <div class="col-md-4 text-md-end">
                @if (count($logs) > 0)
                    <button wire:click="downloadLogs" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-download"></i> Download
                    </button>
                    <button wire:click="clearLogs" wire:confirm="Yakin ingin menghapus semua logs?" 
                            class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash"></i> Clear
                    </button>
                @endif
            </div>
        </div>

        {{-- Success Message --}}
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- ========================================= --}}
        {{-- 🎯 FILTER & SEARCH --}}
        {{-- ========================================= --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    {{-- Search Input --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold mb-2">
                            <i class="fas fa-search me-1"></i> Search Message
                        </label>
                        <div class="input-group">
                            <input type="text" 
                                   wire:model.live="searchQuery" 
                                   class="form-control" 
                                   placeholder="Cari error message...">
                            <button class="btn btn-outline-primary" type="button" wire:click="search">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Level Filter --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold mb-2">
                            <i class="fas fa-filter me-1"></i> Filter Level
                        </label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="level" id="level-all" 
                                   wire:click="filterByLevel('all')" {{ $selectedLevel === 'all' ? 'checked' : '' }}>
                            <label class="btn btn-outline-secondary" for="level-all">All</label>

                            <input type="radio" class="btn-check" name="level" id="level-error" 
                                   wire:click="filterByLevel('ERROR')" {{ $selectedLevel === 'ERROR' ? 'checked' : '' }}>
                            <label class="btn btn-outline-danger" for="level-error">ERROR</label>

                            <input type="radio" class="btn-check" name="level" id="level-warning" 
                                   wire:click="filterByLevel('WARNING')" {{ $selectedLevel === 'WARNING' ? 'checked' : '' }}>
                            <label class="btn btn-outline-warning" for="level-warning">WARNING</label>

                            <input type="radio" class="btn-check" name="level" id="level-info" 
                                   wire:click="filterByLevel('INFO')" {{ $selectedLevel === 'INFO' ? 'checked' : '' }}>
                            <label class="btn btn-outline-info" for="level-info">INFO</label>

                            <input type="radio" class="btn-check" name="level" id="level-debug" 
                                   wire:click="filterByLevel('DEBUG')" {{ $selectedLevel === 'DEBUG' ? 'checked' : '' }}>
                            <label class="btn btn-outline-secondary" for="level-debug">DEBUG</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================= --}}
        {{-- 📋 LOGS TABLE --}}
        {{-- ========================================= --}}
        @if (count($logs) > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-list me-2"></i> Total Logs: {{ count($logs) }}
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 150px;">
                                        <i class="fas fa-clock me-1"></i> Timestamp
                                    </th>
                                    <th style="width: 100px;">
                                        <i class="fas fa-tag me-1"></i> Level
                                    </th>
                                    <th>
                                        <i class="fas fa-message me-1"></i> Message
                                    </th>
                                    <th style="width: 80px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $index => $log)
                                    <tr class="align-middle">
                                        <td>
                                            <small class="text-muted font-monospace">
                                                {{ $log['timestamp'] }}
                                            </small>
                                        </td>
                                        <td>
                                            @php
                                                $levelClass = [
                                                    'ERROR' => 'danger',
                                                    'CRITICAL' => 'danger',
                                                    'ALERT' => 'danger',
                                                    'EMERGENCY' => 'dark',
                                                    'WARNING' => 'warning',
                                                    'NOTICE' => 'info',
                                                    'INFO' => 'info',
                                                    'DEBUG' => 'secondary'
                                                ];
                                                $class = $levelClass[$log['level']] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $class }}">
                                                {{ strtoupper($log['level']) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ Str::limit($log['message'], 60) }}
                                            </small>
                                        </td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#logDetailModal{{ $index }}">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- DETAIL MODAL --}}
                                    <div class="modal fade" id="logDetailModal{{ $index }}" tabindex="-1" 
                                         aria-labelledby="logDetailModalLabel{{ $index }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-bug me-2"></i> 
                                                        Error Detail - {{ $log['timestamp'] }}
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" 
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <strong>Level:</strong>
                                                        <span class="badge bg-{{ $class }} ms-2">
                                                            {{ strtoupper($log['level']) }}
                                                        </span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Environment:</strong>
                                                        <code class="ms-2">{{ $log['environment'] }}</code>
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Message:</strong>
                                                        <pre class="bg-light p-3 rounded mt-2"><code>{{ $log['message'] }}</code></pre>
                                                    </div>
                                                    <div class="mb-0">
                                                        <strong>Full Log:</strong>
                                                        <pre class="bg-light p-3 rounded mt-2 font-monospace" style="max-height: 400px; overflow-y: auto;"><code>{{ $log['raw'] }}</code></pre>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-success alert-dismissible fade show text-center py-5" role="alert">
                <i class="fas fa-check-circle display-4 d-block mb-3"></i>
                <h5 class="fw-bold">No Errors Found</h5>
                <p class="text-muted mb-0">
                    Aplikasi berjalan lancar tanpa error
                </p>
            </div>
        @endif
    </div>

    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9fa !important;
        }

        .font-monospace {
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
        }

        pre {
            word-wrap: break-word;
            white-space: pre-wrap;
        }

        .btn-group label {
            cursor: pointer;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.6rem;
        }
    </style>
</div>
