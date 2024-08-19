@extends('main.template')

@section('head-section')
    <!-- Datatables -->

    <script src="{{ asset('atlantis/examples') }}/assets/js/plugin/datatables/datatables.min.js"></script>
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
@endsection


@section('main')
    <div class="page-inner" style="background-color: white !important">
        <div class="container-fluid">
            <div class="row">
                <h1 class="mb-3 mt-3 col-12"><b>Dashboard</b></h1>

                <div class="mt-3 row">
                    @php
                        // Ambil semua kategori pelajaran sekali
                        $lessonCategories = DB::table('lesson_categories')->get()->keyBy('name');
                    @endphp
                    @forelse ($myClasses as $data)
                        @php
                            $userID = Auth::id();
                            $numStudents = DB::select(
                                "SELECT *
                                FROM
                                student_lesson a
                                WHERE a.lesson_id = $data->id",
                            );
                            $numStudentsCount = count($numStudents);

                            // Ambil warna kategori jika kategori ada dalam $lessonCategories
                            // $warna = $lessonCategories[$data->course_category]->color_of_categories ?? '#007bff';
                        @endphp

                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                            <div class="card shadow ">
                                <!-- Image -->
                                <img class="card-img-top" style="aspect-ratio: 16 / 9"
                                    onerror="this.onerror=null; this.src='{{ url('/default/default_courses.jpeg') }}'; this.alt='Course Image';"
                                    src="{{ env('AWS_BASE_URL') . $data->course_cover_image }}" alt="La Noyee">
                                {{-- <img src="assets/images/courses/4by3/08.jpg"  class="card-img-top" alt="course image"> --}}
                                <!-- Card body -->
                                <div class="card-body">
                                    <!-- Badge and favorite -->
                                    <div
                                        style="width: 100%; display: flex; justify-content: space-between; margin-bottom: .5rem;">
                                        <div class="class-badge"
                                            style="color: white; margin-bottom: 5px; margin-right: 5px; background-color: {{ $data->course_category_color }}; padding: 2px 10px;">
                                            <strong>{{ $data->course_category_name }}</strong>
                                        </div>
                                    </div>
                                    <!-- Title -->
                                    <h5 class="card-title"><a href="#">{{ $data->course_title }}</a></h5>
                                    <p class="mb-2 text-truncate-2 d-none">Proposal indulged no do sociable he throwing
                                        settling.</p>


                                    <hr style="margin-left: -20px; margin-right: -20px" class="mb-3 mt-2">

                                    <div class="d-flex justify-content-between">
                                        {{-- href="{{ url('course/'.$data->id.'/section/'.$data->first_section) }}" --}}
                                        @php
                                            $class = DB::select("
                                                        SELECT
                                                        lsn.id AS lesson_id,
                                                        lsn.course_title AS lesson_title,
                                                        lsn.course_cover_image AS lesson_cover_img,
                                                        lsn.department_id AS lesson_dept_id,
                                                        lsn.position_id AS lesson_posit_id,
                                                        cs.quiz_session_id AS exam_session_id,
                                                        cs.section_title AS section_title,
                                                        cs.id AS section_id,
                                                        cs.created_at AS date_create,
                                                        es.exam_type AS exam_type,
                                                        exm.id AS exam_id
                                                        FROM
                                                        lessons lsn
                                                        LEFT JOIN
                                                        course_section cs ON lsn.id = cs.course_id
                                                        LEFT JOIN
                                                        exam_sessions es ON cs.quiz_session_id = es.id
                                                        LEFT JOIN
                                                        exams exm ON es.exam_id = exm.id
                                                        WHERE
                                                        lsn.id = $data->id
                                                        AND
                                                        es.exam_id = exm.id
                                                        AND
                                                        es.exam_type = 'Post Test'
                                                        ORDER BY
                                                        cs.created_at DESC
                                                        ");
                                        @endphp
                                        
                                        
                                        <div>
                                            <a id="checkBtn" class="btn text-white btn-round{{ $class ? '' : ' empty' }}" style="background-color: {{ $class ? '#208DBB' : '#CCCCCC' }}" onclick="{{ $class ? "redirectToSection('" . url('/visualization/main-pie-chart-details?learn_status=all&location=all&class=' . $data->id . '&department=all&month=all/') . "')" : 'void(0)' }}">Check</a>
                                            <script>
                                                // Mengambil tombol check
                                                var checkBtn = document.getElementById('checkBtn');

                                                // Mengecek apakah class kosong, jika ya, menonaktifkan tombol
                                                if (checkBtn.classList.contains('empty')) {
                                                    checkBtn.removeAttribute('onclick');
                                                    checkBtn.style.backgroundColor = '#CCCCCC';
                                                }

                                                // Fungsi untuk mengarahkan ke bagian yang dituju
                                                function redirectToSection(url) {
                                                    window.location.href = url;
                                                }
                                            </script>
                                        </div>

                                        
                                        <p style="width: 100%; margin-top: 15px; font-size: 17px; color: #23BD33; text-align: end" class="h6 mb-0">
                                            {{ isset($courseStatus[$data->id]) ? $courseStatus[$data->id] : '0' }}% Completed
                                        </p>
                                    </div>


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
                                        <img style="width: 6%; height: auto; margin-top: 12px"
                                            src="{{ url('/icons/user_lesson_card.png') }}" alt="Portfolio Icon">
                                        <a style="text-decoration: none;color: BLACK;"
                                            href="{{ url('/class/students/' . $data->id) }}">
                                            <p style="font-size: 17px; margin-left: 10px; margin-top:28px;">
                                                <b> {{ $numStudentsCount }} </b><span
                                                    style="color: #8C94A3;">students</span>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="w-100 d-flex justify-content-center">
                            <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                            <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_cy82iv.json"
                                background="transparent" speed="1" style="width: 300px; height: 300px;" loop
                                autoplay></lottie-player>
                        </div>
                        <strong class="w-100 text-center">Belum Ada Data</strong>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
