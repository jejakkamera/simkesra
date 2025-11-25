<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Upload Penerima Bantuan</h5>
            
            <button wire:click="SiswaList" class="btn btn-secondary" title="DataList Siswa">
                <i class="ti ti-list me-0 me-sm-1"></i> 
            </button>
            <br>
            * Format yang digunakan sama data BJB
            <br>
            * Pastikan Nama, NIK dan ibu kandung tidak kosong dan data yang diupload sesuai.
        </div>
        <div class="card-body">
    
            <form wire:submit.prevent="pesertaUploadProsess" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="periode" class="form-label">Pilih Periode</label>
                    <select class="form-control" id="periode" wire:model="periode">
                        <option value="">-- Pilih Periode --</option>
                        @foreach($periodes as $p)
                            <option value="{{ $p->id }}" {{ $p->id == $this->Period ? 'selected' : '' }}>
                                {{ $p->name_period }}
                            </option>
                        @endforeach
                    </select>
                    @error('periode') 
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="skema" class="form-label">Pilih Skema</label>
                    <select class="form-control" id="skema" wire:model="skema">
                        <option value="">-- Pilih Skema --</option>
                        @foreach($skemas as $skema)
                            <option value="{{ $skema->id }}">{{ $skema->judul }} - {{ $skema->wilayah }} </option>
                        @endforeach
                    </select>
                    @error('skema') 
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="excel_file" class="form-label">Choose Excel File</label>
                    <input type="file" class="form-control" id="excel_file" wire:model="excel_file">
                    @error('excel_file')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Upload</button>
        
                @if ($excel_file)
                    <div class="mt-3">
                        <strong>Selected File:</strong> {{ $excel_file->getClientOriginalName() }}
                    </div>
                @endif
            </form>
            <hr>
            <h3 class="mt-5">Upload Logs</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>File Path</th>
                        <th>Periode</th>
                        <th>Skema</th>
                        <th>Status</th>
                        <th>Uploaded At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>
                                <a href="{{ asset('storage/'.$log->file_path) }}" class="btn btn-primary btn-sm" download>
                                    <i class="fa fa-download"></i> 
                                </a>
                            </td>
                            
                            <td>{{ $log->name_period }}</td>
                            <td>{{ $log->judul }} - {{ $log->wilayah }}</td>
                            <td>
                                @if($log->status == 'pending')
                                    <span class="badge-warning">Pending</span>
                                @elseif($log->status == 'completed')
                                    <span class="badge-success" data-bs-toggle="modal" data-bs-target="#detailsModal" data-details="{{ json_encode($log->details) }}"> <button class="btn btn-secondary btn-sm">Info</button> Completed</span>
                                @elseif($log->status == 'failed')
                                    <span class="badge-danger" data-bs-toggle="modal" data-bs-target="#notesModal" data-notes="{{ $log->notes }}">Failed</span>
                                @else
                                    <span class="badge-info">Unknown</span>
                                @endif
                            </td>

                            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal untuk Log Details -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Log Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="modalDetailsContent"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal untuk Notes -->
    <div class="modal fade" id="notesModal" tabindex="-1" aria-labelledby="notesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notesModalLabel">Notes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <pre id="modalNotesContent"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Modal for Log Details
        var detailsModal = document.getElementById('detailsModal');
        detailsModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // The button that triggers the modal
            var details = JSON.parse(button.getAttribute('data-details')); // Get data-details
            var modalBody = detailsModal.querySelector('#modalDetailsContent');

            modalBody.innerHTML = ''; // Clear modal content

            details.forEach(function(detail) {
                var listItem = document.createElement('li');
                listItem.innerHTML = `
                    <strong>Status:</strong> ${detail.status}<br>
                    <strong>Note:</strong><pre>${detail.note}</pre>`;
                modalBody.appendChild(listItem);
            });
        });

        // Modal for Notes
        var notesModal = document.getElementById('notesModal');
        notesModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // The button that triggers the modal
            var notes = button.getAttribute('data-notes'); // Get data-notes
            var modalBody = notesModal.querySelector('#modalNotesContent');

            try {
                // Try parsing JSON if possible
                var parsedNotes = JSON.parse(notes);
                modalBody.textContent = JSON.stringify(parsedNotes, null, 2);
            } catch (e) {
                // If not JSON, display as plain text
                modalBody.textContent = notes;
            }
        });
    });
</script>
