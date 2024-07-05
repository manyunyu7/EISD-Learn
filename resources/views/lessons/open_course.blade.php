@extends('main.template_openCourse')


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
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-U9zBET2NSPdld3JMGN9s3Qa/s6zrmMzNMI7d7bPKL6KA6aSX4N2p1Nex/aD1xOfq" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
       
@endsection

@section('main')
<br><br>

    <div class="col-md-12" >
        {{-- BREADCRUMB --}}
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href={{url('/home')}}>Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Class</li>
            </ol>
        </nav>
    </div>
    
    <div class="row mt--2 border-primary col-md-12">
        
    </div>

    <div class="page-inner mt--5 col-md-12">
        <div class="row mt-3 border-primary col-md-12">
            {{-- RECOMMENDATION PAGES--}}
            <div class="col-md-12">
                <h2><b>My Class</b></h2>
            </div>
            {{-- DAFTAR KELASS --}}
            <div class="tab-content mt-1" id="pills-without-border-tabContent">
                <div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel"
                        aria-labelledby="pills-home-tab-nobd">
                    <div class="container-myClass">
                        <div class="card-body">
                            <div class="row row-eq-height">
                                @forelse ($myClasses as $data)
                                    @php
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
                                        
                                    @endphp
                                <div class="col-lg-3 col-sm-6 my-2">
                                    <div class="card recommendationCard" style="background-color: white !important">
                                        <a href="javascript:void();" data-switch="0">
                                            <img class="card-img-top" onerror="this.onerror=null; this.src='{{ url('/default/default_courses.jpeg') }}'; this.alt='Alternative Image';"
                                                    src="{{ Storage::url('public/class/cover/') . $data->course_cover_image }}"
                                                    alt="La Noyee">
                                        </a>
                                        <br>
                                        <p>
                                            <span class="badge dynamic-badge" style=" border-radius: 0; font-size: 13px; font-weight: bold">{{ $data->course_category }}</span>
                                        </p>
                                        <div class="course-info">
                                            <h4>{{ $data->course_title }}</h4>
                                            <br>
                                        </div>
                                        <hr>
                                        <div class="toga-container col-12">
                                            {{-- <a class="btn btn-custom col-3" href="{{ url('/my-class/open/'. $data->id.'/section'.'/'. $data->first_section) }}" >
                                                <span style="text-decoration: none; color: white; text-align: justify;">Check</span>
                                            </a> --}}
                                            <button type="button" class="btn btn-custom col-3" onclick="redirectToSection('{{ url('/my-class/open/'.$data->id.'/section/'.$data->first_section) }}')">
                                                <span>Check</span>
                                            </button>
                                            <script>
                                                function redirectToSection(url) {
                                                    window.location.href = url;
                                                }
                                            </script>
                                            <p id="progressCourse" class="col-8">{{ $progressPercentage }}% Completed</p>
                                        </div>
                                        <hr>
                                        <div style="display: flex; justify-content: center; align-items: center;">
                                            <img style="width: 6%; height: auto; margin-top: 12px" src="{{ url('/DashboardIcons/User.svg') }}" alt="Portfolio Icon">
                                            {{-- <p style="font-size: 17px; margin-left: 10px; margin-top:28px;"><b> {{ $data->num_students_registered }} </b> students</p> --}}
                                            <a style="text-decoration: none;color: BLACK;" href="{{ url('/class/class-list/students/' . $data->id) }}">
                                                <p style="font-size: 17px; margin-left: 10px; margin-top:28px;"><b> {{ $data->num_students_registered }} </b ><span style="color: #8C94A3;">students</span></p>
                                            </a>
                                        </div>
                                    </div> 
                                </div>
                                <script>
                                    var badges = document.querySelectorAll('.dynamic-badge');
                                
                                    badges.forEach(function (badge) {
                                        var selectedCategory = badge.textContent;
                                        var badgeColor, textColor;
                                
                                        switch (selectedCategory) {
                                            case 'Management Trainee':
                                                badgeColor = '#f7c8ca';
                                                textColor = '#D02025';
                                                break;
                                            case 'General':
                                                badgeColor = 'blue';
                                                break;
                                            case 'Design':
                                                badgeColor = 'green';
                                                break;
                                            case 'Finance & Accounting':
                                                badgeColor = 'purple';
                                                break;
                                            case 'Human Resource and Development':
                                                badgeColor = 'orange';
                                                break;
                                            case '3D Modelling':
                                                badgeColor = 'pink';
                                                break;
                                            case 'Digital Management':
                                                badgeColor = '#EBEBFF';
                                                textColor = '#342F98';
                                                break;
                                            case 'Marketing and Business':
                                                badgeColor = 'yellow';
                                                break;
                                            case 'Food and Beverage':
                                                badgeColor = 'brown';
                                                break;
                                            case 'Management':
                                                badgeColor = 'teal';
                                                break;
                                            case 'Social and Politics':
                                                badgeColor = 'indigo';
                                                break;
                                            case 'Office':
                                                badgeColor = 'maroon';
                                                break;
                                            case 'Outdoor Activity':
                                                badgeColor = 'lime';
                                                break;
                                            case 'Junior High School':
                                                badgeColor = 'navy';
                                                break;
                                            case 'Senior High School':
                                                badgeColor = 'olive';
                                                break;
                                
                                            default:
                                                badgeColor = 'gray';
                                        }
                                
                                        badge.style.backgroundColor = badgeColor;
                                        badge.style.color = textColor; // Set text color to white
                                    });
                                </script>
                                

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
                                @forelse ($myClasses as $data)
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
            
            <!-- Add this script at the end of your view file -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Check for the presence of the success message
                    @if(session('success'))
                        // Display an alert with the success message
                        alert("{{ session('success') }}");
                    @endif
                });
            </script>
            <!-- Add this script at the end of your view file -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Check for the presence of the error message
                    @if(session('error'))
                        // Display an alert with the error message
                        alert("{{ session('error') }}");
                    @endif
                });
            </script> 
    @endif

@endsection
