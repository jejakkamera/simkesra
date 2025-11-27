<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">
      <!-- Login -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center mb-4 mt-2">
            <a href="{{url('/')}}" class="app-brand-link gap-2">
              <span class="app-brand-logo demo"><img src="{{ asset('assets/img/favicon/polijati.png') }}" alt="Politeknik Jatiluhur"></span>
              <span class="app-brand-text demo text-body fw-bold ms-1">Politeknik Jatiluhur</span>
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-1 pt-2">Penerimaan Mahasiswa baru! 👋</h4>
          <p class="mb-4">Forfot Password </p>
          @if (session()->has('error'))
              <div class="alert alert-danger">
                  {{ session('error') }}
              </div>
          @endif

          @if (session('message'))
            <div class="alert alert-success" role="alert">
              {{ session('message') }}
            </div>
          @endif

          <form wire:submit.prevent="sendResetLink">
              <div class="mb-4">
                  <label for="email" class="block text-sm font-medium">Email address</label>
                  <input wire:model="email" id="email" type="email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required autofocus>
                  @error('email') <span class="error text-red-500">{{ $message }}</span> @enderror
              </div>

              <button type="submit" class="btn btn-google-plus d-grid w-100">Send Reset Link</button>
          </form>
<hr>
          <p class="text-center">
            <span>New on our platform?</span>
            <a href="{{url('auth/register-basic')}}" wire:navigate>
              <span>Create an account</span>
            </a>
          </p>
          <p class="text-center">
            <span>Have Account?</span>
            <a href="{{url('auth/login-basic')}}" wire:navigate>
              <span>Login</span>
            </a>
          </p>


        </div>
      </div>
      <!-- /Register -->
    </div>
  </div>
</div>
