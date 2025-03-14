
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
            <!-- Verify Email -->
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-2">Verify Your Email Address</h4>
                    <p class="mb-4">Before proceeding, please check your email for a verification link.</p>

                    @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                    <div class="text-center">
                      <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">Click here to request resend e-mail</button>.
                    </form>
                      <br>
                      or
                      <br>
                      <a href="/" class="btn btn-primary"> Login</a>
                    </div>

                </div>
            </div>
            <!-- /Verify Email -->
        </div>
    </div>
</div>
