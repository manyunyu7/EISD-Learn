@extends('main.template')

@section('head-section')
    <!-- Datatables -->

    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>
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


    <div class="page-inner">
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

        <button type="button" class="btn btn-custom md-col-3"  onclick="redirectToSection('{{ url('lesson/create_v2') }}')">
            <div style="margin-right: 10px; margin-left: 10px">Add</div>
        </button>


        <div class="container-fluid mt-3 row">
            @php
                // Ambil semua kategori pelajaran sekali
                $lessonCategories = DB::table('lesson_categories')->get()->keyBy('name');

            @endphp
            @forelse ($myClasses as $data)
                @php
                // Ambil warna kategori jika kategori ada dalam $lessonCategories
                $warna = $lessonCategories[$data->course_category]->color_of_categories ?? '#007bff';
                $numStudents = DB::select(
                                "SELECT *
                                    FROM
                                        student_lesson a
                                    WHERE a.lesson_id = $data->id");
                $numStudentsCount = count($numStudents);
                @endphp

                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                    <div class="card shadow ">
                        <!-- Image -->
                        <img class="card-img-top"
                             style="max-height: 220px"
                             onerror="this.onerror=null; this.src='{{ url('/default/default_courses.jpeg') }}'; this.alt='Course Image';"
                             src="{{ Storage::url('public/class/cover/') . $data->course_cover_image }}"
                             alt="La Noyee">
                        <!-- Card body -->
                        <div class="card-body">
                            <!-- Badge and favorite -->
                            <div style="width: 100%; display: flex; flex-wrap: wrap; justify-content: left; align-items: flex-start; margin-bottom: .5rem;">
                                @if($data->new_class == 'Aktif')
                                    <div class="class-badge" style="color: white; margin-bottom: 5px; margin-right: 10px; background-color: rgb(31, 65, 151); padding: 2px 10px;">
                                        NEW
                                    </div>
                                @endif
                                <div class="class-badge" style="color: white; margin-bottom: 5px; margin-right: 5px; background-color: {{ $warna }}; padding: 2px 10px;">
                                    <strong>{{ $data->course_category }}</strong>
                                </div>
                                <div class="class-badge" style="color: black; display: flex; align-items: center; margin-bottom: 5px; margin-left: auto;">
                                    <img src="{{ url('/Icons/Star.svg') }}" style="margin-right: 4px;">
                                    <p style="font-size: 15px; margin-bottom: 0;"><strong>5.0</strong></p>
                                </div>
                            </div>

                            <!-- Title -->
                            <h6 class="card-title"><a href="#">{{$data->course_title}}</a></h6>
                            <p class="mb-2 text-truncate-2 d-none">Proposal indulged no do sociable he throwing
                                settling.</p>


                            <hr style="margin-left: -20px; margin-right: -20px" class="mb-3 mt-2">


                            {{-- <div class="d-flex justify-content-between">
                                <div>
                                    <img style="width: 35%; height: auto;" src="{{ url('/HomeIcons/Toga_MDLNTraining.svg') }}">
                                    <p>{{ $data->mentor_name }}</p>
                                </div>
                            </div> --}}
                            <li class="toga-container dropdown hidden-caret" style="display: flex; justify-content: space-between; align-items: center;">
                                <img style="width: 15%; height: auto; max-height: 20px" src="{{ url('/HomeIcons/Toga_MDLNTraining.svg') }}">
                                <p style="font-size: 15px; margin-bottom: 3px;">{{ $data->mentor_name }}</p>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                                    <img id="dotsThree" src="{{ url('/HomeIcons/DotsThree.svg') }}" alt="">
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <a class="dropdown-item" href="{{ url('/lesson/manage-materials/' . $data->id) }}">
                                                Manage Materials
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ url('/class/students/' . $data->id) }}">
                                                <span class="link-collapse">Manage Students</span>
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ url('/class/class-list/mentor-view-class/' . $data->id) }}">
                                                <span class="link-collapse">View Class</span>
                                            </a>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                            <!-- Rating star -->
                            <ul class="list-inline mb-0 d-none">
                                <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i>
                                </li>
                                <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i>
                                </li>
                                <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i>
                                </li>
                                <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i>
                                </li>
                                <li class="list-inline-item me-0 small"><i class="far fa-star text-warning"></i>
                                </li>
                                <li class="list-inline-item ms-2 h6 fw-light mb-0">4.0/5.0</li>
                            </ul>
                        </div>
                        <!-- Card footer -->
                        <div class="card-footer pt-0 pb-3">
                            <div style="display: flex; justify-content: center; align-items: center;">
                                <!-- Icon for students -->
                                <img style="width: 10%; height: auto; margin-top: 12px;"
                                     src="{{ url('/icons/UserStudent_mentor.svg') }}">

                                <!-- Link to view students -->
                                {{-- href="{{ url('/class/class-list/students/' . $data->id) }}" --}}
                                <a style="text-decoration: none; color: black;">
                                    <p style="font-size: 17px; margin-left: 10px; margin-top: 28px;">
                                        <b>{{ $numStudentsCount }}</b><span style="color: #8C94A3;"> students</span>
                                    </p>
                                </a>
                                <!-- Edit button icon -->
                                <img class="editButton" id="{{ $data->id }}" style="width: 10%; height: auto; margin-top: 12px; margin-left: 10px; cursor: pointer;"
                                        src="{{ url('/icons/btn_edit.svg') }}" alt="Edit Icon">

                                        <script>
                                            // Wait for the DOM to fully load
                                            document.addEventListener('DOMContentLoaded', function() {
                                                // Mendapatkan semua elemen dengan kelas editButton
                                                const editButtons = document.querySelectorAll('.editButton');
                                        
                                                // Menambahkan event listener untuk setiap tombol edit
                                                editButtons.forEach(button => {
                                                    button.addEventListener('click', function() {
                                                        // Mendapatkan id pelajaran dari id tombol edit yang diklik
                                                        const lessonId = button.getAttribute('id');
                                                        // Mengalihkan halaman ke URL yang ditentukan saat tombol edit diklik dengan id pelajaran yang sesuai
                                                        window.location.href = "{{ url('/lesson/edit_class') }}/" + lessonId;
                                                    });
                                                });
                                            });
                                        </script>
                                    
                                <!-- Delete button icon -->
                                <!-- Delete button icon -->
<img class="deleteButton" id="{{ $data->id }}" style="width: 10%; height: auto; margin-top: 12px; margin-left: 10px; cursor: pointer;" src="{{ url('/icons/btn_delete.svg') }}" alt="Delete Icon">

<!-- SweetAlert Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    // Wait for the DOM to fully load
    document.addEventListener('DOMContentLoaded', function() {
        // Mendapatkan semua elemen dengan kelas deleteButton
        const deleteButtons = document.querySelectorAll('.deleteButton');

        // Menambahkan event listener untuk setiap tombol delete
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const lessonId = button.getAttribute('id');

                // Tampilkan SweetAlert
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect user when confirmed
                        window.location.href = "{{ url('/lesson/delete_class') }}/" + lessonId;
                    }
                });
            });
        });
    });
</script>

                                <!-- Toggle switch -->

                            </div>
                        </div>

                    </div>
                </div>
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
                <strong class="w-100 text-center">Anda Belum Terdaftar di Kelas Manapun</strong>
            @endforelse
        </div>
    </div>
@endsection




