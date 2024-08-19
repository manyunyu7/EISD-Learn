@extends('main.template')

@section('head-section')
    <!-- Datatables -->

    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('script')
    <script>
        $(document).on('click', '.button', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            swal({
                    title: "Are you sure!",
                    type: "error",
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes!",
                    showCancelButton: true,
                },
                function () {
                    $.ajax({
                        type: "POST",
                        url: "{{url('/destroy')}}",
                        data: {id: id},
                        success: function (data) {
                            //
                        }
                    });
                });
        });

    </script>

    <script>
        $(document).ready(function() {
            // Menangani submit form dalam modal yang aktif
            $(document).on('submit', 'form[id^="submitPinForm"]', function(e) {
                e.preventDefault(); // Prevent form submission

                var form = $(this);
                var pin = form.find('#pin').val();
                var idClass = form.find('#hiddenField').val();

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

    {{-- Toastr --}}
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Datatables -->
    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#basic-datatables').DataTable({});

            $('#multi-filter-select').DataTable({
                "pageLength": 5,
                initComplete: function () {
                    this.api().columns().every(function () {
                        var column = this;
                        var select = $('<select class="form-control"><option value=""></option></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                }
            });

            // Add Row
            $('#add-row').DataTable({
                "pageLength": 5,
            });

            var action = '<td> <div class="form-button-action"> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

            $('#addRowButton').click(function () {
                $('#add-row').dataTable().fnAddData([
                    $("#addName").val(),
                    $("#addPosition").val(),
                    $("#addOffice").val(),
                    action
                ]);
                $('#addRowModal').modal('hide');

            });
        });
    </script>


    <script>
        //message with toastr
        @if(session()-> has('success'))
        toastr.success('{{ session('success') }}', 'BERHASIL!');
        @elseif(session()-> has('error'))
        toastr.error('{{ session('error') }}', 'GAGAL!');
        @endif
    </script>

@endsection


@section('main')
    <div class="page-inner" style="background-color: white !important">
        <div class="page-header">

            <script>
                function redirectToSection(url) {
                    window.location.href = url;
                }
            </script>
        </div>


        <div class="col-md-12">
            <h1><strong>All Class</strong></h1>
        </div>

        <div class="col-md-12" >
            <nav >
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href={{url('/home')}}>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Class</li>
                </ol>
            </nav>
        </div>


        @include('student.all_class_filter')

        <div class="container-fluid mt-3 row">

            <div class="col-md-12 mb-4 d-none">
                <h2><b>Recommendation</b></h2>
            </div>

            @php
                // Ambil semua kategori pelajaran sekali
                $lessonCategories = DB::table('lesson_categories')->get()->keyBy('name');

            @endphp
       
        
            @forelse ($classes as $data)
                @include('student.all_class_card_item')
                @if($data->is_registered_by_student == true)
                    <strong class="w-100 text-center">Anda Telah Terdaftar Dalam Kelas Ini</strong>
                    @break
                @endif
            @empty
                <div class="w-100 d-flex justify-content-center">
                    <script
                        src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js">
                    </script>
                    <lottie-player
                        src="https://assets5.lottiefiles.com/packages/lf20_cy82iv.json"
                        background="transparent" speed="1"
                        style="width: 300px; height: 300px;"
                        loop autoplay></lottie-player>
                </div>
                <strong class="w-100 text-center">Kelas Belum Tersedia</strong>
            @endforelse
        </div>
    </div>
@endsection


