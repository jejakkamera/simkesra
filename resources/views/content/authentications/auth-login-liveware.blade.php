@extends('layouts.layoutMaster')

@section('content')
    <div class="auth-wrapper auth-basic px-2">
        <div class="auth-inner my-2">
            <!-- Login basic -->
            <div class="card mb-0">
                <div class="card-body">
                    <a href="/" class="brand-logo">
                        <img src="{{ asset('images/logo/logo.png') }}" alt="brand logo">
                    </a>

                    <h4 class="card-title mb-1">Welcome to Vuexy! 👋</h4>
                    <p class="card-text mb-2">Please sign-in to your account and start the adventure</p>

                    <livewire:auth.login />

                </div>
            </div>
            <!-- /Login basic -->
        </div>
    </div>
@endsection
