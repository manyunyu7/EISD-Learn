@extends('main.template')
@section('script')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = google.visualization.arrayToDataTable([
        ['Task', 'Hours per Day'],
        ['Sudah Mengerjakan',     {{ $count_studentsTaken }}],
        ['Belum Mengerjakan',      {{ $count_studentsUntaken }}]
      ]);

      var options = {
        colors:['#67C587','#207F3F']
      };

      var chart = new google.visualization.PieChart(document.getElementById('piechart'));

      chart.draw(data, options);
    }
  </script>
@endsection
@section('main')

    <div class="container">
        @if (count($list_studentTaken) >= 3)
            @foreach ($class as $data)
            <div class="page-inner">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb bg-white">
                        <li class="breadcrumb-item"><a href={{url('/home')}}>Home</a></li>
                        <li class="breadcrumb-item active" ><a href={{url('/dashboard/mentor')}}>Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Course - {{ $data->lesson_title }}</li>
                    </ol>
                </nav>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content mt-2 mb-5" id="pills-without-border-tabContent">
                                    <div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel"
                                        aria-labelledby="pills-home-tab-nobd">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                <img style="aspect-ratio: 16 / 9"
                                                    src="{{ Storage::url('public/class/cover/') . $data->lesson_cover_img }}"
                                                    alt="Profile Image" class="avatar-img"
                                                    onerror="this.onerror=null; this.src='{{ url('/default/default_profile.png') }}'; this.alt='Alternative Image';">
                                            </div>
                                            <div>
                                                <div class="card-head-row card-tools-still-right">
                                                    <h3 style="color: black;"><b>{{ $data->section_title }}</b></h3>
                                                    <p class="card-category">{{ $data->lesson_category }}</p>
                                                </div>
                                            </div>
                                            <div class="ml-auto d-flex" style="margin-bottom: -190px; position: relative">
                                                <img style="width: 20px; height: auto;" src="{{ url('/icons/UserStudent_mentor.svg') }}" alt="User Icon">
                                                <a  style="text-decoration: none; color: black;">
                                                    <p style="font-size: 15px; margin-left: 15px; margin-top: 18px">
                                                        <b>{{ $totalStudents }}</b><span style="color: #8C94A3;"> students</span>
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3><strong>Post Test Progress</strong></h3>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="pills-without-border-tabContent">
                                    <div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel"
                                         aria-labelledby="pills-home-tab-nobd">
                                        <div class="">
                                            <div id="piechart" style="height: 300px; width: 100%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3><strong>Detail Not Finished</strong></h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" style="border-collapse: collapse;">
                                        <tbody>
                                            @foreach ($students_notTakenPostTest as $item)
                                            <tr style="height: 70px;">
                                                <td scope="row">
                                                    <div class="avatar-sm">
                                                        <img style="width: 125%; height: auto;"
                                                            src="{{ Storage::url('public/profile/') . $item->profile_url }}"
                                                            alt="Profile Image" class="avatar-img rounded-circle"
                                                            onerror="this.onerror=null; this.src='{{ url('/default/default_profile.png') }}'; this.alt='Alternative Image';">
                                                    </div>
                                                </td>
                                                <td style="width: 100%; font-size:17px">{{ $item->name }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if (($list_studentTaken->count() >= 3))
                <h4 class="page-title">Leaderboard Score</h4>
                <div class="col-md-12" >
                    <div class="row">
                        <div class="col-11" >
                            <button class="btn btn-custom"><strong>Download</strong></button>
                        </div>
                        <div class="col-1 ms-auto mt-4">
                            <a href="#"  id="seeAllLink" style="text-decoration: none; color:#8C94A3">See All</a>
                        </div>
                        <script>
                            document.getElementById("seeAllLink").addEventListener("click", function(event){
                                event.preventDefault(); // Prevent default link behavior

                                var tableContainer = document.getElementById("tableContainer");

                                // Toggle the display style between "block" and "none"
                                if(tableContainer.style.display === "none") {
                                    tableContainer.style.display = "block";
                                } else {
                                    tableContainer.style.display = "none";
                                }
                            });

                        </script>
                    </div>
                </div>
                <div class="row">
                    {{-- Card I --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header" style="padding: 10px;">
                                <div class="d-flex align-items-center justify-content-center">
                                    <img style="width: 12%; height: auto; margin-right: 10px;" src="{{ url('/icons/rank_1.svg') }}" alt="User Icon">
                                    <a href="#" style="text-decoration: none; color: black;">
                                        <p style="font-size: 18px; margin: 0;"><b>{{ $rank1->name }}</b></p>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <div class="d-flex flex-column align-items-center"> <!-- Container untuk gambar dan scoring -->
                                    <img style="width: 100%; max-width: 130px; height: auto;  max-height: 130px"
                                        src="{{ Storage::url('public/profile/') . $rank1->profile_url }}"
                                        alt="Profile Image" class="avatar-img rounded-circle"
                                        onerror="this.onerror=null; this.src='{{ url('/default/default_profile.png') }}'; this.alt='Alternative Image';">
                                    <div id="scoring" class="d-flex align-items-center"> <!-- Container untuk scoring -->
                                        <img style="width: 35%; height: auto; margin-right: 10px;" src="{{ url('/icons/tropy.svg') }}" alt="User Icon">
                                        <a href="#" style="text-decoration: none; color: black;">
                                            <p style="font-size: 18px; margin: 0; white-space: nowrap;"><b>{{ $rank1->current_score }}</b></p>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Card II --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header" style="padding: 10px;">
                                <div class="d-flex align-items-center justify-content-center">
                                    <img style="width: 12%; height: auto; margin-right: 10px;" src="{{ url('/icons/rank_2.svg') }}" alt="User Icon">
                                    <a href="#" style="text-decoration: none; color: black;">
                                        <p style="font-size: 18px; margin: 0;"><b>{{ $rank2->name }}</b></p>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <div class="d-flex flex-column align-items-center"> <!-- Container untuk gambar dan scoring -->
                                    <img style="width: 100%; max-width: 130px; height: auto;  max-height: 130px"
                                        src="{{ Storage::url('public/profile/') . $rank2->profile_url }}"
                                        alt="Profile Image" class="avatar-img rounded-circle"
                                        onerror="this.onerror=null; this.src='{{ url('/default/default_profile.png') }}'; this.alt='Alternative Image';">
                                    <div id="scoring" class="d-flex align-items-center"> <!-- Container untuk scoring -->
                                        <img style="width: 35%; height: auto; margin-right: 10px;" src="{{ url('/icons/tropy.svg') }}" alt="User Icon">
                                        <a href="#" style="text-decoration: none; color: black;">
                                            <p style="font-size: 18px; margin: 0; white-space: nowrap;"><b>{{ $rank2->current_score }}</b></p>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Card III --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header" style="padding: 10px;">
                                <div class="d-flex align-items-center justify-content-center">
                                    <img style="width: 12%; height: auto; margin-right: 10px;" src="{{ url('/icons/rank_3.svg') }}" alt="User Icon">
                                    <a href="#" style="text-decoration: none; color: black;">
                                        <p style="font-size: 18px; margin: 0;"><b>{{ $rank3->name }}</b></p>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <div class="d-flex flex-column align-items-center"> <!-- Container untuk gambar dan scoring -->
                                    <img style="width: 100%; max-width: 130px; height: auto;  max-height: 130px"
                                        src="{{ Storage::url('public/profile/') . $rank3->profile_url }}"
                                        alt="Profile Image" class="avatar-img rounded-circle"
                                        onerror="this.onerror=null; this.src='{{ url('/default/default_profile.png') }}'; this.alt='Alternative Image';">
                                    <div id="scoring" class="d-flex align-items-center"> <!-- Container untuk scoring -->
                                        <img style="width: 35%; height: auto; margin-right: 10px;" src="{{ url('/icons/tropy.svg') }}" alt="User Icon">
                                        <a href="#" style="text-decoration: none; color: black;">
                                            <p style="font-size: 18px; margin: 0; white-space: nowrap;"><b>{{ $rank3->current_score }}</b></p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <h3>
                                    Minimal ada 3 Student yang telah mengerjakan Post Test untuk ditampilkan dalam Leaderboard
                                </h3>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Lakukan Looping Untuk Table Ini! --}}
                <div class="table-responsive" id="tableContainer">
                    <table class="table" style="border-collapse: collapse;">
                        <thead style="background-color: #ebebeb;">
                            <tr class="text-center" style="font-size: 12px">
                                <th><h3><b>Rank</b></h3></th>
                                <th><h3><b></b></h3></th>
                                <th><h3><b>Name</b></h3></th>
                                <th><h3><b>Division</b></h3></th>
                                <th><h3><b>Quiz</b></h3></th>
                                <th><h3><b>Pre Test</b></h3></th>
                                <th><h3><b>Post Test</b></h3></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students_takePostTest as $item)
                                <?php
                                    // Cari data dari $list_studentTaken_preTest yang memiliki user_id yang sama dengan $item
                                    $quiz_students = DB::select("
                                                            SELECT
                                                                lsn.id AS lesson_id,
                                                                lsn.course_title AS lesson_title,
                                                                lsn.course_category AS lesson_category,
                                                                lsn.course_cover_image AS lesson_cover_img,
                                                                lsn.department_id AS lesson_dept_id,
                                                                lsn.position_id AS lesson_posit_id,
                                                                cs.quiz_session_id AS exam_session_id,
                                                                cs.section_title AS section_title,
                                                                cs.id AS section_id,
                                                                cs.created_at AS date_create,
                                                                es.exam_type AS exam_type,
                                                                exm.id AS exam_id
                                                            FROM
                                                                lessons lsn
                                                            LEFT JOIN
                                                                course_section cs ON lsn.id = cs.course_id
                                                            LEFT JOIN
                                                                exam_sessions es ON cs.quiz_session_id = es.id
                                                            LEFT JOIN
                                                                exams exm ON es.exam_id = exm.id
                                                            WHERE
                                                                lsn.id = $item->course_id
                                                                AND
                                                                es.exam_id = exm.id
                                                                AND
                                                                es.exam_type = 'Quiz'
                                                            ORDER BY
                                                                cs.created_at DESC
                                    ");
                                    $preTest_students = DB::select("
                                                        SELECT
                                                            lsn.id AS lesson_id,
                                                            lsn.course_title AS lesson_title,
                                                            lsn.course_category AS lesson_category,
                                                            lsn.course_cover_image AS lesson_cover_img,
                                                            lsn.department_id AS lesson_dept_id,
                                                            lsn.position_id AS lesson_posit_id,
                                                            cs.quiz_session_id AS exam_session_id,
                                                            cs.section_title AS section_title,
                                                            cs.id AS section_id,
                                                            cs.created_at AS date_create,
                                                            es.exam_type AS exam_type,
                                                            exm.id AS exam_id
                                                        FROM
                                                            lessons lsn
                                                        LEFT JOIN
                                                            course_section cs ON lsn.id = cs.course_id
                                                        LEFT JOIN
                                                            exam_sessions es ON cs.quiz_session_id = es.id
                                                        LEFT JOIN
                                                            exams exm ON es.exam_id = exm.id
                                                        WHERE
                                                            lsn.id = $item->course_id
                                                            AND
                                                            es.exam_id = exm.id
                                                            AND
                                                            es.exam_type = 'Pre Test'
                                                        ORDER BY
                                                            cs.created_at DESC
                                    ");


                                    // Kondisional Segmentasi QUIZ
                                    if (!empty($quiz_students)){
                                        $quiz_SessionId = $quiz_students[0]->exam_session_id;
                                        $quiz_SectionId = $quiz_students[0]->section_id;

                                        $students_takeQuiz = DB::select("
                                                            SELECT
                                                                u.name,
                                                                u.id,
                                                                u.profile_url,
                                                                u.department,
                                                                MAX(et.current_score) AS highest_score,
                                                                es.exam_type AS exam_type,
                                                                et.course_section_flag AS course_section_id,
                                                                et.course_flag AS course_id
                                                            FROM
                                                                exam_takers et
                                                            LEFT JOIN
                                                                course_section cs ON et.session_id = cs.quiz_session_id
                                                            LEFT JOIN
                                                                exam_sessions es ON et.session_id = es.id
                                                            LEFT JOIN
                                                                users u ON et.user_id = u.id
                                                            WHERE
                                                                et.user_id = $item->id
                                                                AND et.course_flag = $item->course_id
                                                                AND et.session_id = $quiz_SessionId
                                                                AND et.course_section_flag = $quiz_SectionId
                                                                AND es.exam_type = 'Quiz'
                                                            GROUP BY
                                                                et.user_id, et.course_flag
                                                            ORDER BY
                                                                highest_score ASC
                                        ");
                                    }

                                    // Kondisional Segmentasi PRE TEST
                                    if (!empty($preTest_students)){
                                        $preTest_SessionId = $preTest_students[0]->exam_session_id;
                                        $preTest_SectionId = $preTest_students[0]->section_id;


                                        $students_takePreTest = DB::select("
                                                                SELECT
                                                                    u.name,
                                                                    u.id,
                                                                    u.profile_url,
                                                                    u.department,
                                                                    MAX(et.current_score) AS highest_score,
                                                                    es.exam_type AS exam_type,
                                                                    et.course_section_flag AS course_section_id,
                                                                    et.course_flag AS course_id
                                                                FROM
                                                                    exam_takers et
                                                                LEFT JOIN
                                                                    course_section cs ON et.session_id = cs.quiz_session_id
                                                                LEFT JOIN
                                                                    exam_sessions es ON et.session_id = es.id
                                                                LEFT JOIN
                                                                    users u ON et.user_id = u.id
                                                                WHERE
                                                                    et.user_id = $item->id
                                                                    AND et.course_flag = $item->course_id
                                                                    AND et.session_id = $preTest_SessionId
                                                                    AND et.course_section_flag = $preTest_SectionId
                                                                    AND es.exam_type = 'Pre Test'
                                                                GROUP BY
                                                                    et.user_id, et.course_flag
                                                                ORDER BY
                                                                    highest_score ASC
                                        ");
                                    }

                                ?>
                                <tr class="text-center">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="avatar-sm">
                                            <img style="width: 125%; height: auto;"
                                                src="{{ Storage::url('public/profile/') . $item->profile_url }}"
                                                alt="Profile Image" class="avatar-img rounded-circle"
                                                onerror="this.onerror=null; this.src='{{ url('/default/default_profile.png') }}'; this.alt='Alternative Image';">
                                        </div>
                                    </td>
                                    @if (!empty($students_takePostTest))
                                        <td>{{ $item->name }}</td>
                                    @else
                                        <td>-</td>
                                    @endif
                                    <td>Digital Management</td>
                                    {{-- <td>{{ count($students_takeQuiz) }}</td> --}}
                                    @if (!empty($students_takeQuiz))
                                        <td>{{ $students_takeQuiz[0]->highest_score }}</td>
                                    @else
                                        <td>-</td>
                                    @endif

                                    @if (!empty($students_takePreTest))
                                        <td>{{ $students_takePreTest[0]->highest_score }}</td>
                                    @else
                                        <td>-</td>
                                    @endif

                                    @if (!empty($students_takePostTest))
                                        <td>{{ $item->highest_score }}</td>
                                    @else
                                        <td>-</td>
                                    @endif
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="container-fluid">
                <div class="container mt-5">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <script>
                                        $(function () { //ready
                                            toastr.error('{{ session('
                                                    $error ') }}', '{{ $error }}');
                                        });

                                    </script>
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                </div>
            </div>
            @endforeach
        @else
            <div class="col-md-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <h4>Peserta Yang Menyelesaikan Post Test Masih Kurang Dari 3!</h4>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>


    @if (session()->has('success'))
        <script>
            toastr.success('{{ session('
                success ') }}', '{{ session('success') }}');

        </script>
    @elseif(session()-> has('error'))
        <script>
            toastr.error('{{ session('
                error ') }}', '{{ session('
                error ') }}');

        </script>

    @endif

@endsection
