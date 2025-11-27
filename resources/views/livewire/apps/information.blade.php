<div class="row">
    <!-- User Sidebar -->
    <div class="col-xl-12 col-lg-5 order-1 order-md-0">
      <!-- User Card -->
      <div class="card mb-6">
        <div class="card-body pt-12">
          <div class="user-avatar-section">
            <div class=" d-flex align-items-center flex-column">
              <img class="img-fluid rounded mb-4" src="{{ asset('storage/'.$school->logo) }}" height="120" width="120" alt="User avatar" />
              <div class="user-info text-center">
                <h5>{{ $school->name }}</h5>
    
    
              </div>
            </div>
          </div>
         
          <h5 class="pb-4 border-bottom mb-4">Details</h5>
          <div class="info-container">
            <ul class="list-unstyled mb-6">
              <li class="mb-2">
                <span class="h6">Alamat:</span>
                <span>{{ $school->address }}</span>
              </li>
              <li class="mb-2">
                <span class="h6">Phone:</span>
                <span>{{ $school->phone_number }}</span>
              </li>
              {{-- <li class="mb-2">
                <span class="h6">Token:</span>
                <span id="token">{{ $school->token }}</span>
                <button class="btn btn-xs btn-outline-secondary ms-2" onclick="copyToClipboard('token')">
                    <i class="ti ti-copy "></i>
                </button>
              </li> --}}
              <li class="mb-2">
                <span class="h6">Domain:</span>
                <span id="domain">{{ url()->to('/');  }}</span>
                <button class="btn btn-xs btn-outline-secondary ms-2" onclick="copyToClipboard('domain')">
                    <i class="ti ti-copy "></i>
                </button>
              </li>
             
            </ul>
            <div class="d-flex justify-content-center">
              <button class="btn btn-warning me-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Sekolah" wire:click="AppsInformationEdit"><i class="ti ti-edit ti-md"></i></button>
              {{-- <button class="btn btn-info me-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Generate Token" wire:click="GenerateToken"><i class="ti ti-api ti-md"></i></button> --}}
            </div>
          </div>
        </div>
      </div>
      <!-- /User Card -->
      <!-- Plan Card -->
      
      <!-- /Plan Card -->
    </div>
    <!--/ User Sidebar -->
  
    <!--/ User Content -->

    <script>
        function copyToClipboard(elementId) {
            // Ambil elemen berdasarkan ID
            const textToCopy = document.getElementById(elementId).innerText;
    
            // Salin teks ke clipboard
            navigator.clipboard.writeText(textToCopy).then(() => {
                alert('Copied to clipboard: ' + textToCopy);
            }).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        }
    </script>
  </div>
