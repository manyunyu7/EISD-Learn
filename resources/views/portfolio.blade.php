@extends('main.template')


@section('head-section')
    @include('main.home._styling_home_student')
@endsection


@section('script')
    {{-- @include('main.home.script_student') --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
       
@endsection

@section('main')
<br><br>
    <div class="page-inner mt--5">
        <div class="row mt--2 border-primary">

            {{-- RECOMMENDATION --}}
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
                                        <li class="toga-container dropdown hidden-caret" style="display: flex; justify-content: space-between; align-items: center;">
                                            <img style="width: 12%; height: auto;" src="{{ url('/HomeIcons/Toga_MDLNTraining.svg') }}">
                                            <p>Modernland Training</p>
                                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                                                <img id="dotsThree" src="{{ url('/HomeIcons/DotsThree.svg') }}" alt="">
                                            </a>
                                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                                <div class="dropdown-user-scroll scrollbar-outer">
                                                    <li>
                                                        <a class="dropdown-item" href="#">Join Class</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href='{{ url("/class/class-list/view-class/").'/'.$data->id }}'>
                                                            <span class="link-collapse">View Class</span>
                                                        </a>
                                                    </li>
                                                </div>
                                            </ul>
                                        </li>
                                        <hr>
                                        <div style="display: flex; justify-content: center; align-items: center;">
                                            <img style="width: 6%; height: auto; margin-top: 12px" src="{{ url('/DashboardIcons/User.svg') }}" alt="Portfolio Icon">
                                            <p style="font-size: 17px; margin-left: 10px; margin-top:28px;"><b> 25 </b> students</p>
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
