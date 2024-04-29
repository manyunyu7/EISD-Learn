@extends('main.template')

@section('head-section')
    <!-- Datatables -->
    <!-- DataTables CSS -->
    {{--    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.min.css">--}}
    <!-- DataTables Buttons CSS -->
    {{--    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">--}}
    {{--    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>--}}


    <style>
        .exam-title-card {
            margin: 0 auto; /* Center the card horizontally */
            position: relative; /* Enable positioning of absolute elements */
        }

        .exam-title-card-img-container {
            width: 20%; /* 2/10 of screen width */
            display: inline-block;
            vertical-align: middle;
        }

        .exam-title-card-img-container img {
            border-radius: 50%;
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto; /* Center the image inside the container */
        }

        .exam-title-card-content {
            width: 78%; /* Adjust the width as needed */
            display: inline-block;
            vertical-align: middle;
        }

        .student-count-exam-title-card {
            position: absolute;
            margin-right: 20px;
            bottom: 5px; /* Adjust the distance from the bottom */
            right: 10px; /* Adjust the distance from the right */
            color: #888; /* Adjust the color as needed */
        }
    </style>

    <style>
        /* Custom CSS for DataTables elements */


        /* Style for the search box container */
        .dt-search {
            display: flex; /* Arrange elements in a row */
            align-items: center; /* Vertically align label and input */
            margin-bottom: 10px; /* Add some space below the search box */
        }

        /* Style for the search label */
        .dt-search label {
            margin-right: 5px; /* Add spacing between label and input */
            font-weight: bold; /* Make label text bolder (optional) */
        }

        /* Style for the search input field */
        .dt-search input {
            border: 1px solid #ccc; /* Add a border */
            border-radius: 3px; /* Add rounded corners */
            padding: 5px 10px; /* Add padding for better user experience */
            font-size: 14px; /* Set font size */
        }

        /* Style for the search input field on hover */
        .dt-search input:hover {
            border-color: #999; /* Change border color on hover (optional) */
        }

        /* Style for Excel download button */
        .dt-button {
            /* Add your custom styles here */
            display: inline-block;
            padding: 8px 16px;
            border: 1px solid #007bff; /* Change color as needed */
            color: #007bff; /* Change color as needed */
            text-align: center;
            margin-bottom: 20px;
            text-decoration: none;
            font-size: 15px;
            border-radius: 20px; /* Adjust as needed */
            transition: all 0.3s ease;
            background-color: transparent;
        }

        .dt-button.buttons-excel.buttons-html5:hover {
            background-color: #007bff; /* Change color as needed */
            color: #fff; /* Change color as needed */
        }

        /* Style for pagination buttons */
        .dt-paging.paging_full_numbers .dt-paging-button {
            /* Add your custom styles here */
            width: 30px;
            height: 30px;
            border-radius: 50%; /* Ensures rounded circles */
            background-color: #fff; /* Change color as needed */
            margin: 0 2px; /* Adjust spacing as needed */
            justify-content: center;
            align-items: center;
            cursor: pointer;
            border: 1px solid #007bff; /* Change color as needed */
            color: #007bff; /* Change color as needed */
            font-weight: bold;
        }

        .dt-paging {
            margin: 20px;
        }


        .dt-paging.paging_full_numbers .dt-paging-button.current {
            background-color: #007bff; /* Change color as needed */
            color: #fff; /* Change color as needed */
        }

        .dt-paging.paging_full_numbers .dt-paging-button:hover {
            background-color: #007bff; /* Change color as needed */
            color: #fff; /* Change color as needed */
        }
    </style>
@endsection

@section('script')

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages': ['corechart']});
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


    <script>
        $(document).on('click', '.button', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            swal({
                    title: "Are you sure!",
                    type: "error",
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes!",
                    showCancelButton: true,
                },
                function () {
                    $.ajax({
                        type: "POST",
                        url: "{{url('/destroy')}}",
                        data: {id: id},
                        success: function (data) {
                            //
                        }
                    });
                });
        });

    </script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#basic-datatables').DataTable({
                dom: '<"top"Bfrtip>', // Add buttons to the top and bottom, with buttons at the top
                buttons: [
                    'excel', 'pdf', 'csv' // Button for Excel export
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]], // Define options for number of rows per page
                pagingType: 'full_numbers' // Include pagination numbers
            });

            $('#not-taken-table').DataTable({
                dom: '<"top"frtip>', // Add buttons to the top and bottom, with buttons at the top
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]], // Define options for number of rows per page
                pagingType: 'full_numbers', // Include pagination numbers
                pageLength: 5 // Display 5 rows per page

            });

        });
    </script>

    {{-- Toastr --}}
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        //message with toastr
        @if(session()-> has('success'))
        toastr.success('{{ session('success') }}', 'BERHASIL!');
        @elseif(session()-> has('error'))
        toastr.error('{{ session('error') }}', 'GAGAL!');
        @endif
    </script>

@endsection

