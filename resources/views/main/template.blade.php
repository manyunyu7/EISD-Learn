<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport'/>
    <link rel="icon" href="{{asset('atlantis/examples')}}/assets/img/icon.ico" type="image/x-icon"/>


    <!-- Optional Javiracript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <link rel="stylesheet" href="{{asset('atlantis/examples')}}/assets/css/demo.css">

    <!--   Core JS Files   -->
    <script src="{{asset('atlantis/examples')}}/assets/js/core/jquery.3.2.1.min.js"></script>
    <script src="{{asset('atlantis/examples')}}/assets/js/core/popper.min.js"></script>
    <script src="{{asset('atlantis/examples')}}/assets/js/core/bootstrap.min.js"></script>

    {{-- Toastr  --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
          integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
          crossorigin="anonymous"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
            integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
            crossorigin="anonymous"></script>

    <!-- jQuery UI -->
    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script
        src="{{asset('atlantis/examples')}}/assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Bootstrap Tagsinput -->
    <script
        src="{{asset('atlantis/examples')}}/assets/js/plugin/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>


    <!-- Fonts and icons -->
    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {"families": ["Lato:300,400,700,900"]},
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ['{{asset('atlantis/examples')}}/assets/css/fonts.min.css']
            },
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>


    @yield('head-section')

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{asset('atlantis/examples')}}/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('atlantis/examples')}}/assets/css/atlantis.min.css">
    <link href="https://vjs.zencdn.net/8.3.0/video-js.css" rel="stylesheet"/>
    <!---Select2 To MultiSelect -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <style>
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999; /* Make sure the loader is above everything else */
        }

        .hidden-actions {
            display: none;
            padding: 10px;
        }

        .loader {
            border: 4px solid #f3f3f3; /* Light gray border */
            border-top: 4px solid #3498db; /* Blue border for loading indicator */
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite; /* Spin animation */
        }

        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999; /* Make sure the loader is above everything else */
        }

        .blinking-overlay {
            display: flex;
            align-items: center;
            justify-content: center;
            animation: blink 1s infinite;
        }

        .centered-image {
            max-width: 20%; /* Adjust the maximum width of the image */
            max-height: 20vh; /* Adjust the maximum height of the image */
        }

        /* CSS for the timer */
        .timer {
            font-size: 24px;
            color: black; /* Initial color */
            animation: blink 1s step-end infinite; /* Blinking animation */
        }

        @keyframes blink {
            50% {
                color: transparent; /* Blinking color */
            }
        }

        /* Floating timer container */
        .timer-container {
            position: fixed;
            top: 10px; /* Adjust the top position as needed */
            right: 10px; /* Adjust the right position as needed */
            background-color: white; /* You can adjust the background color as needed */
            z-index: 9999;
            padding: 10px;
            border: 1px solid #ccc; /* Add a border for style */
            border-radius: 5px;
        }

        /* Style for the timer */
        #timer {
            font-size: 24px;
            font-weight: bold;
            color: black; /* You can adjust the text color as needed */
        }

        /* Define the style for cards outside the specified index range */
        .outside-index-card {
            background-color: #ffcccc; /* Change the background color to light red */
            border: 2px solid #ff0000; /* Add a red border */
            color: #ff0000; /* Change the text color to red */
            display: none; /* Hide the card */
        }

        /* Additional styling for the timer as needed */

        @keyframes blink {
            0%, 100% {
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <!-- CSS Just for demo purpose, don't include it in your project -->
    {{-- <link rel="stylesheet" href="{{asset('atlantis/examples')}}/assets/css/demo.css"> --}}

</head>
<body>


<div id="loaderOverlay" class="loader-overlay">
    <div class="blinking-overlay">
        <img src="{{URL::to('/')}}/mdln_long.png" alt="navbar brand" class="centered-image">
    </div>
</div>
<script>
    var loaderOverlay = document.getElementById('loaderOverlay');

    // Function to show the loading overlay
    function showLoaderOverlay() {
        if (loaderOverlay) {
            loaderOverlay.style.display = '';
        }
    }

    // Function to hide the loading overlay
    function hideLoaderOverlay() {
        setTimeout(function () {
            if (loaderOverlay) {
                loaderOverlay.style.display = 'none';
            }
        }, 1000);
    }

    function hideLoaderOverlayNow() {
        setTimeout(function () {
            if (loaderOverlay) {
                loaderOverlay.style.display = 'none';
            }
        }, 0);
    }
</script>
<script>
    showLoaderOverlay()
    hideLoaderOverlay()
</script>

<div class="wrapper">
    
    @if(!isset($showCompact))
        <div class="main-header">
            <!-- Logo Header -->
            <div class="logo-header" style="background-color: #1D2026">
                <a href="{{url('/home')}}" class="logo">
                    <div style="text-align: center;">
                        <img src="{{URL::to('/')}}/home_assets/img/ic_LearningMDLN.svg" 
                             style="width: 80%; 
                                    height: auto;
                                    display: flex;
                                    margin-top: 5px;
                             "
                        >
                    </div>
                </a>
                
                <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="icon-menu"></i>
                    </button>
                </div>
            </div>
            <!-- End Logo Header -->

            <!-- Navbar Header -->
            @auth
                @include('main.nav_bar')
            @endauth

            <!-- End Navbar -->
        </div>

        <!-- Sidebar -->
        @include('main.side-bar')
        <!-- End Sidebar -->

    @endif

    @if(!isset($showCompact))
        <div class="main-panel">
            @endif
            <div class="content">
                @yield('breadcumb')
                @yield('main')
            </div>
            @if(!isset($showCompact))

        </div>
    @endif
</div>


<!-- Chart JS -->
<script src="{{asset('atlantis/examples')}}/assets/js/plugin/chart.js/chart.min.js"></script>

<!-- jQuery Sparkline -->
<script src="{{asset('atlantis/examples')}}/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

<!-- Chart Circle -->
<script src="{{asset('atlantis/examples')}}/assets/js/plugin/chart-circle/circles.min.js"></script>

<!-- Datatables -->
<script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>

<!-- Bootstrap Notify -->
<script src="{{asset('atlantis/examples')}}/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

<!-- jQuery Vector Maps -->
<script src="{{asset('atlantis/examples')}}/assets/js/plugin/jqvmap/jquery.vmap.min.js"></script>
<script src="{{asset('atlantis/examples')}}/assets/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

<!-- Sweet Alert -->
<script src="{{asset('atlantis/examples')}}/assets/js/plugin/sweetalert/sweetalert.min.js"></script>

<!-- Atlantis JS -->
<script src="{{asset('atlantis/examples')}}/assets/js/atlantis.min.js"></script>

<!-- Atlantis DEMO methods, don't include it in your project! -->
{{-- <script src="{{asset('atlantis/examples')}}/assets/js/setting-demo.js"></script>--}}
{{-- <script src="{{asset('atlantis/examples')}}/assets/js/demo.js"></script>  --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script>

    window.addEventListener("load", () => {
        console.log("%c 💻 Modernland.com is Hiring: %chttps://careers.blibli.com%c ", "background:#000;color:#0f0;font-family:Lucida console;font-size:20px;letter-spacing:-1px;display:block;padding:5px;box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255,255,255,0.4) inset;animation:blink 1s infinite;", "color:#0f0;text-decoration:underline;", "@keyframes blink{0%,100%{opacity:1}50%{opacity:0}}");
        // Calculate the loading time
        const loadingTime = Date.now() - performance.timing.navigationStart;

        if (loadingTime < 1000) {
            setTimeout(() => {
                loaderOverlay.style.display = "none";
            }, 2000 - loadingTime);
        } else {
            loaderOverlay.style.display = "none";
        }
    });


</script>

@yield('script')
</body>
</html>
