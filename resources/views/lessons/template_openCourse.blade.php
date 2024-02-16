<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">

    @yield('head-section')

    <!-- CSS Files -->
    <style>
        /* p{
            font-family: Georgia;
        } */
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

        /* Style untuk TABEL 1 */
        .table1 {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px; /* Adjust margin as needed */
        }

        .table1 th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        }

        .table1 th {
        background-color: cyan;
        }
        

        /* Style untuk TABEL 2 */
        .table2 {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px; /* Sesuaikan margin sesuai kebutuhan */
        }
        
        .table2 th{
            border: 0px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table2 td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .table2 th {
            background-color: cyan;
        }

        /* Menerapkan flexbox pada setiap baris table */
        .table2 tr {
            display: flex;
            width: 100%;
        }
        
        /* Mengatur lebar kolom checkbox */
        .table2 td:first-child {
            flex-basis: auto; /* Menyesuaikan lebar berdasarkan isi */
            flex-shrink: 0; /* Tetapkan agar tidak mengecil */
        }
        
        /* Mengatur lebar kolom elemen p */
        .table2 td:last-child {
            flex: 1; /* Memanfaatkan ruang yang tersedia */
        }
        /* Mengatur tinggi masing-masing baris */
        .table2 tr {
            height: 50px; /* Atur tinggi sesuai kebutuhan */
        }
        .align-middle {
            display: flex;
            align-items: center;
        }

        .btn {
          border: none; /* Remove borders */
          border-radius: 12px;
          color: white; /* Add a text color */
          padding: 14px 28px; /* Add some padding */
          cursor: pointer; /* Add a pointer cursor on mouse-over */
        }

        .success {background-color: #04AA6D;} /* Green */
        .success:hover {background-color: #46a049;}

        

      </style>
      

</head>

<body>
    <div class="wrapper">
        
        @if(!isset($showCompact))
            <!-- Container Body -->
            @include('lessons.openCourse')
            <!-- End Container Body -->
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