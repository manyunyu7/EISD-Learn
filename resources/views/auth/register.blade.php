@extends('auth.template')

@section('body')
    <div class="container">
        <div class="row h-100">
            <div class="col-sm-12 my-auto">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header w-100 center d-none">
                                <div class="fa-align-center">
                                </div>
                                {{ __('Register') }}
                            </div>

                            <div class="card-body">
                                <form method="POST" action="{{ route('partner-register') }}">
                                    @csrf
                                    <div class="div">

                                        @if (session('error'))
                                            <div class="alert alert-danger">
                                                {{ session('error') }}
                                            </div>
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <strong>Error!</strong>
                                                {{ session('error') }}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                        @endif

                                    </div>
                                    <h4>Registrasi</h4>
                                    <div class="form-group row">
                                        <label for="name"
                                            class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                        <div class="col-md-6">
                                            <input id="name" type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                value="{{ old('name') }}" required autocomplete="name" autofocus>

                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email"
                                            class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autocomplete="email">

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="contact"
                                            class="col-md-4 col-form-label text-md-right">{{ __('Nomor Telepon') }}</label>

                                        <div class="col-md-6">
                                            <input id="contact" type="text"
                                                class="form-control @error('contact') is-invalid @enderror" name="contact"
                                                value="{{ old('contact') }}" required autocomplete="contact"
                                                oninput="validatePhoneNumber()">

                                            @error('contact')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <span id="contact-error" class="text-danger" style="display:none;">Nomor telepon
                                                harus dimulai dengan 0.</span>
                                        </div>
                                    </div>

                                    <script>
                                        function validatePhoneNumber() {
                                            var contactField = document.getElementById('contact');
                                            var errorMessage = document.getElementById('contact-error');

                                            // Regular expression to ensure the phone number starts with 0, contains only digits, and has a minimum of 5 digits
                                            var phonePattern = /^0\d{4,}$/;

                                            // Check if the input matches the pattern
                                            if (contactField.value && !phonePattern.test(contactField.value)) {
                                                errorMessage.style.display = 'inline';
                                                contactField.classList.add('is-invalid');
                                            } else {
                                                errorMessage.style.display = 'none';
                                                contactField.classList.remove('is-invalid');
                                            }
                                        }
                                    </script>


                                    <div class="form-group row">
                                        <label for="password"
                                            class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                        <div class="col-md-6 input-group">
                                            <div class="input-group">
                                                <input type="password"
                                                    class="password form-control @error('password') is-invalid @enderror"
                                                    id="exampleInputPassword1" placeholder="Password" name="password"
                                                    required autocomplete="current-password">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="togglePassword">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="registration_code"
                                            class="col-md-4 col-form-label text-md-right">{{ __('Kode Registrasi') }}</label>

                                        <div class="col-md-6">
                                            <input id="registration_code" type="text"
                                                class="form-control @error('registration_code') is-invalid @enderror"
                                                name="registration_code" value="{{ old('registration_code') }}" required
                                                autocomplete="registration_code">

                                            @error('registration_code')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group row d-none">
                                        <label for="rolez" class="col-md-4 col-form-label text-md-right">Disini Aku
                                            Mau</label>

                                        <div class="col-md-6">
                                            <select class="form-control" name="rolez" id="">
                                                <option value="student">Belajar</option>
                                                {{--                                                <option value="mentor">Mengajar (Mentor)</option> --}}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0 mt-4">
                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Register') }}
                                            </button>
                                        </div>
                                    </div>


                                    <a class="btn btn-link" href="{{ url('login') }}">
                                        {{ __('Sudah Punya Akun ? Login Disini') }}
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
