{{-- TEMPLATE LAMA --}}
{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}



{{-- TEMPLATE BARU --}}
@extends('auth.template')

@section('body')
    <div class="row h-100">
        <div class="col-sm-12 my-auto">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card" style="border-radius: 30px;">
                        <div class="mt-4">
                            <div>
                                <h1 style="text-align: center"><b>Reset Password</b></h1>
                            </div>
                        </div>

                        <div class="card-body d-flex justify-content-center align-items-center mb-5">
                            <form method="POST" action="{{ route('password.update') }}" id="reset-password-form" class="w-100">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus readonly>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <div class="input-group">
                                        <input type="password" class="password form-control @error('password') is-invalid @enderror" id="input_pass1" name="password" required autocomplete="new-password">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword1">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password-confirm" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" class="password form-control" id="input_pass2" name="password_confirmation" required autocomplete="new-password">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword2">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-danger btn-block">Reset Password</button>
                                </div>

                                <div class="mb-3 d-flex justify-content-end">
                                    <a class="btn btn-link" href="{{ url('login') }}" style="color: #FF1D01;">
                                        {{ __('Back to Login?') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><br>
            <div class="col-md-12 centere" style="display: flex; justify-content: center;">
                <img src="{{URL::to('/')}}/home_assets/img/ic_LearningMDLN.svg" style="width:35%; height: auto; display: flex; align-items: center;">
            </div>
        </div>
    </div>
    <script>
        document.getElementById('togglePassword1').addEventListener('click', function () {
            var passwordInput = document.getElementById('input_pass1');
            var type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            var eyeIcon = document.querySelector('#togglePassword1 i');
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });

        document.getElementById('togglePassword2').addEventListener('click', function () {
            var passwordInput = document.getElementById('input_pass2');
            var type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            var eyeIcon = document.querySelector('#togglePassword2 i');
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('reset-password-form');

            form.addEventListener('submit', function(event) {
                const password = document.getElementById('input_pass1').value;
                const passwordConfirm = document.getElementById('input_pass2').value;

                if (password !== passwordConfirm) {
                    event.preventDefault(); // Prevent form submission
                    alert('Passwords do not match. Please make sure both fields are the same.');
                }
            });
        });
    </script>
@endsection



