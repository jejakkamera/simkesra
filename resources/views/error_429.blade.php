@extends('layouts.layoutError')

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="app-brand justify-content-center mb-4 mt-2">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">
                                <img src="{{ asset('assets/img/illustrations/auth-lock-illustration-light.png') }}"
                                     alt="Too Many Requests" class="img-fluid" width="80">
                            </span>
                            <span class="app-brand-text demo text-body fw-bold ms-1">Access Limited</span>
                        </a>
                    </div>

                    <h3 class="mb-2 fw-bold text-danger">Too Many Requests</h3>
                    <p class="text-muted mb-4">
                        Slow down! You've made too many login attempts.<br>
                        Please wait <strong>15 minutes</strong> before trying again.
                    </p>

                    <div class="alert alert-warning d-inline-block px-4 py-2 mb-4">
                        <span id="countdown" class="fw-semibold"></span>
                    </div>

                    <div class="d-grid gap-2 col-lg-6 mx-auto">
                        <a href="/login" class="btn btn-primary">Try Again</a>
                        <a href="/" class="btn btn-outline-secondary">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script nonce="@cspNonce">
    function startCountdown(seconds) {
        var countdownElement = document.getElementById('countdown');
        var interval = setInterval(function () {
            var minutes = Math.floor(seconds / 60);
            var remainingSeconds = seconds % 60;
            countdownElement.innerText = minutes + 'm ' + remainingSeconds + 's remaining';
            seconds--;
            if (seconds < 0) {
                clearInterval(interval);
                countdownElement.innerText = 'You can try again now.';
            }
        }, 1000);
    }

    window.onload = function () {
        startCountdown(900); // 900 detik = 15 menit
    };
</script>
@endsection
