<div>
    <div class="col-12 col-xl-12 mb-4 order-1 order-lg-0">
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <div class="card-title m-0">
          <h5 class="mb-0">Change Role. Role Aktiv : {{ session('active_role') }}</h5>

        </div>
      </div>
      <div class="card-body">
        <ul class="nav nav-tabs widget-nav-tabs pb-3 gap-4 mx-1 d-flex flex-nowrap" role="tablist">
                    @foreach ($roles as $role)
                        <li class="nav-item" role="presentation">
                            <button wire:click="changeRoleSelected('{{ $role }}')" class="nav-link btn active d-flex flex-column align-items-center justify-content-center">
                                <div class="badge bg-label-secondary rounded p-2"></div>
                                <h6 class="tab-widget-title mb-0 mt-2">{{ $role }}</h6>
                            </button>
                        </li>
                    @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>
