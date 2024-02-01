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
    <style>
        /* Custom CSS */
        #main {
          margin-left: 0; /* Initialize margin-left to 0 */
          transition: margin-left 0.5s; /* Add transition for smooth effect */
        }
    
        #openNav {
          position: absolute; /* Position absolute for easy alignment */
          top: 10px; /* Adjust top position as needed */
          left: 0px; /* Adjust left position as needed */
        }
    
        /* Adjust styles as needed */
        .w3-teal {
          position: relative; /* Set position relative for proper stacking */
        }

        #judulKelas {
            margin-left: 40px;;
        }


        table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px; /* Adjust margin as needed */
        }

        th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        }

        th {
        background-color: cyan;
        }




      </style>
      

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
        // JavaScript functions (w3_open and w3_close) remain unchanged
        function w3_open() {
          document.getElementById("mySidebar").style.display = "block";
          document.getElementById("mySidebar").style.width = "25%";
          document.getElementById("main").style.marginLeft = "25%";
        }
    
        function w3_close() {
          document.getElementById("mySidebar").style.display = "none";
          document.getElementById("main").style.marginLeft = "0";
        }
      </script>
@yield('script')
</body>
</html>
