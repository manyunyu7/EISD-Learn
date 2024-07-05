@extends('main.template')


@section('head-section')
    @include('main.home._styling_home_student')

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}

@endsection


@section('script')
    {{-- @include('main.home.script_student') --}}


    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-U9zBET2NSPdld3JMGN9s3Qa/s6zrmMzNMI7d7bPKL6KA6aSX4N2p1Nex/aD1xOfq"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>

@endsection

@section('main')

    <div class="page-inner">

        <div class="col-md-12">
            {{-- BREADCRUMB --}}
            <nav class="" style="--bs-breadcrumb-divider: '>'; " aria-label="breadcrumb">
                <ol class="breadcrumb bg-white">
                    <li class="breadcrumb-item"><a href={{url('/home')}}>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">My Class</li>
                </ol>
            </nav>
        </div>


        @include('student.myclass_filter')

        <div class="container-fluid mt-3 row">
            @forelse ($myClasses as $data)
                @php
                    $userID = Auth::id();
                    $silabusClass = DB::select("SELECT
                                    a.*
                                    FROM
                                        course_section a
                                    WHERE
                                        a.course_id = $data->id
                                    ");
                    $hasTaken  = DB::select("SELECT
                                    a.*
                                    FROM
                                        student_section a
                                    LEFT JOIN
                                        course_section b  ON a.section_id = b.id
                                    WHERE
                                        a.student_id = $userID AND b.course_id = $data->id;
                                    "
                                    );
                    $totalSections = count($silabusClass);
                    $total_hasTaken = count($hasTaken);
                    if($totalSections != null and $total_hasTaken != null){
                        $progressPercentage = round(($total_hasTaken / $totalSections) * 100);
                    }else{
                        $progressPercentage = 0;
                    }
                    $warna = $lessonCategories[$data->course_category]->color_of_categories ?? '#007bff';
                @endphp

                <div class="col-sm-6 col-xl-4">
                    <div class="card shadow ">
                        <!-- Image -->
                        <img class="card-img-top"
                            style="aspect-ratio: 16 / 9"
                             onerror="this.onerror=null; this.src='{{ url('/default/default_courses.jpeg') }}'; this.alt='Course Image';"
                             src="{{ env('AWS_BASE_URL') . $data->course_cover_image }}"
                             alt="La Noyee">
                        <!-- Card body -->
                        <div class="card-body">
                            <div style="width: 100%; display: flex; flex-wrap: wrap; justify-content: left; align-items: flex-start; margin-bottom: .5rem;">
                                @if($data->new_class == 'Aktif')
                                    <div class="class-badge"
                                        style="color: white; margin-bottom: 5px; margin-right: 10px; background-color: rgb(31, 65, 151); padding: 2px 10px;">
                                        NEW
                                    </div>
                                @endif
                                <div class="class-badge"
                                    style="color: white; margin-bottom: 5px; margin-right: 5px; background-color: {{ $warna }}; padding: 2px 10px;">
                                    <strong>{{ $data->course_category }}</strong>
                                </div>
                            </div>
                            <!-- Title -->
                            <h5 class="card-title"><a href="#">{{$data->course_title}}</a></h5>
                            <p class="mb-2 text-truncate-2 d-none">Proposal indulged no do sociable he throwing
                                settling.</p>


                            <hr style="margin-left: -20px; margin-right: -20px" class="mb-3 mt-2">

                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ $data->first_section ? url('course/'.$data->id.'/section/'.$data->first_section) : "javascript:void(0)" }}"
                                       class="btn text-white btn-round"
                                       style="background-color: {{ $data->first_section ? '#208DBB' : '#ffcb52' }}">{{ $data->first_section ? 'Check' : 'Kelas Belum Memiliki Materi' }}</a>
                                </div>

                                <p id="progressCourse" class="h6 mb-0 {{ $data->first_section ? '' : 'd-none' }}">{{ $progressPercentage }}% Completed</p>
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
                                <a style="text-decoration: none;color: BLACK;">
                                    <p style="font-size: 17px; margin-left: 10px; margin-top:28px;">
                                        <b> {{ $data->num_students_registered }} </b><span style="color: #8C94A3;">students</span>
                                    </p>
                                </a>
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


@endsection
