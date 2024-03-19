@extends('main.template')


@section('head-section')
    @include('main.home._styling_home_student')

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}

@endsection


@section('script')
    {{-- @include('main.home.script_student') --}}


    <script>
        const DISPLAY = true;
        const BORDER = true;
        const CHART_AREA = true;
        const TICKS = true;
        const ctx = document.getElementById('myChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [
                    {
                        label: 'Blue Line',
                        data: [12, 19, 3, 5, 2, 3, 12, 19, 3, 5, 2, 3],
                        borderColor: 'blue',
                        borderWidth: 2,
                        fill: false
                    },
                    {
                        label: 'Red Line',
                        data: [5, 9, 8, 2, 6, 7, 5, 9, 8, 2, 6, 7],
                        borderColor: 'red',
                        borderWidth: 2,
                        fill: false
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
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
    <br><br>

    <div class="col-md-12">
        {{-- BREADCRUMB --}}
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href={{url('/home')}}>Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Class List</li>
            </ol>
        </nav>
    </div>

    <div class="row mt--2 border-primary col-md-12">
        {{-- DROPDOWN FILTER --}}
        <div class="row page-inner col-md-12">
            <div class="col-sm-3 col-md-5 col-lg-2 mb-3">
                <p>Sort by:</p>
                <div class="btn-group">
                    <button type="button" class="btn btnSort-custom" style="padding-right: 150px; width: 200px"
                            id="sortBtn"><span>Latest</span></button>
                    <button type="button" class="btn btnSort-custom dropdown-toggle dropdown-toggle-split"
                            id="sortDropdownToggle" data-bs-toggle="dropdown" aria-expanded="false"
                            data-bs-reference="parent">
                        <span class="visually-hidden"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="sortDropdownToggle" style="width: 100%;"
                        id="sortDropdown">
                        <li><a class="dropdown-item text-left" href="#" onclick="changeSortText('Latest')">Latest</a>
                        </li>
                        <li><a class="dropdown-item text-left" href="#"
                               onclick="changeSortText('Recommend')">Recommend</a></li>
                        <li><a class="dropdown-item text-left" href="#" onclick="changeSortText('Most Student')">Most
                                Student</a></li>
                    </ul>
                </div>

                <script>
                    function changeSortText(selectedTextSort) {
                        document.getElementById('sortBtn').innerHTML = '<span>' + selectedTextSort + '</span>';
                    }
                </script>
            </div>

            <div class="col-sm-3 col-md-5 col-lg-2">
                <p>Category:</p>
                <div class="btn-group">
                    <button type="button" class="btn btnSort-custom" style="padding-right: 150px; width: 200px"
                            id="categoryBtn"><span>All Category</span></button>
                    <button type="button" class="btn btnSort-custom dropdown-toggle dropdown-toggle-split"
                            id="categoryDropdownToggle" data-bs-toggle="dropdown" aria-expanded="false"
                            data-bs-reference="parent">
                        <span class="visually-hidden"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="categoryDropdownToggle" style="width: 100%;"
                        id="categoryDropdown">
                        <li><a class="dropdown-item text-left" href="#"
                               onclick="changeCategoryText('Management Trainee')">Management Trainee</a></li>
                        <li><a class="dropdown-item text-left" href="#"
                               onclick="changeCategoryText('Digital Management')">Digital Management</a></li>
                        <li><a class="dropdown-item text-left" href="#"
                               onclick="changeCategoryText('General')">General</a></li>
                    </ul>
                </div>

                <script>
                    function changeCategoryText(selectedTextCategory) {
                        document.getElementById('categoryBtn').innerHTML = '<span>' + selectedTextCategory + '</span>';
                    }
                </script>
            </div>

            <div class="col-sm-6 col-md-2 col-lg-8">

            </div>
        </div>
    </div>

    <div class="page-inner col-md-12">
        <div class="row mt-3 border-primary col-md-12">
            {{-- RECOMMENDATION PAGES--}}
            <div class="col-md-12">
                <h2><b>Recommendation</b></h2>
            </div>
            {{-- DAFTAR KELASS --}}
            <div class="tab-content mt-1" id="pills-without-border-tabContent">
                <div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel"
                     aria-labelledby="pills-home-tab-nobd">
                    <div class="container-myClass">
                        <div class="card-body">
                            <div class="row row-eq-height">

                                @forelse ($classes as $data)
                                    <div class="col-lg-4 col-xl-3 col-sm-6">
                                        <div class="card recommendationCard" style="background-color: white !important;
                                            @if(count($classes)==1) min-width:250px; @endif ">
                                            <a href="javascript:void();" data-switch="0">
                                                <img style="max-height: 300px; min-height: 200px; object-fit: cover" class="card-img-top"
                                                     onerror="this.onerror=null; this.src='{{ url('/default/default_courses.jpeg') }}'; this.alt='Alternative Image';"
                                                     src="{{ Storage::url('public/class/cover/') . $data->course_cover_image }}"
                                                     alt="La Noyee">
                                            </a>
                                            <br>
                                            <p>
                                                <span class="badge dynamic-badge mr-2"
                                                      style=" border-radius: 0; font-size: 13px; font-weight: bold">{{ $data->course_category }}</span>
                                            </p>
                                            <div class="course-info">
                                                <h4>{{ $data->course_title }}</h4>
                                                <br>
                                            </div>
                                            <hr>
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
                                            <li class="toga-container dropdown hidden-caret"
                                                style="display: flex; justify-content: space-between; align-items: center;">
                                                <img style="width: 12%; height: auto;"
                                                     src="{{ url('/HomeIcons/Toga_MDLNTraining.svg') }}">
                                                <p>{{ $data->mentor_name }}</p>
                                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                                                    <img id="dotsThree" src="{{ url('/HomeIcons/DotsThree.svg') }}" alt="">
                                                </a>
                                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                                    <div class="dropdown-user-scroll scrollbar-outer">
                                                        <li>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                               data-bs-target="#exampleModal{{ $data->id }}"
                                                               data-bs-whatever="@mdo">Join Class</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item"
                                                               href="{{ url('/class/class-list/view-class/' . $data->id) }}">
                                                                <span class="link-collapse">View Class</span>
                                                            </a>
                                                        </li>
                                                    </div>
                                                </ul>
                                                <!-- Modal -->
                                                      <form method="POST" action="{{ url('/input-pin') }}">
                                                    {{-- cek Token CSRF --}}
                                                    @csrf
                                                    <div class="modal fade" id="exampleModal{{ $data->id }}"
                                                         tabindex="-1" aria-labelledby="exampleModalLabel"
                                                         aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title" id="exampleModalLabel"><b>Masukan
                                                                            PIN</b></h1>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body center"
                                                                     style="justify-content: center">
                                                                    <p>Untuk masuk ke dalam kelas, silakan masukan PIN
                                                                        terlebih dahulu</p>
                                                                    <div class="mb-3">
                                                                        <!-- Hidden Input -->
                                                                        <input type="hidden" id="hiddenField"
                                                                               name="idClass" value='{{ $data->id }}'>
                                                                        <!-- PIN Input -->
                                                                        <input name="pin"
                                                                               style="border: 1px solid #ced4da;"
                                                                               class="form-control" type="text" id="pin"
                                                                               required
                                                                               placeholder="Masukan PIN disini">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger"
                                                                            data-bs-dismiss="modal">Cancel
                                                                    </button>
                                                                    <button type="submit" class="btn "
                                                                            style="background-color: #208DBB"><span
                                                                                style="color: white">Submit</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </li>
                                            <hr>
                                            <div style="display: flex; justify-content: center; align-items: center;">
                                                <img style="width: 6%; height: auto; margin-top: 12px"
                                                     src="{{ url('/icons/user_lesson_card.png') }}"
                                                     alt="Portfolio Icon">
                                                <a style="text-decoration: none;color: BLACK;"
                                                   href="{{ url('/class/class-list/students/' . $data->id) }}">
                                                    <p style="font-size: 17px; margin-left: 10px; margin-top:28px;">
                                                        <b> {{ $data->num_students_registered }} </b><span
                                                                style="color: #8C94A3;">students</span></p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <p>{{ $data->mentor_name }}</p> --}}
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
                                    <strong class="w-100 text-center">Anda Belum Terdaftar di Kelas
                                        Manapun</strong>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-contact-nobd" role="tabpanel"
                     aria-labelledby="pills-contact-tab-nobd">
                    <div class="">
                        <div class="">
                            <div class="card-head-row card-tools-still-right">
                                <h4 class="card-title">Daftar Ke Kelas Lain</h4>
                            </div>
                            <p class="card-category">
                                Cari Kelas Lain Yang Mungkin Menarik Untuk Dipelajari</p>
                        </div>
                        <div class="card-body">
                            <div class="row row-eq-height">
                                @forelse ($classes as $data)
                                    <div class="col-lg-4 col-sm-6 my-2">
                                        <div class="album-poster-parent"
                                             style="background-color: white !important">
                                            <a href="javascript:void();" class="album-poster"
                                               data-switch="0">
                                                <img class="fufufu"
                                                     onerror="this.onerror=null; this.src='./assets/album/n5'"
                                                     src="{{ Storage::url('public/class/cover/') . $data->course_cover_image }}"
                                                     alt="La Noyee">
                                            </a>
                                            <br>
                                            <div class="course-info">
                                                <h4>{{ $data->course_title }}</h4>

                                            </div>
                                            <p><span
                                                        class="badge badge-primary">{{ $data->course_category }}</span>
                                            </p>

                                            <div class="d-flex">
                                                <div class="avatar">
                                                    <img
                                                            src="{{ Storage::url('public/profile/') . $data->profile_url }}"
                                                            alt="..." class="avatar-img rounded-circle">
                                                </div>
                                                <div class="info-post ml-2">
                                                    <p style="margin-bottom: 1px !important"
                                                       class="username">
                                                        {{ $data->mentor_name }}</p>
                                                    {{ $data->created_at }}
                                                </div>
                                            </div>

                                            <div class="mt-2">
                                                <a href="{{ url("/lesson/$data->id") }}">
                                                    <button type="submit"
                                                            class="btn btn-primary btn-xs btn-block mb-2">
                                                        Lihat
                                                        Kelas
                                                    </button>
                                                </a>
                                                <form action="{{ route('course.register') }}" method="POST"
                                                      enctype="multipart/form-data">
                                                    @csrf
                                                    <input class="d-none" type="text" name="course_id"
                                                           value="{{ $data->id }}" id="">
                                                    <button type="submit"
                                                            class="btn btn-outline-primary btn-xs">Daftar
                                                        Kelas
                                                        Ini
                                                    </button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>

                                    {{-- <p>{{ $data->mentor_name }}</p> --}}
                                @empty
                                    <div class="alert alert-primary" role="alert">
                                        <strong>Belum Ada Kelas Tersedia</strong>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (session()->has('success'))
                <script>
                    toastr.success('{{ session('
                        success ') }}',
                        ' {{ Session::get('success') }}');

                </script>
            @elseif(session()-> has('error'))
                <script>
                    toastr.error('{{ session('
                        error ') }}', ' {{ Session::get('error') }}');

                </script>

    @endif

@endsection
