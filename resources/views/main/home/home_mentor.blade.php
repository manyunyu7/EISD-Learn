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

    {{-- canvasJS for Pie Chart --}}
    <script type="text/javascript">
        window.onload = function () {
            // Script untuk Pie Chart
            var chart_pie = new CanvasJS.Chart("chartContainer_pieChart",
            {
                theme: "light2",
                title:{
                    text: "Title of Pie Chart"
                },
                legend: {
                    horizontalAlign: "right", // Atur posisi horizontal legend ke kanan
                    verticalAlign: "center", // Atur posisi vertikal legend ke tengah
                    fontSize: 14 // Atur ukuran font untuk legend
                },
                data: [
                    {
                        type: "pie",
                        showInLegend: true,
                        toolTipContent: "{y} - #percent %",
                        yValueFormatString: "#,##0,,.## Million",
                        legendText: "{indexLabel}",
                        dataPoints: [
                            { y: 4181563, indexLabel: "Human Resource", color: "#B63607" }, // Merah
                            { y: 2175498, indexLabel: "Digital Management", color: "#E0460C" }, // Hijau
                            { y: 3125844, indexLabel: "Finance & Accounting", color: "#FF7F00" }, // Biru
                            { y: 1176121, indexLabel: "Digital Marketing", color: "#FFA500" }, // Kuning
                            { y: 1727161, indexLabel: "Legal", color: "#FFD700" }, // Magenta
                            { y: 4303364, indexLabel: "Project", color: "#E2D278" }, // Cyan
                        ]
                    }
                ]

            });
            chart_pie.render();

            // Script untuk Stacked Bar
            var chart_stackedBar = new CanvasJS.Chart("chartContainer_stackedBar",
            {
                title:{
                    text: "Division of products Sold in Quarter."
                },
                toolTip: {
                    shared: true
                },
                axisY:{
                    title: "percent"
                },
                data:[
                {
                    type: "stackedBar100",
                    showInLegend: true,
                    name: "April",
                    dataPoints: [
                        {y: 600, label: "Water Filter" },
                        {y: 400, label: "Modern Chair" },
                        {y: 120, label: "VOIP Phone" },
                        {y: 250, label: "Microwave" },
                        {y: 120, label: "Water Filter" },
                        {y: 374, label: "Expresso Machine" },
                        {y: 350, label: "Lobby Chair" }

                    ]
                },
                {
                    type: "stackedBar100",
                    showInLegend: true,
                    name: "May",
                    dataPoints: [
                        {y: 400, label: "Water Filter" },
                        {y: 500, label: "Modern Chair" },
                        {y: 220, label: "VOIP Phone" },
                        {y: 350, label: "Microwave" },
                        {y: 220, label: "Water Filter" },
                        {y: 474, label: "Expresso Machine" },
                        {y: 450, label: "Lobby Chair" }

                    ]
                },
                {
                    type: "stackedBar100",
                    showInLegend: true,
                    name: "June",
                    dataPoints: [
                        {y: 300, label: "Water Filter" },
                        {y: 610, label: "Modern Chair" },
                        {y: 215, label: "VOIP Phone" },
                        {y: 221, label: "Microwave" },
                        {y: 75, label: "Water Filter" },
                        {y: 310, label: "Expresso Machine" },
                        {y: 340, label: "Lobby Chair" }

                    ]
                }

                ]

            });
            chart_stackedBar.render();
        }
    </script>
    <script type="text/javascript" src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
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


    {{-- Charts.css --}}
    {{-- <link rel="stylesheet" href="path/to/your/charts.min.css"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css">
@endsection

@section('main')
    <div class="container">
        <div class="page-inner" style="background-color: none;">
            <div class="row mt--2 border-primary">
                {{-- PROFILE USER --}}
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="tab-content mt-2 mb-5" id="pills-without-border-tabContent">
                                <div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel"
                                     aria-labelledby="pills-home-tab-nobd">
                                    <div class="d-flex align-items-center"> {{-- Use flexbox for layout --}}
                                        <div class="mr-3"> {{-- Margin right for spacing --}}
                                            <img style="width: 100%; max-width: 130px; height: auto;  max-height: 130px"
                                                 src="{{ Storage::url('public/profile/') . Auth::user()->profile_url }}"
                                                 alt="Profile Image" class="avatar-img rounded-circle"
                                                 onerror="this.onerror=null; this.src='{{ url('/default/default_profile.png') }}'; this.alt='Alternative Image';">
                                        </div>
                                        <div>
                                            <div class="card-head-row card-tools-still-right">
                                                <h3 style="color: black;"><b>{{ Auth::user()->name }}</b></h3>
                                                <p class="card-category">Digital Management</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ml-auto mt-5"> {{-- Align to the right with ml-auto --}}
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

                {{-- PIE CHART --}}
                <div class="col-md-7">
                    <div class="card"> {{-- card --}}
                        <div class="card-body">
                            <div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
                                <div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel"
                                     aria-labelledby="pills-home-tab-nobd">
                                    <div class="">
                                        <div id="chartContainer_pieChart" style="height: 235px; width: 100%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- canvasJS for Pie Chart --}}
                {{-- <script type="text/javascript">
                    window.onload = function () {
                        var chart = new CanvasJS.Chart("chartContainer_pieChart",
                        {
                            theme: "light2",
                            title:{
                                text: "Gaming Consoles Sold in 2012"
                            },
                            data: [
                            {
                                type: "pie",
                                showInLegend: true,
                                toolTipContent: "{y} - #percent %",
                                yValueFormatString: "#,##0,,.## Million",
                                legendText: "{indexLabel}",
                                dataPoints: [
                                    {  y: 4181563, indexLabel: "PlayStation 3" },
                                    {  y: 2175498, indexLabel: "Wii" },
                                    {  y: 3125844, indexLabel: "Xbox 360" },
                                    {  y: 1176121, indexLabel: "Nintendo DS"},
                                    {  y: 1727161, indexLabel: "PSP" },
                                    {  y: 4303364, indexLabel: "Nintendo 3DS"},
                                    {  y: 1717786, indexLabel: "PS Vita"}
                                ]
                            }
                            ]
                        });
                        chart.render();
                    }
                </script> --}}

                {{-- DASHBOARD --}}
                <div class="col-md-12">
                    <h1><strong>Dashboard</strong></h1>
                </div>

                    {{-- Total Class--}}
                    <div class="col-sm-6 col-md-6">
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
                                            <p class="card-category">Total Class</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Total Students --}}
                    <div class="col-sm-6 col-md-6">
                        <div class="card card-stats card-round" style="background-color: #E1F7E3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center bubble-shadow-small">
                                            <img src="{{ url('icons/dashboard_icon/students.png') }}" alt="Portfolio Icon">
                                        </div>
                                    </div>
                                    <div class="col col-stats ml-3 ml-sm-0">
                                        <div class="numbers">
                                            <h4 class="card-title">{{ $studentCount }}</h4>
                                            <p class="card-category">Total Students</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- On Progress Course --}}
                    <div class="col-sm-6 col-md-6">
                        <div class="card card-stats card-round" style="background-color: #E1F7E3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center bubble-shadow-small">
                                            <img src="{{ url('icons/dashboard_icon/active.png') }}" alt="Portfolio Icon">
                                        </div>
                                    </div>
                                    <div class="col col-stats ml-3 ml-sm-0">
                                        <div class="numbers">
                                            <h4 class="card-title">{{ $onProgressCount }}</h4>
                                            <p class="card-category">On Progress Course</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Completed Course --}}
                    <div class="col-sm-6 col-md-6">
                        <div class="card card-stats card-round" style="background-color: #FFEEE8">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center bubble-shadow-small">
                                            <img src="{{ url('icons/dashboard_icon/completed.png') }}" alt="Portfolio Icon">
                                        </div>
                                    </div>
                                    <div class="col col-stats ml-3 ml-sm-0">
                                        <div class="numbers">
                                            <h4 class="card-title">{{ $completedCourseCount }}</h4>
                                            <p class="card-category">Completed Course</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                <div class="col-md-12">
                    <div class="card"> {{-- card --}}
                        <div class="card-header">
                            <div class="row">
                                <div class="col-4">
                                    <h3><strong>Average Score Post Test</strong></h3>
                                </div>
                                <div class="col-3">
                                    <select class="form-control col-9" id="exampleFormControlSelect1" style="border: none;">
                                        <option>Business Unit</option>
                                        <option>2</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <select class="form-control" id="exampleFormControlSelect2" style="border: none;">
                                        <option>Department</option>
                                        <option>2</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <select class="form-control" id="exampleFormControlSelect3" style="border: none;">
                                        <option>Month</option>
                                        <option>2</option>
                                    </select>
                                </div>
                            </div>
                        </div>



                        <div class="card-body">
                            <div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
                                <div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel"
                                    aria-labelledby="pills-home-tab-nobd">
                                    <div class="">
                                        <table class="charts-css bar multiple stacked show-labels show-primary-axis data-spacing-8">
                                            <caption> Front End Developer Salary </caption>
                                            <tbody>
                                                @foreach ($averageScoreArray as $postTest)
                                                    <tr>
                                                        <th scope="row">{{ $postTest['title_exam'] }}</th>
                                                        <td style="--size: calc( {{ $postTest['average_score'] }} ); background-color:#23BD33; color:white">
                                                            <span class="data mr-2">{{ round($postTest['average_score']) }}%</span>
                                                        </td>
                                                        <td style="--size: calc( 100 - {{ $postTest['average_score'] }}); background-color:#ccf7d1">
                                                            <span class="data"></span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- canvasJS for Stacked Bar --}}
                {{-- <script type="text/javascript">
                    window.onload = function () {
                    var chart = new CanvasJS.Chart("chartContainer_stackedBar",
                    {
                        title:{
                        text: "Division of products Sold in Quarter."
                        },
                        toolTip: {
                        shared: true
                        },
                        axisY:{
                        title: "percent"
                        },
                        data:[
                        {
                        type: "stackedBar100",
                        showInLegend: true,
                        name: "April",
                        dataPoints: [
                        {y: 600, label: "Water Filter" },
                        {y: 400, label: "Modern Chair" },
                        {y: 120, label: "VOIP Phone" },
                        {y: 250, label: "Microwave" },
                        {y: 120, label: "Water Filter" },
                        {y: 374, label: "Expresso Machine" },
                        {y: 350, label: "Lobby Chair" }

                        ]
                        },
                        {
                        type: "stackedBar100",
                        showInLegend: true,
                        name: "May",
                        dataPoints: [
                        {y: 400, label: "Water Filter" },
                        {y: 500, label: "Modern Chair" },
                        {y: 220, label: "VOIP Phone" },
                        {y: 350, label: "Microwave" },
                        {y: 220, label: "Water Filter" },
                        {y: 474, label: "Expresso Machine" },
                        {y: 450, label: "Lobby Chair" }

                        ]
                        },
                        {
                        type: "stackedBar100",
                        showInLegend: true,
                        name: "June",
                        dataPoints: [
                        {y: 300, label: "Water Filter" },
                        {y: 610, label: "Modern Chair" },
                        {y: 215, label: "VOIP Phone" },
                        {y: 221, label: "Microwave" },
                        {y: 75, label: "Water Filter" },
                        {y: 310, label: "Expresso Machine" },
                        {y: 340, label: "Lobby Chair" }

                        ]
                        }

                        ]

                    });

                    chart.render();
                    }
                </script> --}}

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
            </div>
        </div>
    </div>

@endsection
