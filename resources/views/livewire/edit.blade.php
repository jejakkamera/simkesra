<div>
  <div class="row">
    <div class="col-md">
      <div class="card mb-12 bg-light">
        <div class="card-header header-elements">
          <span class="card-header-title me-2"><h2 class="text-lg font-semibold">{{ $formTitle ?: "Form Title" }}</h2></span>
          <!-- Wrap elements with `.card-header-elements`  -->
          <div class="card-header-elements ms-auto">
                    <button type="button" onclick="window.history.back()" class="btn btn-secondary">
                        Kembali
                    </button>
          </div>
        </div>
        <div class="card-body">
          <form wire:submit.prevent="save"> <!-- Note the use of .prevent -->
                    {{ $this->form }}
                    <br>
                    <button type="submit" class="btn btn-primary">
                        Submit
                    </button>
                </form>

                <x-filament-actions::modals />
        </div>
      </div>
    </div>
  </div>


</div>
