@extends('main.template')

@section('head-section')
    <style>
        #previewCover {
            object-fit: cover;
            height: 200px;
            width: 100%;
        }
    </style>

    <style>
        /* Custom CSS for DataTables elements */


        /* Style for the search box container */
        .dt-search {
            display: flex;
            /* Arrange elements in a row */
            align-items: center;
            /* Vertically align label and input */
            margin-bottom: 10px;
            /* Add some space below the search box */
        }

        /* Style for the search label */
        .dt-search label {
            margin-right: 5px;
            /* Add spacing between label and input */
            font-weight: bold;
            /* Make label text bolder (optional) */
        }

        /* Style for the search input field */
        .dt-search input {
            border: 1px solid #ccc;
            /* Add a border */
            border-radius: 3px;
            /* Add rounded corners */
            padding: 5px 10px;
            /* Add padding for better user experience */
            font-size: 14px;
            /* Set font size */
        }

        /* Style for the search input field on hover */
        .dt-search input:hover {
            border-color: #999;
            /* Change border color on hover (optional) */
        }

        /* Style for Excel download button */
        .dt-button {
            /* Add your custom styles here */
            display: inline-block;
            padding: 8px 16px;
            border: 1px solid #007bff;
            /* Change color as needed */
            color: #007bff;
            /* Change color as needed */
            text-align: center;
            margin-bottom: 20px;
            text-decoration: none;
            font-size: 15px;
            border-radius: 20px;
            /* Adjust as needed */
            transition: all 0.3s ease;
            background-color: transparent;
        }

        .dt-button.buttons-excel.buttons-html5:hover {
            background-color: #007bff;
            /* Change color as needed */
            color: #fff;
            /* Change color as needed */
        }

        /* Style for pagination buttons */
        .dt-paging.paging_full_numbers .dt-paging-button {
            /* Add your custom styles here */
            width: 30px;
            height: 30px;
            border-radius: 50%;
            /* Ensures rounded circles */
            background-color: #fff;
            /* Change color as needed */
            margin: 0 2px;
            /* Adjust spacing as needed */
            justify-content: center;
            align-items: center;
            cursor: pointer;
            border: 1px solid #007bff;
            /* Change color as needed */
            color: #007bff;
            /* Change color as needed */
            font-weight: bold;
        }

        .dt-paging {
            margin: 20px;
        }


        .dt-paging.paging_full_numbers .dt-paging-button.current {
            background-color: #007bff;
            /* Change color as needed */
            color: #fff;
            /* Change color as needed */
        }

        .dt-paging.paging_full_numbers .dt-paging-button:hover {
            background-color: #007bff;
            /* Change color as needed */
            color: #fff;
            /* Change color as needed */
        }
    </style>
    {{-- Charts.css --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css">
    <script src="{{ asset('atlantis/examples') }}/assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- CSS Styling -->
    <style>
        .filter-group {
            margin-right: 1rem;
        }

        .form-control-sm {
            padding: 0.3rem 0.6rem;
            font-size: 0.875rem;
        }
    </style>
@endsection


@section('script')
    {{-- @include('main.home.script_student') --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var takenCount = {{ $takenCount }};
            var notTakenCount = {{ $notTakenCount }};

            // Handle null or undefined values
            takenCount = takenCount || 0;
            notTakenCount = notTakenCount || 0;

            var data = google.visualization.arrayToDataTable([
                ['Task', 'Hours per Day'],
                ['Sudah Mengerjakan', takenCount],
                ['Belum Mengerjakan', notTakenCount]
            ]);

            var options = {
                colors: ['#67C587', '#207F3F'],
                legend: {
                    position: 'right' // Set legend position to right
                },
                pieSliceText: 'percentage',
                pieSliceTextStyle: {
                    color: 'white'
                }
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
        }
    </script>

    {{-- canvasJS for Pie Chart --}}
    <script type="text/javascript">
        window.onload = function() {
            // Data from controller
            var groupedByDepartment = @json($mainPieChartData);

            // Convert data to format needed for CanvasJS
            var pieDataPoints = [];
            for (var key in groupedByDepartment) {
                pieDataPoints.push({
                    y: groupedByDepartment[key],
                    indexLabel: key,
                    color: getRandomColor()
                });
            }

            // Function to get a random color
            function getRandomColor() {
                var letters = '0123456789ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            }

            // Script untuk Pie Chart
            var chart_pie = new CanvasJS.Chart("chartContainer_pieChart", {
                theme: "light3",
                // title: {
                //     text: "Percentage of Students in Departments"
                // },
                legend: {
                    horizontalAlign: "bottom",
                    verticalAlign: "center",
                    fontSize: 14
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "{y} Students - #percent %",
                    legendText: "{indexLabel}",
                    dataPoints: pieDataPoints
                }]
            });
            chart_pie.render();

            // Script untuk Stacked Bar
            var chart_stackedBar = new CanvasJS.Chart("chartContainer_stackedBar", {
                title: {
                    text: "Division of products Sold in Quarter."
                },
                toolTip: {
                    shared: true
                },
                axisY: {
                    title: "percent"
                },

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

    </script>
    {{-- Toastr --}}
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#basic-main-tables').DataTable({
                dom: '<"top"Bfrtip>', // Add buttons to the top and bottom, with buttons at the top
                buttons: [
                    'excel', 'pdf', 'csv' // Button for Excel export
                ],
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ], // Define options for number of rows per page
                pagingType: 'full_numbers' // Include pagination numbers
            });
            $('#leaderboard-table').DataTable({
                dom: '<"top"Bfrtip>', // Add buttons to the top and bottom, with buttons at the top
                buttons: [
                    'excel', 'pdf', 'csv' // Button for Excel export
                ],
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ], // Define options for number of rows per page
                pagingType: 'full_numbers' // Include pagination numbers
            });

            var action =
                '<td> <div class="form-button-action"> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

        });
    </script>
@endsection


@section('main')
    <script>
        window.onload = function() {
            // jQuery and everything else is loaded
            var el = document.getElementById('input-image');
            el.onchange = function() {
                var fileReader = new FileReader();
                fileReader.readAsDataURL(document.getElementById("input-image").files[0])
                fileReader.onload = function(oFREvent) {
                    document.getElementById("imgPreview").src = oFREvent.target.result;
                };
            }

            $(document).ready(function() {
                $.myfunction = function() {
                    $("#previewName").text($("#inputTitle").val());
                    var title = $.trim($("#inputTitle").val())
                    if (title == "") {
                        $("#previewName").text("Judul")
                    }
                };

                $("#inputTitle").keyup(function() {
                    $.myfunction();
                });

            });
        }
    </script>


    <div class="page-inner">
        <div class="main-content-container container-fluid px-4 mt-5">

            {{-- @include('blog.breadcumb') --}}


            <!-- Page Header -->
            <div class="page-header row no-gutters mb-4">
                <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                    <span class="text-uppercase page-subtitle">Dashboard</span>
                    <h3 class="page-title">General Report</h3>
                </div>
            </div>


            <!-- End Page Header -->
            <div class="row">
                {{-- Side Bar --}}

                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Card Header with Title and Filters -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <!-- Title on the left -->
                                        <h5 class="card-title mb-0">Overview</h5>

                                        <!-- Filters on the right -->
                                        <div class="d-flex align-items-center flex-wrap" style="max-width: 100%;">
                                            <form action="{{ url()->current() }}" method="GET" id="filterForm"
                                                class="d-flex flex-wrap align-items-center">
                                                <input type="hidden" name="learn_status" id="learnStatusInput"
                                                    value="all">

                                                <!-- Dropdown Filters -->
                                                <div class="filter-group mx-1">
                                                    <select class="form-control form-control-sm" name="location"
                                                        id="exampleFormControlSelect1" onchange="submitForm()">
                                                        <option value="all">All Business Units</option>
                                                        @foreach ($locations as $location)
                                                            <option value="{{ $location->id }}"
                                                                {{ request('location') == $location->id ? 'selected' : '' }}>
                                                                {{ $location->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="filter-group mx-1">
                                                    <select class="form-control form-control-sm" name="class"
                                                        id="exampleFormControlSelect2" onchange="submitForm()">
                                                        <option value="all">Semua Kelas</option>
                                                        @foreach ($classes as $class)
                                                            <option value="{{ $class->id }}"
                                                                {{ request('class') == $class->id ? 'selected' : '' }}>
                                                                {{ $class->course_title }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="filter-group mx-1">
                                                    <select class="form-control form-control-sm" name="department"
                                                        id="exampleFormControlSelect2" onchange="submitForm()">
                                                        <option value="all">All Departments</option>
                                                        @foreach ($departmentsForFilter as $department)
                                                            <option value="{{ $department->id }}"
                                                                {{ request('department') == $department->id ? 'selected' : '' }}>
                                                                {{ $department->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="filter-group mx-1 d-none">
                                                    <select class="form-control form-control-sm" name="month"
                                                        id="exampleFormControlSelect3" onchange="submitForm()">
                                                        <option value="all">All Months</option>
                                                        @foreach (range(1, 12) as $month)
                                                            <option value="{{ $month }}"
                                                                {{ request('month') == $month ? 'selected' : '' }}>
                                                                {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="filter-group mx-1">
                                                    <select class="form-control form-control-sm" id="learnStatusSelect"
                                                        onchange="updateLearnStatus()">
                                                        <option value="all"
                                                            {{ request('learn_status') == 'all' ? 'selected' : '' }}>All
                                                            Status</option>
                                                        <option value="finished"
                                                            {{ request('learn_status') == 'finished' ? 'selected' : '' }}>
                                                            Finished</option>
                                                        <option value="not_finished"
                                                            {{ request('learn_status') == 'not_finished' ? 'selected' : '' }}>
                                                            Not Finished</option>
                                                    </select>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Chart and Button Section -->
                                    <div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel"
                                        aria-labelledby="pills-home-tab-nobd">
                                        <div style="position: relative; height: 100%;">
                                            <div class="d-flex flex-column">
                                                <div style="text-align: center; flex-grow: 1; position: relative;">
                                                    <div id="chartContainer_pieChart"
                                                        @if (count($mainPieChartData) == 0) style="height: 19px; width: auto;"
                                                        @else
                                                            style="height: 288px; width: auto;" @endif>
                                                    </div>
                                                    @if (count($mainPieChartData) == 0)
                                                        <p style="margin-top: 20px; font-size: 1.2em;">No Data Available</p>
                                                    @endif
                                                    <!-- Button placed at the bottom left, inline with content -->
                                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                                        onclick="openPieDetailsPage()"
                                                        style="position: absolute; bottom: 10px; left: 10px;">See More
                                                        Details</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        function submitForm() {
                                            document.getElementById('filterForm').submit();
                                        }

                                        function updateLearnStatus() {
                                            const selectElement = document.getElementById('learnStatusSelect');
                                            const hiddenInputElement = document.getElementById('learnStatusInput');
                                            hiddenInputElement.value = selectElement.value;
                                            submitForm();
                                        }

                                        function openPieDetailsPage() {
                                            const form = document.createElement('form');
                                            form.method = 'GET';
                                            form.action = '{{ url('/visualization/main-pie-chart-details') }}'; // Replace with the URL for the new page

                                            // Create hidden inputs with the current form values
                                            const inputs = [{
                                                    name: 'learn_status',
                                                    value: document.getElementById('learnStatusSelect').value
                                                },
                                                {
                                                    name: 'location',
                                                    value: document.getElementById('exampleFormControlSelect1').value
                                                },
                                                {
                                                    name: 'department',
                                                    value: document.getElementById('exampleFormControlSelect2').value
                                                },
                                                {
                                                    name: 'month',
                                                    value: document.getElementById('exampleFormControlSelect3').value
                                                }
                                            ];

                                            inputs.forEach(input => {
                                                const hiddenInput = document.createElement('input');
                                                hiddenInput.type = 'hidden';
                                                hiddenInput.name = input.name;
                                                hiddenInput.value = input.value;
                                                form.appendChild(hiddenInput);
                                            });

                                            document.body.appendChild(form);
                                            form.submit();
                                        }
                                    </script>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Daftar Siswa
                                @if (Request::query('class') !== 'all')
                                    Terdaftar
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-main-tables"
                                    class="table table-bordered  @if (count($userFilters) < 1) d-none @endif">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Department</th>
                                            <th scope="col">Position</th>
                                            <th scope="col">Kelas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($userFilters as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data->name }}</td>
                                                <td>{{ $data->department_name }}</td>
                                                <td>{{ $data->position_name }}</td>
                                                <td>{{ $data->course_title }}</td>

                                            </tr>
                                        @empty
                                            <div class="alert alert-danger">
                                                Anda Belum Memiliki Kelas
                                            </div>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                @if ($lastPostTestDetail != null)
                    {{-- <div class="col-12">
                    <div class="card exam-title-card mt-5">
                        <div class="card-body">
                            <div class="exam-title-card-img-container mr-2">
                                <img  style="aspect-ratio: 16 / 9"
                                     src="{{ env('AWS_BASE_URL') . $lesson->course_cover_image }}"
                                     alt="Profile Image">
                            </div>
                            <div class="exam-title-card-content">
                                <h2 class="text-dark" style="font-size: 22px">{{$lastPostTestDetail->exam_title}}</h2>
                                <h2 class="card-title" style="font-size: 22px">{{$lesson->lesson_title}}</h2>
                                <p class="card-subtitle mb-2 text-muted">Subtitle</p>
                                <p class="student-count-exam-title-card">{{$studentCount}} Student</p>
                            </div>
                        </div>
                    </div>
                </div> --}}
                @else
                    @if (Request::query('class') !== 'all')
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                Belum Ada Post Test Pada Kelas Ini
                            </div>
                        </div>
                    @endif
                @endif


                @if ($lastPostTestDetail != null)
                    <div class="col-md-12 mb-5">
                        <div class="row">
                            <div class="col-md-6 col-12" style="display: inline-block; vertical-align: top;">
                                <div class="card" style="height: 100%;">
                                    <div class="card-header">
                                        <div class="card-title">Post Test Progress</div>
                                    </div>
                                    <div class="card-body pb-0">
                                        <!-- Create a canvas element to render the chart -->
                                        <!-- HTML container for the pie chart -->
                                        <div id="piechart" style="width: 100%; height: 500px;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12" style="display: inline-block; vertical-align: top;">
                                <div class="card" style="height: 100%;">
                                    <div class="card-header">
                                        <div class="card-title">Belum Mengerjakan Post Test</div>
                                    </div>
                                    <div class="card-body pb-0">
                                        <div class="table-responsive">
                                            <div class="table-responsive">
                                                <table id="not-taken-table"
                                                    class="table @if (count($summaryPrePost) < 1) d-none @endif">
                                                    <tbody>
                                                        @forelse ($studentsNotTakenPostTest as $data)
                                                            <tr>
                                                                <td style="overflow: hidden; white-space: nowrap;">
                                                                    <div style="display: flex; align-items: center;">
                                                                        <div
                                                                            style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; margin-right: 10px;">
                                                                            <img src="{{ env('AWS_BASE_URL') . $data->profile_url }}"
                                                                                alt="Profile Picture"
                                                                                style="width: 100%; height: 100%; object-fit: cover;"
                                                                                onerror="this.src='{{ asset('default/default_profile.png') }}'; this.alt='Default Profile Picture';">
                                                                        </div>
                                                                        {{ optional($data)->student_name }}
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            {{--                                                <!-- Handle the case where $summaryPrePost is empty --> --}}
                                                            {{--                                                <div class="alert alert-danger"> --}}
                                                            {{--                                                    Belum Ada Peserta Yang Mengerjakan Exam Ini --}}
                                                            {{--                                                </div> --}}
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                @endif



                @if (count($rankingCard) > 0)
                    <!-- Check if the ranking card is not empty -->
                    <div class="col-md-12">
                        <h1 class="text-dark">
                            <strong>Leaderboard Score</strong>
                        </h1>
                    </div>
                @endif

                <div class="col-md-12">
                    <div class="row justify-content-center">
                        @forelse ($rankingCard as $data)
                            <div class="col-md-4 mb-4"> <!-- Added mb-4 for margin bottom -->
                                <div class="card h-100"> <!-- Added h-100 class -->
                                    <div class="card-header" style="padding: 10px;">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <img style="width: 90px; height: 90px; margin-right: 10px;"
                                                src="{{ asset('icons/rank_' . $loop->index + 1 . '.svg') }}"
                                                alt="User Icon">
                                            <a href="#" style="text-decoration: none; color: black;">
                                                <div style="display: flex; align-items: center;">
                                                    <p style="font-size: 18px; margin: 0;">
                                                        <b>{{ optional($data)->student_name }}</b>
                                                    </p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body d-flex justify-content-center align-items-center">
                                        <div class="d-flex flex-column align-items-center" style="margin-top: 30px">
                                            <!-- Container untuk gambar dan scoring -->
                                            <img style="width: 100%; max-width: 130px; min-height: 130px; margin-bottom: 30px;  max-height: 130px"
                                                src="{{ env('AWS_BASE_URL') . $data->student_photo }}"
                                                alt="Alternative Image" class="avatar-img rounded-circle"
                                                onerror="this.onerror=null; this.src='{{ asset('default/default_profile.png') }}'; this.alt='Alternative Image';">
                                            <div id="scoring" class="d-flex align-items-center">
                                                <!-- Container untuk scoring -->
                                                <img style="width: 35%; height: auto; margin-right: 10px;"
                                                    src="{{ asset('icons/tropy.svg') }}" alt="Tropy Icon">
                                                <a href="#" style="text-decoration: none; color: black;">
                                                    <p style="font-size: 15px; margin: 0; white-space: nowrap;">
                                                        <b>{{ $data->total_score }} Point</b>
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <!-- Handle the case where $summaryPrePost is empty -->
                            {{-- <div class="alert alert-danger">
                                Belum Ada Peserta Yang Belum Mengerjakan Post Test Ini
                            </div> --}}
                        @endforelse
                    </div>
                </div>
                @if (Request::query('class') !== 'all' || Request::query('class')== null)
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="leaderboard-table"
                                class="table table-bordered  @if (count($summaryPrePost) < 1) d-none @endif">
                                <thead>
                                    <tr>
                                        <th style="background-color: #F8F8F8">
                                            <h3><b>Rank</b></h3>
                                        </th>
                                        <th style="background-color: #F8F8F8">
                                            <h3><b>Nama Siswa</b></h3>
                                        </th>
                                        <th style="background-color: #F8F8F8">
                                            <h3><b>Penyelesaian Materi</b></h3>
                                        </th>
                                        <th style="background-color: #F8F8F8">
                                            <h3><b>Pre-Test</b></h3>
                                        </th>
                                        <th style="background-color: #F8F8F8">
                                            <h3><b>Quiz</b></h3>
                                        </th>
                                        <th style="background-color: #F8F8F8">
                                            <h3><b>Post-Test</b></h3>
                                        </th>
                                        {{-- <th style="background-color: #F8F8F8"><h3><b>Total Score</b></h3></th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($summaryPrePost as $data)
                                        <tr>
                                            <td>
                                                {{ $data->rank }}
                                            </td>
                                            <td style="overflow: hidden; white-space: nowrap;">
                                                <div style="display: flex; align-items: center;">
                                                    <div
                                                        style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; margin-right: 10px;">
                                                        <img src="{{ env('AWS_BASE_URL') . $data->student_photo }}"
                                                            alt="Profile Picture"
                                                            style="width: 100%; height: 100%; object-fit: cover;"
                                                            onerror="this.src='{{ asset('default/default_profile.png') }}'; this.alt='Default Profile Picture';">
                                                    </div>
                                                    {{ optional($data)->student_name }}
                                                </div>
                                            </td>
                                            <td style="overflow: hidden; white-space: nowrap;">
                                                {{ optional($data)->percentage }}
                                            </td>
                                            <td style="overflow: hidden; white-space: nowrap;">
                                                {{ optional($data)->highest_pre_test_score }}
                                            </td>
                                            <td style="overflow: hidden; white-space: nowrap;">
                                                {{ optional($data)->highest_quiz_score }}
                                            </td>
                                            <td style="overflow: hidden; white-space: nowrap;">
                                                {{ optional($data)->highest_post_test_score }}
                                            </td>
                                            {{-- <td style="overflow: hidden; white-space: nowrap;">
                                        {{ optional($data)->total_score }}
                                    </td> --}}
                                        </tr>
                                    @empty
                                        <!-- Handle the case where $summaryPrePost is empty -->
                                        {{--                                        <div class="alert alert-danger"> --}}
                                        {{--                                            Belum Ada Peserta Yang Mengerjakan Exam Ini --}}
                                        {{--                                        </div> --}}
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
