@extends('main.template')

@section('head-section')
    @include('main.home._styling_home_student')
    <!-- Tambahkan ini untuk jQuery dan SweetAlert -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#submitPinForm').on('submit', function(e) {
                e.preventDefault(); // Prevent form submission

                let pin = $('#pin').val();
                let idClass = $('#hiddenField').val();

                $.ajax({
                    url: '{{ url('/input-pin') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        pin: pin,
                        idClass: idClass
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showCancelButton: false,
                                confirmButtonText: 'Pergi ke kelas saya'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '/class/my-class';
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan, silakan coba lagi nanti.'
                        });
                    }
                });
            });
        });

    </script>
@endsection

@section('main')
    <br><br>
    <div class="col-md-12" style="background: white">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href={{url('/home')}}>Home</a></li>
                <li class="breadcrumb-item"><a href={{ url('class/class-list') }}>Class List</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Class</li>
            </ol>
        </nav>
    </div>

    <div class="row mt--2 border-primary col-md-12" style="background: white">
        <div class="col-md-10">
            <div class="col-md-12">
                <h1><b>{{ $data->course_title }}</b></h1>
            </div>
        </div>
    </div>
@endsection