@section('main')

    <div class="page-inner" style="background-color: #ffffff !important;">

        <div class="col-md-12" style="margin-top: 88px">
            {{-- BREADCRUMB --}}
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href={{url('/')}}>Home</a></li>
                    <li class="breadcrumb-item"><a href={{url('/lesson')}}>Kelas</a></li>
                    <li class="breadcrumb-item"><a
                            href={{url("/lesson/manage_v2/".$lesson->id."/dashboard")}}>Dashboard</a></li>
                </ol>
            </nav>
        </div>
        <div class="container-fluid">
            <div class="row">

                @if($lastPostTestDetail!=null)
                    <div class="col-12">
                        <div class="card exam-title-card mt-5">
                            <div class="card-body">
                                <div class="exam-title-card-img-container mr-2">
                                    <img style="width: 175px; height: 175px; object-fit: cover"
                                         src="{{ Storage::url('public/class/cover/') . $lesson->course_cover_image }}"
                                         alt="Profile Image">
                                </div>
                                <div class="exam-title-card-content">
                                    <h2 class="text-dark"
                                        style="font-size: 22px">{{$lastPostTestDetail->exam_title}}</h2>
                                    <h2 class="card-title" style="font-size: 22px">{{$lesson->lesson_title}}</h2>
                                    <p class="card-subtitle mb-2 text-muted">Subtitle</p>
                                    <p class="student-count-exam-title-card">{{$studentCount}} Student</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-12">
                        <div class="alert alert-danger" role="alert">
                            Belum Ada Post Test Pada Kelas Ini
                        </div>
                    </div>
                @endif


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
                                    <div class="card-title">Detail Not Finished</div>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="table-responsive">
                                        <div class="table-responsive">
                                            <table id="not-taken-table"
                                                   class="table table-bordered  @if (count($summaryPrePost) < 1) d-none @endif">
                                                <thead>
                                                <tr>
                                                    <th><h3><b>Nama Siswa</b></h3></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse ($studentsNotTakenPostTest as $data)
                                                    <tr>
                                                        <td style="overflow: hidden; white-space: nowrap;">
                                                            <div style="display: flex; align-items: center;">
                                                                <div
                                                                    style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; margin-right: 10px;">
                                                                    <img
                                                                        src="{{asset('storage/profile/' . $data->profile_url)}}"
                                                                        alt="Profile Picture"
                                                                        style="width: 100%; height: 100%; object-fit: cover;"
                                                                        onerror="this.src='{{url("/default/default_profile.png")}}'; this.alt='Default Profile Picture';">
                                                                </div>
                                                                {{ optional($data)->student_name }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    {{--                                                <!-- Handle the case where $summaryPrePost is empty -->--}}
                                                    {{--                                                <div class="alert alert-danger">--}}
                                                    {{--                                                    Belum Ada Peserta Yang Mengerjakan Exam Ini--}}
                                                    {{--                                                </div>--}}
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


                <div class="col-md-12">
                    <h1 class="text-dark">
                        <strong>Leaderboard Score</strong>
                    </h1>
                </div>

                <div class="col-md-12">
                    <div class="row justify-content-center">
                        @forelse ($rankingCard as $data)
                            <div class="col-md-4 mb-4"> <!-- Added mb-4 for margin bottom -->
                                <div class="card h-100"> <!-- Added h-100 class -->
                                    <div class="card-header" style="padding: 10px;">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <img style="width: 12%; height: 70px; margin-right: 10px;"
                                                 src="http://0.0.0.0:5253/icons/rank_{{ $loop->index + 1 }}.svg"
                                                 alt="User Icon">
                                            <a href="#" style="text-decoration: none; color: black;">
                                                <div style="display: flex; align-items: center;">
                                                    <p style="font-size: 18px; margin: 0;">
                                                        <b>{{ optional($data)->student_name }}</b></p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body d-flex justify-content-center align-items-center">
                                        <div class="d-flex flex-column align-items-center" style="margin-top: 30px">
                                            <!-- Container untuk gambar dan scoring -->
                                            <img
                                                style="width: 100%; max-width: 130px; min-height: 130px; margin-bottom: 30px;  max-height: 130px"
                                                src="{{asset('storage/profile/' . $data->student_photo)}}"
                                                alt="Alternative Image" class="avatar-img rounded-circle"

                                                onerror="this.onerror=null; this.src='http://0.0.0.0:5253/default/default_profile.png';
                                 this.alt='Alternative Image';">
                                            <div id="scoring" class="d-flex align-items-center">
                                                <!-- Container untuk scoring -->
                                                <img style="width: 35%; height: auto; margin-right: 10px;"
                                                     src="http://0.0.0.0:5253/icons/tropy.svg" alt="User Icon">
                                                <a href="#" style="text-decoration: none; color: black;">
                                                    <p style="font-size: 15px; margin: 0; white-space: nowrap;">
                                                        <b>{{$data->total_score}} Point</b></p>
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

                <div class="col-md-12">

                    <div class="table-responsive">
                        <table id="basic-datatables"
                               class="table table-bordered  @if (count($summaryPrePost) < 1) d-none @endif">
                            <thead>
                            <tr>
                                <th style="background-color: #F8F8F8"><h3><b>Rank</b></h3></th>
                                <th style="background-color: #F8F8F8"><h3><b>Nama Siswa</b></h3></th>
                                <th style="background-color: #F8F8F8"><h3><b>Percentage</b></h3></th>
                                <th style="background-color: #F8F8F8"><h3><b>Pre-Test</b></h3></th>
                                <th style="background-color: #F8F8F8"><h3><b>Post-Test</b></h3></th>
                                <th style="background-color: #F8F8F8"><h3><b>Total Score</b></h3></th>
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
                                                <img src="{{asset('storage/profile/' . $data->student_photo)}}"
                                                     alt="Profile Picture"
                                                     style="width: 100%; height: 100%; object-fit: cover;"
                                                     onerror="this.src='{{url("/default/default_profile.png")}}'; this.alt='Default Profile Picture';">
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
                                        {{ optional($data)->highest_post_test_score }}
                                    </td>
                                    <td style="overflow: hidden; white-space: nowrap;">
                                        {{ optional($data)->total_score }}
                                    </td>
                                </tr>
                            @empty
                                <!-- Handle the case where $summaryPrePost is empty -->
                                {{--                                        <div class="alert alert-danger">--}}
                                {{--                                            Belum Ada Peserta Yang Mengerjakan Exam Ini--}}
                                {{--                                        </div>--}}
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
