@extends('main.template')

@section('head-section')
    <style>
        #previewCover {
            object-fit: cover;
            height: 200px;
            width: 100%;
        }
    </style>
    {{-- Charts.css --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css">

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

                                                <div class="filter-group mx-1">
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
                            <div class="card-title">Daftar Siswa</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables"
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

            </div>
        </div>
    </div>
@endsection
