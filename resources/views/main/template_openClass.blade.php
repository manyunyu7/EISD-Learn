<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    @yield('head-section')

    <!-- CSS Files -->

</head>
<body>
    <div class="wrapper">
        
        @if(!isset($showCompact))
            {{-- <div class="main-header">
                <!-- Navbar Header -->
                @auth
                    @include('main.nav_bar')
                @endauth

                <!-- End Navbar -->
            </div> --}}

            <!-- Sidebar -->
            @include('main.sidebar.class-sidebar')
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

    
<script>
    function openLeftMenu() {
      document.getElementById("leftMenu").style.display = "block";
    }
    
    function closeLeftMenu() {
      document.getElementById("leftMenu").style.display = "none";
    }
    
    function openRightMenu() {
      document.getElementById("rightMenu").style.display = "block";
    }
    
    function closeRightMenu() {
      document.getElementById("rightMenu").style.display = "none";
    }
    </script>
@yield('script')
</body>
</html>
