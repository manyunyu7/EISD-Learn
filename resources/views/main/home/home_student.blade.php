@section('head-section')
    @include('main.home._styling_home_student')
@endsection


@section('script')
    {{-- @include('main.home.script_student') --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
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
                datasets: [{
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

    <script>
        window.onload = function() {
            var jan = <?php echo json_encode($jan); ?>;
            var feb = <?php echo json_encode($feb); ?>;
            var mar = <?php echo json_encode($mar); ?>;
            var apr = <?php echo json_encode($apr); ?>;
            var mei = <?php echo json_encode($mei); ?>;
            var jun = <?php echo json_encode($jun); ?>;
            var jul = <?php echo json_encode($jul); ?>;
            var agt = <?php echo json_encode($agt); ?>;
            var sep = <?php echo json_encode($sep); ?>;
            var okt = <?php echo json_encode($okt); ?>;
            var nov = <?php echo json_encode($nov); ?>;
            var des = <?php echo json_encode($des); ?>;


            var chart1 = new CanvasJS.Chart("chartContainer1", {
                animationEnabled: true,
                axisX: {
                    interval: 1,
                    intervalType: "month",
                    valueFormatString: "MM",
                    tickLength: 0,
                    lineThickness: 0,
                    gridThickness: 0
                },
                axisY: {
                    lineThickness: 0,
                    tickLength: 0,
                    gridThickness: 0,
                    labelFontSize: 0
                },
                legend: {
                    reversed: true,
                    verticalAlign: "center",
                    horizontalAlign: "right"
                },
                data: [{
                        type: "stackedColumn100",
                        name: "Kelas Yang Selesai",
                        showInLegend: false,
                        xValueFormatString: "MM",
                        markerColor: "#23BD33",
                        dataPoints: [{
                                label: "Jan",
                                y: jan.completed_count,
                                color: "#23BD33"
                            },
                            {
                                label: "Feb",
                                y: feb.completed_count,
                                color: "#23BD33"
                            },
                            {
                                label: "Mar",
                                y: mar.completed_count,
                                color: "#23BD33"
                            },
                            {
                                label: "Apr",
                                y: apr.completed_count,
                                color: "#23BD33"
                            },
                            {
                                label: "May",
                                y: mei.completed_count,
                                color: "#23BD33"
                            },
                            {
                                label: "Jun",
                                y: jun.completed_count,
                                color: "#23BD33"
                            },
                            {
                                label: "Jul",
                                y: jul.completed_count,
                                color: "#23BD33"
                            },
                            {
                                label: "Aug",
                                y: agt.completed_count,
                                color: "#23BD33"
                            },
                            {
                                label: "Sep",
                                y: sep.completed_count,
                                color: "#23BD33"
                            },
                            {
                                label: "Okt",
                                y: okt.completed_count,
                                color: "#23BD33"
                            },
                            {
                                label: "Nov",
                                y: nov.completed_count,
                                color: "#23BD33"
                            },
                            {
                                label: "Dec",
                                y: des.completed_count,
                                color: "#23BD33"
                            }
                        ],
                        indexLabel: "{y}",
                        indexLabelPlacement: "outside",
                        indexLabelFontColor: "#333",
                        indexLabelFontSize: 14
                    },
                    {
                        type: "stackedColumn100",
                        name: "Kuota Kelas Tersisa",
                        showInLegend: false,
                        xValueFormatString: "MM",
                        markerColor: "#ccf7d1",
                        dataPoints: [{
                                label: "Jan",
                                y: (5 - jan.completed_count),
                                color: "#ccf7d1"
                            },
                            {
                                label: "Feb",
                                y: (5 - feb.completed_count),
                                color: "#ccf7d1"
                            },
                            {
                                label: "Mar",
                                y: (5 - mar.completed_count),
                                color: "#ccf7d1"
                            },
                            {
                                label: "Apr",
                                y: (5 - apr.completed_count),
                                color: "#ccf7d1"
                            },
                            {
                                label: "May",
                                y: (5 - mei.completed_count),
                                color: "#ccf7d1"
                            },
                            {
                                label: "Jun",
                                y: (5 - jun.completed_count),
                                color: "#ccf7d1"
                            },
                            {
                                label: "Jul",
                                y: (5 - jul.completed_count),
                                color: "#ccf7d1"
                            },
                            {
                                label: "Aug",
                                y: (5 - agt.completed_count),
                                color: "#ccf7d1"
                            },
                            {
                                label: "Sep",
                                y: (5 - sep.completed_count),
                                color: "#ccf7d1"
                            },
                            {
                                label: "Okt",
                                y: (5 - okt.completed_count),
                                color: "#ccf7d1"
                            },
                            {
                                label: "Nov",
                                y: (5 - nov.completed_count),
                                color: "#ccf7d1"
                            },
                            {
                                label: "Dec",
                                y: (5 - des.completed_count),
                                color: "#ccf7d1"
                            }
                        ]
                    }
                ]
            });
            chart1.render();

            var resultPostTest = <?php echo json_encode($postTestScore); ?>;
            var dataPoints = [];


            resultPostTest.forEach(function(item) {
                var xValue = item.title_exam;
                var yValue = parseInt(item.highest_currentScore);

                dataPoints.push({
                    label: xValue,
                    y: yValue
                });
            });

            console.log(dataPoints);
            var chart2 = new CanvasJS.Chart("chartContainer2", {
                axisX: {
                    interval: 1,
                    labelAngle: -45
                },
                axisY: {
                    minimum: 0,
                    maximum: 100,
                    labelFormatter: function(e) {
                        return parseInt(e.value);
                    }
                },
                data: [{
                    type: "line",
                    dataPoints: dataPoints
                }]
            });

            chart2.render();
        }
    </script>
@endsection

@section('main')
    <div class="page-inner mt-5" style="background-color: white!important;">
        <div class="row mt--2 border-primary">

            {{-- PROFILE USER --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content mt-2 mb-5" id="pills-without-border-tabContent">
                            <div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel"
                                aria-labelledby="pills-home-tab-nobd">
                                <div class="d-flex align-items-center"> {{-- Use flexbox for layout --}}
                                    <div class="mr-3"> {{-- Margin right for spacing --}}
                                        <img style="width: 130px; height: 130px; object-fit: cover"
                                            src="{{ env('AWS_BASE_URL') . Auth::user()->profile_url }}" alt="Profile Image"
                                            class="avatar-img rounded-circle"
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

                        <div class="ml-auto mt-5">
                            <div class="portfolio-container">
                                <img src="{{ url('/home_icons/Portfolio.svg') }}" alt="Portfolio Icon">
                                <p>{{ Auth::user()->url_personal_website }}</p>
                            </div>
                            <div class="social-icon">
                                <a href="https://facebook.com/.{{ Auth::user()->url_facebook }}" target="_blank"
                                    rel="noopener noreferrer" class="btn btnColor btn-icon">
                                    <img src="{{ url('/home_icons/Facebook.svg') }}" alt="Facebook Icon">
                                </a>
                                <a href="https://www.linkedin.com/in/{{ Auth::user()->url_linkedin }}" target="_blank"
                                    rel="noopener noreferrer" class="btn btnColor btn-icon">
                                    <img src="{{ url('/home_icons/linkedin.svg') }}" alt="Instagram Icon">
                                </a>
                                <a href="https://instagram.com/#" target="_blank" rel="noopener noreferrer"
                                    class="btn btnColor btn-icon">
                                    <img src="{{ url('/home_icons/Twitter.svg') }}" alt="Instagram Icon">
                                </a>
                                <a href="https://instagram.com/{{ Auth::user()->url_instagram }}" target="_blank"
                                    rel="noopener noreferrer" class="btn btnColor btn-icon">
                                    <img src="{{ url('/home_icons/Instagram.svg') }}" alt="Instagram Icon">
                                </a>
                                <a href="https://youtube.com/{{ Auth::user()->url_youtube }}" target="_blank"
                                    rel="noopener noreferrer" class="btn btnColor btn-icon">
                                    <img src="{{ url('/home_icons/Youtube.svg') }}" alt="Instagram Icon">
                                </a>
                                <a href="https://wa.me/{{ Auth::user()->url_whatsapp }}" target="_blank"
                                    rel="noopener noreferrer" class="btn btnColor btn-icon">
                                    <img src="{{ url('/home_icons/Whatsapp.svg') }}" alt="Instagram Icon">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TRACKING DATA COURSES --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-9">
                                <h3><strong>Data Completed Courses (Pcs)</strong></h3>
                            </div>

                            <div class="col-3">
                                <select class="form-control" id="exampleFormControlSelect3" style="border: none;">
                                    <option>2024</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
                            <div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel"
                                aria-labelledby="pills-home-tab-nobd">
                                <div class="">
                                    <div id="chartContainer1" style="height: 370px; width: 100%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TRACKING POST TEST SCORE --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-9">
                                <h3><strong>Post Test Score</strong></h3>
                            </div>

                            <div class="col-3">
                                <select class="form-control" id="exampleFormControlSelect3" style="border: none;">
                                    <option>Januari</option>
                                    <option>Februari</option>
                                    <option>Maret</option>
                                    <option>April</option>
                                    <option>Mei</option>
                                    <option>Juni</option>
                                    <option>Juli</option>
                                    <option>Agustus</option>
                                    <option>September</option>
                                    <option>Oktober</option>
                                    <option>November</option>
                                    <option>Desember</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
                            <div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel"
                                aria-labelledby="pills-home-tab-nobd">
                                <div class="">
                                    <div id="chartContainer2" style="height: 370px; width: 100%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- DASHBOARD --}}
            <div class="col-md-12">
                <h1><strong>Dashboard</strong></h1>
            </div>

            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round" style="background-color: #FFEEE8">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center bubble-shadow-small">
                                    <img src="{{ url('icons/dashboard_icon/enrolled.png') }}" alt="Portfolio Icon">
                                </div>
                            </div>
                            <div class="col col-stats ml-3 ml-sm-0">
                                <div class="numbers">
                                    <h4 class="card-title">{{ $classRegisteredCount }}</h4>
                                    <p class="card-category">Enrolled Course</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round" style="background-color: #EBEBFF">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center bubble-shadow-small">
                                    <img src="{{ url('icons/dashboard_icon/active.png') }}" alt="Portfolio Icon">
                                </div>
                            </div>
                            <div class="col col-stats ml-3 ml-sm-0">
                                <div class="numbers">
                                    <h4 class="card-title">{{ $activeCourse }}</h4>
                                    <p class="card-category">Active Course</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4">
                <div class="card card-stats card-round" style="background-color: #E1F7E3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center bubble-shadow-small">
                                    <img src="{{ url('icons/dashboard_icon/completed.png') }}" alt="Portfolio Icon">
                                </div>
                            </div>
                            <div class="col col-stats ml-3 ml-sm-0">
                                <div class="numbers">
                                    <h4 class="card-title">{{ $completedCourse }}</h4>
                                    <p class="card-category">Completed Course</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MY CLASS --}}
            <div class="col-md-12">
                <h1><strong>My Class</strong></h1>
            </div>

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
                        $hasTaken = DB::select("SELECT
                                    a.*
                                    FROM
                                        student_section a
                                    LEFT JOIN
                                        course_section b  ON a.section_id = b.id
                                    WHERE
                                        a.student_id = $userID AND b.course_id = $data->id;
                                    ");
                        $totalSections = count($silabusClass);
                        $total_hasTaken = count($hasTaken);
                        if ($totalSections != null and $total_hasTaken != null) {
                            $progressPercentage = round(($total_hasTaken / $totalSections) * 100);
                        } else {
                            $progressPercentage = 0;
                        }

                    @endphp

                    <div class="col-sm-6 col-xl-4">
                        <div class="card shadow ">
                            <!-- Image -->
                            <img class="card-img-top" style="aspect-ratio: 16 / 9"
                                onerror="this.onerror=null; this.src='{{ url('/default/default_courses.jpeg') }}'; this.alt='Course Image';"
                                src="{{ env('AWS_BASE_URL') . $data->course_cover_image }}" alt="La Noyee">
                            {{--                            <img src="assets/images/courses/4by3/08.jpg"  class="card-img-top" alt="course image"> --}}
                            <!-- Card body -->
                            <div class="card-body">
                                <!-- Badge and favorite -->
                                <div
                                    style="width: 100%; display: flex; flex-wrap: wrap; justify-content: left; align-items: flex-start; margin-bottom: .5rem;">
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
                                    <div>
                                        <a href="{{ url('course/' . $data->id . '/section/' . $data->first_section) }}"
                                            class="btn text-white btn-round " style="background-color: #208DBB">Check</a>
                                    </div>

                                    {{--                                    <span class="h6 fw-light mb-0"><i class="fas fa-table text-orange me-2"></i>15 lectures</span> --}}
                                    <p id="progressCourse" class="h6 mb-0">{{ $progressPercentage }}%
                                        Completed</p>

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
                                            <b> {{ $data->num_students_registered }} </b><span
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
                    <strong class="w-100 text-center">Anda Belum Terdaftar di Kelas Manapun</strong>
                @endforelse
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
                                <div class="col-md-3 my-4 @if ($index > 2) d-none @endif">
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
                    toastr.success(
                        '{{ session('
                                                                                                            success ') }}',
                        ' {{ Session::get('success') }}');
                </script>
            @elseif(session()->has('error'))
                <script>
                    toastr.error(
                        '{{ session('
                                                                                                            error ') }}',
                        ' {{ Session::get('error') }}');
                </script>
            @endif
        @endsection
