@extends('main.template')

@section('head-section')
    <!-- Datatables -->
    <script src="{{ asset('atlantis/examples') }}/assets/js/plugin/datatables/datatables.min.js"></script>

    <style>
        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 36px;
            /* Lebar switch */
            height: 18px;
            /* Tinggi switch */
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 12px;
            /* Bentuk kotak switch */
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            /* Tinggi slider */
            width: 14px;
            /* Lebar slider */
            left: 2px;
            /* Jarak dari kiri */
            bottom: 2px;
            /* Jarak dari bawah */
            background-color: white;
            transition: .4s;
            border-radius: 50%;
            /* Bentuk bulatan di dalam slider */
        }

        /* Warna latar belakang slider saat diaktifkan */
        input:checked+.slider {
            background-color: #FC1E01;
        }

        /* Bayangan saat slider difokuskan */
        input:focus+.slider {
            box-shadow: 0 0 1px #FC1E01;
        }

        /* Perpindahan slider saat diaktifkan */
        input:checked+.slider:before {
            transform: translateX(16px);
            /* Perpindahan slider saat diaktifkan */
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 18px;
            /* Bentuk bulat switch */
        }

        .slider.round:before {
            border-radius: 50%;
            /* Bentuk bulat dalam slider */
        }
    </style>
@endsection

