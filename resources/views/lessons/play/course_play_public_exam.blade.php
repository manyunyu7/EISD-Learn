<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{$exam->title}}</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{asset('lesson_template/')}}/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{asset('lesson_template/')}}/css/simple-sidebar.css" rel="stylesheet">
    <link href="{{asset('lesson_template/')}}/css/custom.css" rel="stylesheet">

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


        /* CSS Loader */
        /* HTML: <div class="loader"></div> */
        .loader {
            width: 60px;
            height: 60px;
            display: flex;
            color: #FC3A51;
            --c: #0000 calc(100% - 20px), currentColor calc(100% - 19px) 98%, #0000;
            background: radial-gradient(farthest-side at left, var(--c)) right /50% 100%,
            radial-gradient(farthest-side at top, var(--c)) bottom/100% 50%;
            background-repeat: no-repeat;
            animation: l18-0 2s infinite linear .25s;
        }

        .loader::before {
            content: "";
            width: 50%;
            height: 50%;
            background: radial-gradient(farthest-side at bottom right, var(--c));
            animation: l18-1 .5s infinite linear;
        }

        @keyframes l18-0 {
            0%, 12.49% {
                transform: rotate(0deg)
            }
            12.5%, 37.49% {
                transform: rotate(90deg)
            }
            37.5%, 62.49% {
                transform: rotate(180deg)
            }
            62.5%, 87.49% {
                transform: rotate(270deg)
            }
            87.5%, 100% {
                transform: rotate(360deg)
            }
        }

        @keyframes l18-1 {
            0% {
                transform: perspective(150px) rotateY(0) rotate(0)
            }
            50% {
                transform: perspective(150px) rotateY(180deg) rotate(0)
            }
            80%, 100% {
                transform: perspective(150px) rotateY(180deg) rotate(90deg)
            }
        }

        .loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.8); /* semi-transparent white background */
            z-index: 9999; /* ensure the loader is on top of other content */
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

<div id="wrapper" class="toggled">

    <div class="container-fluid navbar-fixed-top large-nav-bar" style="background-color: #F5F7FA; padding: 10px 20px;">
        <div class="row">
            <div class="col-xs-1 back-button d-none">
                <a href="{{ url()->previous() }}" class="btn btn-link">
                    <img src="{{ asset('lesson_template/img/back_button.svg') }}" alt="Back"
                         style="width: 57px; height: 57px;">
                </a>
            </div>
            <div class="col-xs-10 text-center title-top">
                <h3>{{ $exam->title }}
                    @if($isExam)
                        (Exam)
                    @endif
                </h3>
            </div>
            <div class="col-xs-1 col-xs-0 text-right hamburger-button d-none">
                <div style="margin-top:6px; margin-right: 30px">
                    <a href="#menu-toggle" id="menu-toggle">
                        <img src="{{asset('lesson_template/img/')}}/hamburger_button.svg" alt="Toggle Menu"
                             style="width: 57px; height: 57px;">
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="loader-container" style="display: none">
        <div class="loader"></div>
    </div>

    <!-- Page Content -->
    <div id="page-content-wrapper">

        @if($isExam)
            @include('lessons.play.public_exam_section')
        @endif

    </div>


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
