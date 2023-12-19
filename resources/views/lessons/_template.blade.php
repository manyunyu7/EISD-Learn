<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>{{ config('app.name') }}</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport'/>
    <link rel="icon" href="{{ asset('atlantis/examples') }}/assets/img/icon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <link href="https://vjs.zencdn.net/8.3.0/video-js.css" rel="stylesheet"/>


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


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <!--   Core JS Files   -->
    <script src="{{ asset('atlantis/examples') }}/assets/js/core/jquery.3.2.1.min.js"></script>
    <script src="{{ asset('atlantis/examples') }}/assets/js/core/popper.min.js"></script>
    <script src="{{ asset('atlantis/examples') }}/assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery UI -->
    <script src="{{ asset('atlantis/examples') }}/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="{{ asset('atlantis/examples') }}/assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js">
    </script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('atlantis/examples') }}/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Bootstrap Tagsinput -->
    <script
        src="{{ asset('atlantis/examples') }}/assets/js/plugin/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>


    <!-- Fonts and icons -->
    <script src="{{ asset('atlantis/examples') }}/assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Lato:300,400,700,900"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands",
                    "simple-line-icons"
                ],
                urls: ['{{ asset('atlantis/examples') }}/assets/css/fonts.min.css']
            },
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>

    @yield('head-section')

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('atlantis/examples') }}/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('atlantis/examples') }}/assets/css/atlantis.min.css">

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

@yield('main')


<div class="wrapper">
    <div class="main-header d-none">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="blue">

            @if(Auth::check())
                <a href="{{ url('/') }}" class="logo">
                    <p class="navbar-brand text-white">{{ config('app.name') }}</p>
                </a>
            @else
                <a href="{{ url('/') }}" class="logo">
                    <p class="navbar-brand text-white">{{ config('app.name') }}</p>
                </a>
            @endif
            <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
                    data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <i class="icon-menu"></i>
                    </span>
            </button>
            <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="icon-menu"></i>
                </button>
            </div>
        </div>
        <!-- End Logo Header -->

        <!-- Navbar Header -->
        {{-- @include('main.nav_bar') --}}
        <!-- End Navbar -->
    </div>

{{--    <!-- Sidebar -->--}}
{{--    @include('lessons._side-bar')--}}
{{--    <!-- End Sidebar -->--}}


    <div class="main-panel">
            @yield('breadcumb')

    </div>


</div>



<!-- Chart JS -->
<script src="{{ asset('atlantis/examples') }}/assets/js/plugin/chart.js/chart.min.js"></script>

<!-- jQuery Sparkline -->
<script src="{{ asset('atlantis/examples') }}/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

<!-- Chart Circle -->
<script src="{{ asset('atlantis/examples') }}/assets/js/plugin/chart-circle/circles.min.js"></script>

<!-- Datatables -->
<script src="{{ asset('atlantis/examples') }}/assets/js/plugin/datatables/datatables.min.js"></script>

<!-- Bootstrap Notify -->
<script src="{{ asset('atlantis/examples') }}/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

<!-- jQuery Vector Maps -->
<script src="{{ asset('atlantis/examples') }}/assets/js/plugin/jqvmap/jquery.vmap.min.js"></script>
<script src="{{ asset('atlantis/examples') }}/assets/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

<!-- Sweet Alert -->
<script src="{{ asset('atlantis/examples') }}/assets/js/plugin/sweetalert/sweetalert.min.js"></script>

<!-- Atlantis JS -->
<script src="{{ asset('atlantis/examples') }}/assets/js/atlantis.min.js"></script>

<!-- Atlantis DEMO methods, don't include it in your project! -->
{{-- <script src="{{asset('atlantis/examples')}}/assets/js/setting-demo.js"></script>
<script src="{{asset('atlantis/examples')}}/assets/js/demo.js"></script> --}}


@stack('custom-scripts')


@yield('script')
<script>
    Circles.create({
        id: 'circles-1',
        radius: 45,
        value: 60,
        maxValue: 100,
        width: 7,
        text: 5,
        colors: ['#f1f1f1', '#FF9E27'],
        duration: 400,
        wrpClass: 'circles-wrp',
        textClass: 'circles-text',
        styleWrapper: true,
        styleText: true
    })

    Circles.create({
        id: 'circles-2',
        radius: 45,
        value: 70,
        maxValue: 100,
        width: 7,
        text: 36,
        colors: ['#f1f1f1', '#2BB930'],
        duration: 400,
        wrpClass: 'circles-wrp',
        textClass: 'circles-text',
        styleWrapper: true,
        styleText: true
    })

    Circles.create({
        id: 'circles-3',
        radius: 45,
        value: 40,
        maxValue: 100,
        width: 7,
        text: 12,
        colors: ['#f1f1f1', '#F25961'],
        duration: 400,
        wrpClass: 'circles-wrp',
        textClass: 'circles-text',
        styleWrapper: true,
        styleText: true
    })

    var totalIncomeChart = document.getElementById('totalIncomeChart').getContext('2d');

    var mytotalIncomeChart = new Chart(totalIncomeChart, {
        type: 'bar',
        data: {
            labels: ["S", "M", "T", "W", "T", "F", "S", "S", "M", "T"],
            datasets: [{
                label: "Total Income",
                backgroundColor: '#ff9e27',
                borderColor: 'rgb(23, 125, 255)',
                data: [6, 4, 9, 5, 4, 6, 4, 3, 8, 10],
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false,
            },
            scales: {
                yAxes: [{
                    ticks: {
                        display: false //this will remove only the label
                    },
                    gridLines: {
                        drawBorder: false,
                        display: false
                    }
                }],
                xAxes: [{
                    gridLines: {
                        drawBorder: false,
                        display: false
                    }
                }]
            },
        }
    });

    $('#lineChart').sparkline([105, 103, 123, 100, 95, 105, 115], {
        type: 'line',
        height: '70',
        width: '100%',
        lineWidth: '2',
        lineColor: '#ffa534',
        fillColor: 'rgba(255, 165, 52, .14)'
    });
</script>
</body>

</html>