@section('script')
    <script>
        $(document).on('click', '.button', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            swal({
                    title: "Are you sure!",
                    type: "error",
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes!",
                    showCancelButton: true,
                },
                function() {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('/destroy') }}",
                        data: {
                            id: id
                        },
                        success: function(data) {
                            //
                        }
                    });
                });
        });
    </script>

    {{-- Toastr --}}
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Datatables -->
    <script src="{{ asset('atlantis/examples') }}/assets/js/plugin/datatables/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#basic-datatables').DataTable({});

            $('#multi-filter-select').DataTable({
                "pageLength": 5,
                initComplete: function() {
                    this.api().columns().every(function() {
                        var column = this;
                        var select = $(
                                '<select class="form-control"><option value=""></option></select>'
                            )
                            .appendTo($(column.footer()).empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function(d, j) {
                            select.append('<option value="' + d + '">' + d +
                                '</option>')
                        });
                    });
                }
            });

            // Add Row
            $('#add-row').DataTable({
                "pageLength": 5,
            });

            var action =
                '<td> <div class="form-button-action"> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

            $('#addRowButton').click(function() {
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
        @if (session()->has('success'))
            toastr.success('{{ session('success') }}', 'BERHASIL!');
        @elseif (session()->has('error'))
            toastr.error('{{ session('error') }}', 'GAGAL!');
        @endif
    </script>
@endsection


@section('main')
    <div class="page-inner"  style="background-color: white !important">

        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href={{ url('/home') }}>Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Class</li>
            </ol>
        </nav>

        <div class="container-fluid d-none">
            <h1><strong>All Class</strong></h1>
        </div>


        <script>
            function redirectToSection(url) {
                window.location.href = url;
            }
        </script>

        @include('student.all_class_filter')

        <button type="button" class="btn btn-custom md-col-3" onclick="redirectToSection('{{ url('lesson/create_v2') }}')">
            <div style="margin-right: 10px; margin-left: 10px">Add</div>
        </button>


        <div class="container-fluid mt-3 row">
            @forelse ($myClasses as $data)
                    @php

                        $numStudents = DB::select(
                            "SELECT *
                                        FROM
                                            student_lesson a
                                        WHERE a.lesson_id = $data->id",
                        );
                        $numStudentsCount = count($numStudents);
                    @endphp

                    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                        <div class="card shadow ">
                            <!-- Cover Image Course -->
                            <img class="card-img-top" style="aspect-ratio: 16 / 9"
                                onerror="this.onerror=null; this.src='{{ url('/default/default_courses.jpeg') }}'; this.alt='Course Image';"
                                src="{{ env('AWS_BASE_URL') . $data->course_cover_image }}" alt="La Noyee">

                        <!-- Card body -->
                        <div class="card-body">
                            <!-- Badge and favorite -->
                            <div style="width: 100%; display: flex; flex-wrap: wrap; justify-content: left; align-items: flex-start; margin-bottom: .5rem;">
                                @if ($data->new_class == 'y')
                                    <div class="class-badge" style="color: white; margin-bottom: 5px; margin-right: 10px; background-color: rgb(31, 65, 151); padding: 2px 10px;">
                                        NEW
                                    </div>
                                @endif
                                <div class="class-badge"
                                    style="color: white; margin-bottom: 5px; margin-right: 5px; background-color: {{ $data->course_category_color }}; padding: 2px 10px;">
                                    <strong>{{ $data->course_category_name }}</strong>
                                </div>
                                <div class="class-badge"
                                    style="color: black; display: flex; align-items: center; margin-bottom: 5px; margin-left: auto;">
                                    <img src="{{ url('/icons/rating_class.svg') }}" style="margin-right: 4px;">
                                    <p style="font-size: 15px; margin-bottom: 0;"><strong>{{ $data->rating_course }}</strong></p>
                                </div>
                            </div>
                            <!-- Title -->
                            <h6 class="card-title"><a href="#">{{ $data->course_title }}</a></h6>
                            <p class="mb-2 text-truncate-2 d-none">Proposal indulged no do sociable he throwing settling.</p>

                            <hr style="margin-left: -20px; margin-right: -20px" class="mb-3 mt-2">
                            <li class="toga-container dropdown hidden-caret"
                                style="display: flex; justify-content: space-between; align-items: center;">
                                <img style="width: 15%; height: auto; max-height: 20px"
                                    src="{{ url('/home_icons/Toga_MDLNTraining.svg') }}">
                                <p style="font-size: 15px; margin-bottom: 3px;">{{ $data->mentor_name }}</p>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                                    <img id="dotsThree" src="{{ url('/home_icons/DotsThree.svg') }}" alt="">
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
                                            <a class="dropdown-item"
                                                href="{{ url('/class/class-list/mentor-view-class/' . $data->id) }}">
                                                <span class="link-collapse">View Class</span>
                                            </a>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </div>

                        <!-- Card footer -->
                        <div class="card-footer">
                            <ul style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center;">
                                <li style="margin-right: 8px;">
                                    <img style="width: 20px; height: auto;"
                                        src="{{ url('/icons/UserStudent_mentor.svg') }}" alt="User Icon">
                                </li>
                                <li style="margin-right: 15px; margin-bottom:5px; display: flex; align-items: center;">
                                    <a href="#" style="text-decoration: none; color: black;">
                                        <p style="font-size: 15px; margin-top: 25px; width:max-content">
                                            <b>{{ $numStudentsCount }}</b><span style="color: #8C94A3; margin-left: 5px;"></span>
                                        </p>
                                    </a>
                                </li>
                                <li style="margin-right: 10px;">
                                    <img class="editButton" id="{{ $data->id }}"
                                        style="width: 25px; height: auto; cursor: pointer;"
                                        src="{{ url('/icons/btn_edit.svg') }}" alt="Edit Icon">
                                </li>
                                @if ($numStudentsCount == 0 )
                                    <li style="margin-right: 20px;">
                                        <img class="deleteButton" id="{{ $data->id }}"
                                            style="width: 25px; height: auto; cursor: pointer;"
                                            src="{{ url('/icons/btn_delete.svg') }}" alt="Delete Icon">
                                    </li>
                                @else
                                    <li style="margin-right: 20px;" class="d-none">
                                        <img class="deleteButton" id="{{ $data->id }}"
                                            style="width: 25px; height: auto; cursor: pointer;"
                                            src="{{ url('/icons/btn_delete.svg') }}" alt="Delete Icon">
                                    </li>
                                @endif
                                <br>
                                @if ($numStudentsCount == 0 )
                                    <li>
                                        <div style="display: flex; align-items: center; width: max-content; ">
                                            <p style="margin-left: -20px; margin-bottom:0; padding: 5px 10px; font-size: 12px">Show</p>
                                            <label class="switch" style="margin-left: -2px;">
                                                <input type="checkbox" id="{{ $data->id }}"
                                                    class="switchButton{{ $data->id }}"
                                                    {{ $data->is_visible == 'y' ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </li>
                                @else
                                    <li>
                                        <div style="display: flex; align-items: center; width: max-content; ">
                                            <p style="margin-left: -10px; margin-bottom:0; padding: 5px 10px; font-size: 12px">Show</p>
                                            <label class="switch" style="margin-left: -2px;">
                                                <input type="checkbox" id="{{ $data->id }}"
                                                    class="switchButton{{ $data->id }}"
                                                    {{ $data->is_visible == 'y' ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </li>
                                @endif

                            </ul>


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
                                                    window.location.href = "{{ url('/lesson/delete_class') }}/" +
                                                        lessonId;
                                                }
                                            });
                                        });
                                    });
                                });
                            </script>

                            <script>
                                // Wait for the DOM to fully load
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Menambahkan event listener untuk setiap tombol switch
                                    document.querySelectorAll('.switchButton{{ $data->id }}').forEach(switchBtn => {
                                        switchBtn.addEventListener('change', function() {
                                            // Mendapatkan id pelajaran dari id tombol switch yang diklik
                                            const lessonId = this.getAttribute('id');
                                            var switchStatus = this.checked ? 'y' : 't';
                                            // console.log('Lesson ID : ',lessonId);
                                            // console.log('Switch Status : ',switchStatus);

                                            // Buat objek FormData
                                            var formData = new FormData();
                                            formData.append('lesson_id', lessonId);
                                            formData.append('switch_status', switchStatus);


                                            fetch('/fetch-show', {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': document.querySelector(
                                                            'meta[name="csrf-token"]').getAttribute('content')
                                                    },
                                                    // Menggunakan URLSearchParams untuk mengkodekan data FormData
                                                    body: new URLSearchParams(formData)
                                                })
                                                .then(response => {
                                                    if (!response.ok) {
                                                        throw new Error('Gagal memperbarui data');
                                                    } else {
                                                        Swal.fire({
                                                            icon: 'success',
                                                            title: 'Berhasil',
                                                            text: 'Status Kelas berhasil diubah!'
                                                        });
                                                    }
                                                })
                                                .catch(function(error) {
                                                    console.error('There was a problem with the fetch operation:',
                                                        error);
                                                });
                                        });
                                    });
                                });
                            </script>

                        </div>
                    </div>
                </div>
                @empty
                    <div class="w-100 d-flex justify-content-center">
                        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                        <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_cy82iv.json" background="transparent"
                            speed="1" style="width: 300px; height: 300px;" loop autoplay></lottie-player>
                    </div>
                    <strong class="w-100 text-center">Anda Belum Terdaftar di Kelas Manapun</strong>
                @endforelse
        </div>
    </div>
@endsection
