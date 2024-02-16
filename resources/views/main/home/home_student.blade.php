@section('head-section')
    @include('main.home._styling_home_student')
@endsection


@section('script')
    {{-- @include('main.home.script_student') --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- <script>
        var userScores = @json($userScores);

        var sectionTitles = userScores.map(score => score.section_title);
        var scoreData = userScores.map(score => score.score);

        var ctx = document.getElementById('userScoresChart').getContext('2d');
        var userScoresChart = new Chart(ctx, {
            type: 'line', // Use bar chart for 3D effect
            data: {
                labels: sectionTitles,
                datasets: [{
                    label: 'User Scores',
                    data: scoreData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true // Adjust this based on your data
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'User Scores Chart'
                    }
                }
            }
        });
    </script> --}}

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

            {{-- PROFILE USER --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content mt-2 mb-5" id="pills-without-border-tabContent">
                            <div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel" aria-labelledby="pills-home-tab-nobd">
                                <div class="d-flex align-items-center"> {{-- Use flexbox for layout --}}
                                    <div class="mr-3"> {{-- Margin right for spacing --}}
                                        <img style="width: 100%; max-width: 130px; height: auto;"
                                            src="{{ Storage::url('public/profile/') . Auth::user()->profile_url }}"
                                            alt="Profile Image" class="avatar-img rounded-circle"
                                            onerror="this.onerror=null; this.src='{{ url('/default/default_profile.png') }}'; this.alt='Alternative Image';">
                                    </div>
                                    <div>
                                        <div class="card-head-row card-tools-still-right">
                                            <h1 style="color: black;"><b>{{ Auth::user()->name }}</b></h1>
                                            <p class="card-category">Digital Management</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="ml-auto mt-5"> {{-- Align to the right with ml-auto --}}
                            <div class="portfolio-container">
                                <img src="{{ url('/HomeIcons/Portfolio.svg') }}" alt="Portfolio Icon">
                                <p>{{ Auth::user()->url_personal_website }}</p>
                            </div>
                            <div class="social-icon">
                                <a href="https://facebook.com/.{{ Auth::user()->url_facebook }}" target="_blank" rel="noopener noreferrer" class="btn btnColor btn-icon">
                                    <img src="{{ url('/HomeIcons/Facebook.svg') }}"  alt="Facebook Icon">
                                </a>
                                <a href="https://www.linkedin.com/in/{{ Auth::user()->url_linkedin }}" target="_blank" rel="noopener noreferrer" class="btn btnColor btn-icon">
                                    <img src="{{ url('/HomeIcons/LinkedIn.svg') }}"  alt="Instagram Icon">
                                </a>
                                <a href="https://instagram.com/#" target="_blank" rel="noopener noreferrer" class="btn btnColor btn-icon">
                                    <img src="{{ url('/HomeIcons/Twitter.svg') }}"  alt="Instagram Icon">
                                </a>
                                <a href="https://instagram.com/{{ Auth::user()->url_instagram }}" target="_blank" rel="noopener noreferrer" class="btn btnColor btn-icon">
                                    <img src="{{ url('/HomeIcons/Instagram.svg') }}"  alt="Instagram Icon">
                                </a>
                                <a href="https://youtube.com/{{ Auth::user()->url_youtube }}" target="_blank" rel="noopener noreferrer" class="btn btnColor btn-icon">
                                    <img src="{{ url('/HomeIcons/Youtube.svg') }}"  alt="Instagram Icon">
                                </a>
                                <a href="https://wa.me/{{ Auth::user()->url_whatsapp }}" target="_blank" rel="noopener noreferrer" class="btn btnColor btn-icon">
                                    <img src="{{ url('/HomeIcons/Whatsapp.svg') }}" alt="Instagram Icon"> 
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- TEMPLATE CONTAINER --}}
            {{-- <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        
                    </div>
                </div>
            </div> --}}


            
            {{-- TRACKING DATA COURSES --}}
            <div class="col-md-8">
                <div class="card"> {{-- card --}}
                    <div class="card-body">
                        <div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
                            <div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel"
                                 aria-labelledby="pills-home-tab-nobd">
                                <div class="">
                                    <div class="">
                                        {{-- <canvas id="myChart" height="60"></canvas> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DASHBOARD --}}
            <div class="col-md-12">
                <h2><b>Dashboard</b></h2>
            </div>
            <div class="col-md-4">
                <div class="card" style="background-color: #FFEEE8">
                    <div class="card-body">
                        <div class="dashboard-container">
                            <img src="{{ url('/DashboardIcons/Frame 322.png') }}" alt="Portfolio Icon">
                            <h1>0</h1>
                            <p>Enrolled Courses</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="background-color: #EBEBFF">
                    <div class="card-body">
                        <div class="dashboard-container">
                            <img src="{{ url('/DashboardIcons/Frame 323.png') }}" alt="Portfolio Icon">
                            <h1>0</h1>
                            <p>Active Courses</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="background-color: #E1F7E3">
                    <div class="card-body">
                        <div class="dashboard-container">
                            <img src="{{ url('/DashboardIcons/Frame 324.png') }}" alt="Portfolio Icon">
                            <h1>0</h1>
                            <p>Completed Courses</p>
                        </div>
                    </div>
                </div>
            </div>
            

            {{-- MY CLASS--}}
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


            
            {{-- LEADERBOARD --}}
            {{-- <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Leaderboard Score Management Trainee</div>
                    </div>
                    <div class="card-body">

                        <div class="row justify-content-center">

                            @foreach ($topThree as $index => $student)
                                <div class="col-md-3 my-4 @if($index>2) d-none @endif">
                                    <div class="card card-profile">
                                        <div class="card-header"
                                             style="background-image: url('../assets/img/blogpost.jpg')">
                                            <div class="profile-picture">
                                                <div class="avatar avatar-xl">
                                                    <img
                                                        src="{{ Storage::url('public/profile/') . $student->profile_url }}"
                                                        alt="..."
                                                        class="avatar-img rounded-circle"
                                                        onerror="this.src='{{ asset('storage/profile/error.png') }}'">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="user-profile text-center">
                                                <div class="name">
                                                    <strong>#{{$index+1}}</strong> {{$student->student_name}}</div>
                                                <div class="social-media d-none">
                                                    <a class="btn btn-info btn-twitter btn-sm btn-link" href="#">
                                                        <span class="btn-label just-icon"><i
                                                                class="flaticon-twitter"></i> </span>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm btn-link" rel="publisher" href="#">
                                                        <span class="btn-label just-icon"><i
                                                                class="flaticon-google-plus"></i> </span>
                                                    </a>
                                                    <a class="btn btn-primary btn-sm btn-link" rel="publisher" href="#">
                                                        <span class="btn-label just-icon"><i
                                                                class="flaticon-facebook"></i> </span>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm btn-link" rel="publisher" href="#">
                                                        <span class="btn-label just-icon"><i
                                                                class="flaticon-dribbble"></i> </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="row user-stats text-center">
                                                <div class="col">
                                                    <div class="number">{{$student->total_score}}</div>
                                                    <div class="title">Total Point</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                        <div class="card-list d-none">
                            @foreach ($leaderboard as $index => $student)

                                <div class="item-list">
                                    <div class="avatar">
                                        <img src="{{ Storage::url('public/profile/') . $data->profile_url }}" alt="..."
                                             class="avatar-img rounded-circle">
                                    </div>
                                    <div class="info-user ml-3">
                                        <div class="username">
                                            {{ ($index+1)." ". $student->student_name }}
                                            @if ($index === 0)
                                                <span class="badge badge-primary">1st</span>
                                            @elseif ($index === 1)
                                                <span class="badge badge-secondary">2nd</span>
                                            @elseif ($index === 2)
                                                <span class="badge badge-success">3rd</span>
                                            @endif
                                        </div>
                                        <div class="status">{{$student->total_score}}</div>
                                    </div>
                                </div>
                                <div class="separator-dashed"></div>
                            @endforeach

                        </div>

                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Name</th>
                                <th>Total Score</th>
                            </tr>
                            </thead>
                            <tbody class="scrollable-table-body">
                            @foreach ($leaderboard as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="avatar">
                                            <img src="{{ Storage::url('public/profile/') . $student->profile_url }}"
                                                 alt="..." class="avatar-img rounded-circle">
                                        </div>
                                        {{ $student->student_name }}

                                        @if ($index === 0)
                                            <span class="badge badge-primary ml-1">1st</span>
                                        @elseif ($index === 1)
                                            <span class="badge badge-secondary ml-1">2nd</span>
                                        @elseif ($index === 2)
                                            <span class="badge badge-success ml-1">3rd</span>
                                        @endif
                                    </td>
                                    <td>{{ $student->total_score }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div> --}}

            {{-- SKORING --}}
            {{-- <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Skor Per Modul</div>
                    </div>
                    <div class="card-body">
                        <div class="card-sub">
                            Skor post test tiap-tiap modul
                        </div>
                        <div>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Kelas</th>
                                    <th>Skor</th>
                                </tr>
                                </thead>
                                <tbody class="scrollable-table-body">
                                @foreach ($userScores as $index => $item)
                                    <tr>
                                        <td>
                                            {{ $item->section_title }}
                                        </td>
                                        <td>{{ $item->score }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div> --}}


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
