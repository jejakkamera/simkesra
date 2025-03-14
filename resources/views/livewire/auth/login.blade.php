<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">
      <!-- Login -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center mb-4 mt-2">
            <a href="{{url('/')}}" class="app-brand-link gap-2">
              <span class="app-brand-logo demo"><img src="{{ asset($school_logo) }}" alt="apps"></span>
              <span class="app-brand-text demo text-body fw-bold ms-1">{{ $school_name }}</span>
            </a>
          </div>
          <!-- /Logo -->
          <p class="mb-4">Please sign-in to your account </p>
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
          <form wire:submit.prevent="login">
            <div class="mb-3">
              <label for="email" class="form-label">Email or Username</label>
              <input type="text" id="email" wire:model="email" class="form-control" required>
              @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 form-password-toggle">
              <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Password</label>
                <a href="{{url('auth/forgot-password')}}" wire:navigate>
                  <small>Forgot Password?</small>
                </a>
              </div>
              <div class="input-group input-group-merge">
                <input type="password" id="password" wire:model="password" class="form-control" required>
            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
              </div>
            </div>
            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember-me">
                <label class="form-check-label" for="remember-me">
                  Remember Me
                </label>
              </div>
            </div>
            <div class="mb-3">
              <button class="btn btn-google-plus d-grid w-100" type="submit">Sign in</button>
            </div>
          </form>

          {{-- <p class="text-center">
            <span>New on our platform?</span>
            <a href="{{url('auth/register-basic')}}" wire:navigate>
              <span>Create an account</span>
            </a>
          </p> --}}


        </div>
      </div>
      <!-- /Register -->
    </div>
  </div>
</div>
