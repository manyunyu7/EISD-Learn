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
    <div class="col-md-12">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href={{url('/home')}}>Home</a></li>
                <li class="breadcrumb-item"><a href={{ url('class/class-list') }}>Class List</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Class</li>
            </ol>
        </nav>
    </div>

    <div class="row mt--2 border-primary col-md-12">
        <div class="col-md-10">
            <div class="col-md-12">
                <h1><b>{{ $data->course_title }}</b></h1>
                <div class="row mb-5">
                    <div class="border-primary" style="width:100%; display: flex; align-items: center; flex-wrap: wrap;">
                        <p class="col-md-6" style="margin: 0; margin-right: 10px;">
                            <span class="badge dynamic-badge" style="border-radius: 0; font-size: 16px; font-weight: bold;">{{ $data->course_category }}</span>
                        </p>
                        <div id="mentorLabel" class="mt--2 col-md-6" style="width: 100%; display: flex; align-items: center;
                            @media (max-width: 767px) { order: 2; }">
                            <div style="padding: 10px; display: flex; align-items: center;">
                                <img style="max-width: 24px; max-height: 24px; margin-right: 12px; margin-left: auto; margin-top: 12px;" src="{{ url('/home_icons/Toga_MDLNTraining.svg') }}" alt="Clock Icon">
                                <p style="font-size: 14px; margin: 0; margin-top: -6px; margin-right: 8px; margin-top: 12px;">Modernland Training</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <div style="text-align: center;">
                            <a href="javascript:void();" data-switch="0">
                                <img
                                    style="height: auto!important; max-height: 80vh; object-fit: cover; width: 100vw!important; max-width: 70vw!important; display: inline-block; border-radius: 20px;"
                                    class="card-img-top"
                                    onerror="this.onerror=null; this.src='{{ url('/default/default_courses.jpeg') }}'; this.alt='Alternative Image';"
                                    src="{{ env('AWS_BASE_URL') . $data->course_cover_image }}" alt="La Noyee">
                            </a>
                        </div>

                        <h2 class="mt-3"><b>Deskripsi</b></h2>
                        <p style="font-size: 16px;">{!! $data->course_description !!}</p>

                        <div style="display: flex; align-items: center;">
                            <h2 class="mt--2">
                                <b>Silabus</b>
                            </h2>
                            <div class="mb-3" style="margin-left: auto; margin-right: 5px;">
                                <div style="padding: 10px; display: flex; align-items: center;">
                                    <img style="max-width: 24px; max-height: 24px; margin-right: 12px;" src="{{ url('/icons/Folder.svg') }}" alt="Folder Icon">
                                    <p style="font-size: 1rem; margin: 0;">{{ $jumlahSection }} Sections</p>
                                </div>
                            </div>
                            <div class="mb-3" style="margin-left: 5px;">
                                <div style="padding: 10px; display: flex; align-items: center;">
                                    <img style="max-width: 24px; max-height: 24px; margin-right: 12px;" src="{{ url('/icons/Clock.svg') }}" alt="Clock Icon">
                                    <p style="font-size: 1rem; margin: 0; margin-right: 6px">30m</p>
                                </div>
                            </div>
                        </div>

                        @forelse ($dayta as $item)
                        <div style="border-collapse: collapse; width: 100%;">
                            <div style="border: 1px solid #ccc; padding: 10px; display: flex; align-items: center;">
                                <p style="margin: 0; margin-right: 10px; font-size: 16px">
                                    {{ $item->section_title }}
                                </p>
                                <img style="max-width: 24px; max-height: 24px; margin-right: 12px; margin-left: auto;" src="{{ url('/icons/Clock.svg') }}" alt="Clock Icon">
                                <p style="font-size: 14px; margin: 0; margin-top: -6px; margin-right: 8px; margin-top:1px">30m</p>
                            </div>
                        </div>
                        @empty
                        <div class="alert alert-danger">
                            Kelas Ini Belum Memiliki Materi
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Container -->
        <div class="col-md-2">
            <button type="button" class="btn" style="padding: 10px; background-color: #208DBB; color: white; border-radius: 10px !important; display: flex; justify-content: center; align-items: center; width: auto; max-width: 200px;" data-toggle="modal" data-target="#inputPinModal">
                <span style="font-weight: bold; font-size: 18px;">
                    Join Class
                </span>
            </button>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="inputPinModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form id="submitPinForm" method="POST">
                    {{-- cek Token CSRF --}}
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title" id="exampleModalLabel"><b>Masukan Password</b></h1>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body center" style="justify-content: center">
                            <p>Untuk masuk ke dalam kelas, silakan masukan Password terlebih dahulu</p>
                            <div class="mb-3">
                                <!-- Hidden Input -->
                                <input type="hidden" id="hiddenField" name="idClass" value='{{ $data->id }}'>
                                <!-- PIN Input -->
                                <input name="pin" style="border: 1px solid #ced4da;" class="form-control" type="text" id="pin" required placeholder="Masukan Password disini">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn" style="background-color: #208DBB"><span style="color: white">Submit</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
