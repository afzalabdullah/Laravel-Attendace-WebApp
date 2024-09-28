@extends('layouts.app')

@section('content')
    <div class="container">
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-center py-4">
                            <p class="logo d-flex align-items-center w-auto">
                                <img src="images/logo.png" alt="">
                                <span class="d-none d-lg-block text-decoration-none" style="color: #AF1E23">TPL Trakker</span>
                            </p>
                        </div><!-- End Logo -->

                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4" style="color: black">Login to Your Account</h5>
                                    <p class="text-center small">Enter your username & password to login</p>
                                </div>

                                <form class="row g-3" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="col-12">
                                        <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                        <div class="input-group">
                                            <input id="email" type="text"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        </div>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="password" class="form-label">{{ __('Password') }}</label>
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="current-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                                {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit" style="background-color: #AF1E23; border-color: #AF1E23;">
                                            Login
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="credits">
                            Designed by <a style="color: #AF1E23" href="https://abdullahportfolio.vercel.app/" target="_blank">Abdullah</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

<!-- Place the script at the end of the body or blade content -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const emailInput = document.getElementById('email');
        const form = emailInput.closest('form');

        form.addEventListener('submit', function (event) {
            let emailValue = emailInput.value.trim();

            // Log current value for debugging
            console.log('Current email value before append:', emailValue);

            // Append '@trakker.com' if it is not already present
            if (!emailValue.endsWith('@trakker.com')) {
                emailInput.value = emailValue + '@trakker.com';
            }

            // Log new value after appending
            console.log('Email value after append:', emailInput.value);
        });
    });
</script>
