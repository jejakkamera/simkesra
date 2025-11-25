<div class="container-xxl">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">
      <div class="card">
        <div class="card-body">

          <p class="mb-4">Please sign-in to your account</p>

          <form wire:submit.prevent="login">
            <div class="mb-3">
              <label for="email" class="form-label">Email or Username</label>
              <input type="text" id="email" wire:model.defer="email" class="form-control">
              @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" id="password" wire:model.defer="password" class="form-control">
              @error('password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3 form-check">
              <input class="form-check-input" type="checkbox" id="remember-me" wire:model="remember">
              <label class="form-check-label" for="remember-me">Remember Me</label>
            </div>

            <!-- 🧠 reCAPTCHA di bawah ini -->
            <div class="mb-3" wire:ignore>
              <div  id="recaptcha-container"
                    class="g-recaptcha"
                    data-sitekey="{{ config('services.recaptcha.key') }}"
                    data-callback="recaptchaCallback"></div>
            </div>

            <input type="hidden" id="gRecaptcha" wire:model="gRecaptcha">
            @error('gRecaptcha') <span class="text-danger">{{ $message }}</span> @enderror

            <button type="submit" class="btn btn-google-plus d-grid w-100">Sign in</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>


<script nonce="@cspNonce">
    // Callback dari Google reCAPTCHA
    window.recaptchaCallback = function(token) {
        document.getElementById('gRecaptcha').value = token;
        document.getElementById('gRecaptcha').dispatchEvent(new Event('input'));
    };

    // Reset reCAPTCHA setelah gagal login
    window.addEventListener('reset-recaptcha', function () {
        if (window.grecaptcha) grecaptcha.reset();
        document.getElementById('gRecaptcha').value = '';
        document.getElementById('gRecaptcha').dispatchEvent(new Event('input'));
    });

    // 🔹 Redirect otomatis ke halaman error 429
    window.addEventListener('throttleRedirect', function (event) {
        const minutes = event.detail.minutes;
        // Arahkan ke halaman error khusus (bisa sesuaikan route-nya)
        window.location.href = '/error/429?wait=' + minutes;
    });
</script>

