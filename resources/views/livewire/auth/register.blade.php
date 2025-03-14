<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
            <!-- Register -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-4 mt-2">
                          <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo"><img src="{{ asset('assets/img/favicon/polijati.png') }}" alt="Apps"></span>
                            <span class="app-brand-text demo text-body fw-bold ms-1">APPs</span>
                          </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-1 pt-2">Create an Account</h4>

                    @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="register">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" wire:model="name" class="form-control" required>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="phone" id="phone" wire:model="phone" class="form-control" required>
                            @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" wire:model="email" class="form-control" required>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group input-group-merge">
                              @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                <input type="password" id="password" wire:model="password" class="form-control" required>

                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="input-group input-group-merge">
                              @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                                <input type="password" id="password_confirmation" wire:model="password_confirmation" class="form-control" required>
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">Register</button>
                        </div>
                    </form>

                    <p class="text-center">
                        <span>Already have an account?</span>
                        <a href="{{ url('auth/login-basic') }}" wire:navigate>
                            <span>Sign</span>
                        </a>
                    </p>

                </div>
            </div>
            <!-- /Register -->
        </div>
    </div>
</div>
