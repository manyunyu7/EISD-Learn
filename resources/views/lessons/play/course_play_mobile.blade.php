<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{$lesson->course_title}}</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{asset('lesson_template/')}}/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{asset('lesson_template/')}}/css/simple-sidebar.css" rel="stylesheet">
    <link href="{{asset('lesson_template/')}}/css/custom.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>

        .d-none {
            display: none;
        }

        /* Floating timer container */
        .timer-container {
            position: fixed;
            bottom: 10px; /* Adjust the top position as needed */
            right: 10px; /* Adjust the right position as needed */
            background-color: white; /* You can adjust the background color as needed */
            z-index: 9999;
            padding: 10px;
            border: 1px solid #ccc; /* Add a border for style */
            border-radius: 5px;
        }

        /* Style for the timer */
        #timer {
            font-size: 48px;
            font-weight: bold;
            color: black; /* You can adjust the text color as needed */
        }

    </style>

</head>

<body>
<!-- Timer -->
@if($isExam)
    <div id="floating-timer" class="timer-container">
        <div id="timer" class="timer">00:00:00</div>
    </div>
@endif

<div id="wrapper">

    <div class="small-hamburger" style="margin-top:6px; margin-right: 30px; margin-left: 20px">
        <a href="#menu-toggle" id="menu-toggle-small">
            <img src="{{asset('lesson_template/img/')}}/hamburger_button.svg" alt="Toggle Menu"
                 style="width: 57px; height: 57px;">
        </a>
    </div>

    <div class="container-fluid navbar-fixed-top large-nav-bar" style="background-color: #F5F7FA; padding: 10px 20px;">
        <div class="row">
            <div class="col-xs-1 back-button">
                <a href="{{ url()->previous() }}" class="btn btn-link">
                    <img src="{{ asset('lesson_template/img/back_button.svg') }}" alt="Back"
                         style="width: 57px; height: 57px;">
                </a>
            </div>
            <div class="col-xs-10 text-center title-top">
                <h3>{{ $lesson->course_title }}
                    @if($isExam)
                        (Exam)
                    @endif
                </h3>
            </div>
            <div class="col-xs-1 col-xs-0 text-right hamburger-button">
                <div style="margin-top:6px; margin-right: 30px">
                    <a href="#menu-toggle" id="menu-toggle">
                        <img src="{{asset('lesson_template/img/')}}/hamburger_button.svg" alt="Toggle Menu"
                             style="width: 57px; height: 57px;">
                    </a>
                </div>
            </div>
        </div>
    </div>


    <!-- Page Content -->
    <div id="page-content-wrapper">


        @if($isExam)
            @include('lessons.play.student_exam_section_mobile')
        @endif

        @if(!$isExam)
            <div class="container-fluid">
                <div class="main-content-container container-fluid px-4 mt-5">

                    {{-- @include('blog.breadcumb') --}}
                    @forelse ($section_spec as $index => $sectionSpec)
                        <!-- Page Header -->
                        <div class="page-header row no-gutters mb-4">
                            <div class="col-12 col-sm-12 text-center text-sm-left mb-0">
                                <h2 class="text-uppercase">Kelas {{ $lesson->course_title }} </h2>
                                <h3 class="page-title">Materi Ke : {{ $sectionSpec->section_order }}</h3>
                                <h4 class="page-title">{{ $sectionSpec->section_title }}</h4>
                            </div>
                        </div>


                        @if($sectionDetail->embedded_file!="")
                            <div class="container-fluid" style="margin-left: -20px; margin-right: -20px">
                                <div style="width: 100%;>
                                    {!! $sectionDetail->embedded_file !!}
                                </div>
                            </div>
                        @endif

                        @if($sectionDetail->embedded_file=="" || $sectionDetail->embedded_file==null)
                            <div class="container-fluid">
                                @if(Str::contains(Storage::url('public/class/content/' . $sectionSpec->lesson_id . '/' . $sectionSpec->section_video),'pdf'))

                                    <iframe id="pdfIframe"
                                            src="{{ url('/') }}/library/viewerjs/src/#{{ Storage::url('public/class/content/' . $sectionSpec->lesson_id . '/' . $sectionSpec->section_video) }}#page=1"
                                            style="text-align:center;" width="100%" height="550" allowfullscreen=""
                                            webkitallowfullscreen=""></iframe>
                                    <!-- Add this single <script> tag to the body of your HTML document -->

                                    <script>
                                        // Listen for a message from the iframe
                                        window.addEventListener('message', function (event) {
                                            if (event.data === 'iframeLoaded') {
                                                startTracking();
                                            }
                                        });

                                        function startTracking() {
                                            console.log('Tracking started.');

                                            function getCurrentPage() {
                                                var iframe = document.getElementById('pdfIframe');
                                                var currentPage = iframe.contentWindow.document.querySelector('.toolbarField.pageNumber').value;
                                                return parseInt(currentPage, 10);
                                            }

                                            function getTotalPages() {
                                                var iframe = document.getElementById('pdfIframe');
                                                var totalPages = iframe.contentWindow.document.querySelector('.toolbarLabel').textContent;
                                                var match = totalPages.match(/of (\d+)/);
                                                if (match && match[1]) {
                                                    return parseInt(match[1], 10);
                                                }
                                                return 0;
                                            }

                                            function calculatePercentageCompletion() {
                                                var currentPage = getCurrentPage();
                                                var totalPages = getTotalPages();

                                                if (totalPages === 0) {
                                                    return 0;
                                                }

                                                return (currentPage / totalPages) * 100;
                                            }

                                            function updatePageInfo() {
                                                var currentPage = getCurrentPage();
                                                var totalPages = getTotalPages();
                                                var percentageCompletion = calculatePercentageCompletion();

                                                console.log('Current Page:', currentPage);
                                                console.log('Total Pages:', totalPages);
                                                console.log('Percentage Completion:', percentageCompletion + '%');
                                            }

                                            setInterval(updatePageInfo, 1000);
                                        }
                                    </script>
                                @else
                                    @php
                                        $videoFormats = ['mp4', 'webm', 'ogg']; // Add more video formats as needed
                                        $imageFormats = ['jpg', 'jpeg', 'png', 'gif']; // Add more image formats as needed
                                        $fileExtension = pathinfo($sectionSpec->section_video, PATHINFO_EXTENSION);
                                    @endphp

                                    @if (in_array($fileExtension, $videoFormats))
                                        <video crossorigin controls playsinline id="myVideo" autoplay="autoplay"
                                               width="100%"
                                               class="video-mask" disablePictureInPicture
                                               controlsList="nodownload">
                                            <source
                                                src="{{ Storage::url('public/class/content/' . $sectionSpec->lesson_id . '/' . $sectionSpec->section_video) }}">
                                        </video>
                                    @elseif (in_array($fileExtension, $imageFormats))
                                        <img
                                            src="{{ Storage::url('public/class/content/' . $sectionSpec->lesson_id . '/' . $sectionSpec->section_video) }}"
                                            alt="Image">
                                    @elseif (Str::contains($sectionSpec->section_video, "https://streamable"))
                                        <video crossorigin controls playsinline id="myVideo" autoplay="autoplay"
                                               width="100%"
                                               class="video-mask" disablePictureInPicture
                                               controlsList="nodownload">
                                            <source
                                                src="{{$sectionSpec->section_video}}">
                                        </video>
                                    @else
                                        <h1>Unsupported file format</h1>
                                    @endif
                                @endif
                            </div>
                        @endif

                        <script>
                            function nextCuy() {
                                var nextUrl = "{{ url('/') . "/mobile/course/$courseId/section/$next_section" }}";
                                window.location.href = nextUrl;
                                return;
                                var videoPlayer = document.getElementById("myVideo");
                                var nextUrl = "{{ url('/') . "/mobile/course/$courseId/section/$next_section" }}";
                                var progress = (videoPlayer.currentTime / videoPlayer.duration * 100);

                                if (progress >= 90) {
                                    window.location.href = nextUrl;
                                    return;
                                } else {

                                    // Prevent the default behavior of the button
                                    event.preventDefault();
                                    // Show a SweetAlert alert informing the user to complete the video first
                                    Swal.fire({
                                        title: "Video Progress",
                                        text: "Pengguna harus menyelesaikan video terlebih dahulu.",
                                        icon: "warning",
                                        confirmButtonText: "OK",
                                    });
                                }
                            }
                        </script>


                        <div class="card mt-5">
                            <img class="card-img-top" src="holder.js/100x180/" alt="">
                            <div class="card-body">


                                <h4 class="card-title">{{ $lesson->course_title }}</h4>

                                <p class="card-text">Materi Ke : {{ $sectionSpec->section_order }}</p>
                                {!! $sectionSpec->section_content !!}

                                <div class="d-flex justify-content-between mt-2 mb-4">
                                    @if ($prev_section != null)
                                        <a href="{{ url('/') . "/mobile/course/$courseId/section/$prev_section" }}"
                                           class="btn btn-primary hidden">Previous Lesson</a>
                                    @endif
                                    @if ($next_section != null)
                                        <button style="background-color: #39AA81" id="nextLessonButton"
                                                class="btn btn-primary" onclick="nextCuy();">
                                            Next Lesson
                                        </button>

                                        <!--<a href="{{ url('/') . "/mobile/course/$courseId/section/$next_section" }}" id="nextLessonButton"-->
                                        <!--    class="btn btn-primary ">Next-->
                                        <!--    Lesson</a>-->
                                    @endif

                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse

                </div>
            </div>
        @endif

    </div>
    <!-- /#page-content-wrapper -->

    <!-- Sidebar -->
    <div id="sidebar-wrapper" style="background-color: whitesmoke">
        <ul class="sidebar-nav">
            <div class="container content-container">
                <div class="" style="max-width: 560px">
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; padding: 10px;">
                        <div style="flex: 1; flex-shrink: 1;">
                            <div class="category-label-container"
                                 style=" background-color: red; color: white"
                            >Management Trainee
                            </div>
                        </div>
                        <div style="flex-shrink: 1;">
                            <img style="width: 12%; height: auto;"
                                 src="{{ url('/HomeIcons/Toga_MDLNTraining.svg') }}">
                            Modernland Training
                        </div>
                    </div>
                </div>

                <div
                    style="padding: 30px;  background-color: #F5F7FA; max-width: 560px; display: flex; justify-content: space-between; align-items: center;">
                    <!-- First Section -->
                    <div style="flex: 1; flex-shrink: 1;">
                        <h4 style="color: #FE1D04">Getting Started</h4>
                    </div>

                    <div style="flex-shrink: 1;">
                        <span>
                            <img
                                src="{{asset('lesson_template/img/')}}/section_folders_icon.svg" alt="Toggle Menu"/>
                            <p style="display: inline;">This is the middle section.</p>
                        </span>
                    </div>

                    <!-- Third Section -->
                    <div style="flex-shrink: 1; margin-left: 20px">
                        <span>
                            <img
                                src="{{asset('lesson_template/img/')}}/section_finished_icon.svg" alt="Toggle Menu"/>
                            <p style="display: inline;">This is the middle section.</p>
                        </span>
                    </div>
                </div>


                @forelse ($sections as $item)

                    <!--- Item Course Section Item -->
                    <div
                        style="padding: 20px;  background-color: #FFFFFF; max-width: 560px; display: flex; justify-content: space-between; align-items: center; border-bottom: 0.2px solid black;">
                        <!-- First Section -->
                        <div style="flex: 1; flex-shrink: 1;">
                            @if (isset($item) && isset($item->isTaken))
                                @php
                                    $isCurrent = $item->isCurrent ?? false; // Check if $item->isCurrent is set, if not, set it to false
                                @endphp

                                <a href="{{ url('/') . "/mobile/course/$item->lesson_id/section/$item->section_id" }}"
                                   style="text-decoration: none; color: inherit;">
                                    {{-- Check if the item is marked as taken --}}
                                    @if ($item->isTaken)
                                        {{-- Display a checked checkbox icon indicating completion --}}
                                        <img src="{{ asset('lesson_template/img/checkbox_checked_icon.svg') }}"
                                             alt="Completed"/>
                                        {{-- Check if it's the current section --}}
                                    @elseif ($item->section_id == $currentSectionId)
                                        {{-- Display a checked checkbox icon indicating completion --}}
                                        <img src="{{ asset('lesson_template/img/checkbox_checked_icon.svg') }}"
                                             alt="Completed"/>
                                    @else
                                        {{-- Display an empty checkbox icon indicating incomplete --}}
                                        <img src="{{ asset('lesson_template/img/checkbox_empty_icon.svg') }}"
                                             alt="Incomplete"/>
                                    @endif
                                    {{-- Display the section title --}}
                                    <span style="display: inline-block;">
                                        {{ $item->section_title }}
                                    </span>
                                </a>
                            @endif
                        </div>

                        <!-- Third Section -->
                        <div style="flex-shrink: 1; margin-left: 20px">
                                <span>
                                    @if($item->quiz_session_id!="-"&&$item->quiz_session_id!="")
                                        <img src="{{asset('lesson_template/img/')}}/timer_icon.svg"
                                             alt="Toggle Menu"/>
                                    @endif
                                    @if($item->time_limit_minute!="-"&&$item->time_limit_minute!="")
                                        <p style="display: inline;">{{$item->time_limit_minute}}m</p>
                                    @endif
                            </span>

                        </div>
                    </div>
                @empty
                    <li class="nav-item card p-1 bg-dark" style="margin-bottom: 6px !important">
                        {{-- <a href="{{ route('course.see_section', [$item->lesson_id, $item->section_id]) }}">
                    <span class="badge badge-success ">{{ $item->section_order }}</span><br>
                    </a> --}}
                        <p style="margin-bottom: 0px !important"> Belum Ada Materi di Kelas Ini</p>
                    </li>
                @endforelse
            </div>
        </ul>
    </div>
    <!-- /#sidebar-wrapper -->

</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="{{asset('lesson_template/')}}/js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{asset('lesson_template/')}}/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Menu Toggle Script -->
<script>
    $(document).ready(function () {
        // $("#wrapper").toggleClass("toggled");
    });
    $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    $("#menu-toggle-small").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
</script>

</body>

</html>
