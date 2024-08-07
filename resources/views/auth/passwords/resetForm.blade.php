@extends('auth.template')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('reset-password-form');
        form.addEventListener('submit', function(event) {
            const password = document.getElementById('input_pass1').value;
            const passwordConfirm = document.getElementById('input_pass2').value;
    
            if (password !== passwordConfirm) {
                event.preventDefault(); // Prevent form submission
                // Menggunakan SweetAlert untuk menampilkan pesan error
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Passwords do not match. Please make sure both fields are the same.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
</script>

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
                                        <input type="password" class="password form-control @error('password') is-invalid @enderror" id="input_pass1" name="password" required autocomplete="new-password" minlength="8">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword1">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                    <div id="password-info" class="form-text">Password must be at least 8 characters long.</div>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            document.getElementById('togglePassword1').addEventListener('click', function () {
                var passwordInput = document.getElementById('input_pass1');
                var type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
        
                var eyeIcon = document.querySelector('#togglePassword1 i');
                eyeIcon.classList.toggle('fa-eye'); // Pastikan untuk menggunakan kelas ikon yang konsisten
                eyeIcon.classList.toggle('fa-eye-slash'); // Pastikan untuk menggunakan kelas ikon yang konsisten
            });
        
            document.getElementById('togglePassword2').addEventListener('click', function () {
                var passwordConfirmInput = document.getElementById('input_pass2');
                var type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirmInput.setAttribute('type', type);
        
                var eyeIcon = document.querySelector('#togglePassword2 i');
                eyeIcon.classList.toggle('fa-eye'); // Pastikan untuk menggunakan kelas ikon yang konsisten
                eyeIcon.classList.toggle('fa-eye-slash'); // Pastikan untuk menggunakan kelas ikon yang konsisten
            });
        
            const form = document.getElementById('reset-password-form');
            form.addEventListener('submit', function(event) {
                const password = document.getElementById('input_pass1').value;
                const passwordConfirm = document.getElementById('input_pass2').value;
        
                if (password !== passwordConfirm) {
                    event.preventDefault(); // Prevent form submission
                    // Menggunakan SweetAlert untuk menampilkan pesan error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Passwords do not match. Please make sure both fields are the same.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        
            const passwordInput = document.getElementById('input_pass1');
            const passwordInfo = document.getElementById('password-info');
            
            // Fungsi untuk memeriksa panjang password
            function checkPasswordLength() {
                const minLength = 8; // Minimal panjang karakter
                const currentLength = passwordInput.value.length;
        
                if (currentLength < minLength) {
                    passwordInfo.textContent = `Password must be at least ${minLength} characters long.`;
                    passwordInfo.classList.add('text-danger');
                } else {
                    passwordInfo.textContent = `Password length is sufficient.`;
                    passwordInfo.classList.remove('text-danger');
                    passwordInfo.classList.add('text-success');
                }
            }
        
            // Tambahkan event listener untuk memeriksa panjang password saat mengetik
            passwordInput.addEventListener('input', checkPasswordLength);
        });
        </script>
        

        
@endsection




