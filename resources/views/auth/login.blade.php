@extends('auth.template')
@section('body')
    <div class="row h-100">
        <div class="col-sm-12 my-auto">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card" style="border-radius: 30px;">
                        <div class="mt-4"></div>
                        <div class="card-body  justify-content-center align-items-center mb-5">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <h3 class="form-label font-inter text-center">Login</h3>
                                    <label for="exampleInputEmail1" class="form-label">Username</label>
                                    <input
                                        type="email"
                                        class="username form-control custom-width @error('email') is-invalid @enderror"
                                        id="exampleInputEmail1"
                                        aria-describedby="emailHelp"
                                        placeholder="Username or Email Address"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="email"
                                        autofocus>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="mb-3"></div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Password</label>
                                    <div class="input-group">
                                        <input
                                            type="password"
                                            class="password form-control @error('password') is-invalid @enderror"
                                            id="exampleInputPassword1"
                                            placeholder="Password"
                                            name="password"
                                            required
                                            autocomplete="current-password"
                                        >
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                                <!-- Modal -->
                                <div class="modal fade" id="qrCodeModal" tabindex="-1" role="dialog" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="qrCodeModalLabel">Scan QR Code</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Display QR code for scanning -->
                                                <div id="qrCodeContainer" class="d-flex justify-content-center align-items-center"></div>
                                                <!-- Instructions -->
                                                <div class="ml-4 mt-2">
                                                    <p>Instructions:</p>
                                                    <ul>
                                                        <li>This is the QR code for scanning.</li>
                                                        <li>Please scan this code with your mobile device.</li>
                                                        <li>Keep the modal open while scanning.</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 form-check d-flex justify-content-start">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"
                                               checked>
                                        <label class="form-check-label" for="flexSwitchCheckChecked">Remember me</label>
                                    </div>
                                </div>
                                <!-- Button to open QR code modal -->
                                <button type="button" class="btn btn-sm btn-outline text-small btn-outline-dark mb-4" id="openQRCodeModal">
                                    Login With Modernland Signflow
                                </button>
                                <div class="mb-3 ">
                                    <button type="submit" class="btn btn-danger btn-block ">Submit</button>
                                </div>
                                <div class="mb-3 d-flex justify-content-end">
                                    <a class="btn btn-link link_forgotPwd" href="{{ url('forgotpass') }}"
                                       style="color: #FF1D01;">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/dist/qrcode.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("openQRCodeModal").addEventListener("click", function() {
                $('#qrCodeModal').modal('show');
                generateQRCode();
                setInterval(generateQRCode, 360000); // Regenerate QR code every 3 seconds
            });

            $('.close').on('click', function() {
                // Get a reference to the modal
                var modal = document.getElementById('qrCodeModal');

                // Hide the modal
                $(modal).modal('hide');
            });

            function generateQRCode() {
                // Generate timestamp for the QR code data
                var timestamp = Math.floor(Date.now() / 1000); // Convert to seconds
                // Get IP address
                fetch('https://api64.ipify.org?format=json')
                    .then(response => response.json())
                    .then(data => {
                        var ipAddress = data.ip;
                        // Generate QR code using qrcode.js
                        var qrCodeContainer = document.getElementById("qrCodeContainer");
                        qrCodeContainer.innerHTML = ''; // Clear previous content
                        var qr = new QRCode(qrCodeContainer, {
                            text: `#lms#${ipAddress}#${timestamp}`, // Include IP address and timestamp
                            width: 300,
                            height: 300
                        });
                    })
                    .catch(error => console.error('Error fetching IP address:', error));
            }
        });
    </script>
@endsection
