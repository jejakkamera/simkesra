<div class="container py-4">

    {{-- ========================================= --}}
    {{-- 🖼️ SLIDESHOW GAMBAR INFORMASI --}}
    {{-- ========================================= --}}
    @if ($pictures->isNotEmpty())
        <div id="dashboardCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-inner rounded shadow-sm">
                @foreach ($pictures as $index => $pic)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $pic->file_path) }}"
                             class="d-block w-100 dashboard-slide-img"
                             alt="{{ $pic->description }}">
                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                            <h5 class="text-white fw-bold">{{ $pic->description }}</h5>
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
    @endif

    {{-- ========================================= --}}
    {{-- 📎 FILE / DOWNLOAD INFORMATION --}}
    {{-- ========================================= --}}
    @if ($downloads->isNotEmpty())
        <h5 class="fw-bold mb-3 text-primary">
            <i class="fas fa-file-download me-1"></i> Dokumen / File Terkait
        </h5>

        <div class="row g-3">
            @foreach ($downloads as $file)
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <p class="fw-bold text-dark mb-2">
                                📄 {{ $file->description }}
                            </p>
                            <small class="text-muted mb-3">
                                <i class="fas fa-calendar-alt"></i>
                                {{ $file->created_at->format('d M Y') }}
                            </small>

                            <div class="d-flex gap-2">
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
</style>
</div>
