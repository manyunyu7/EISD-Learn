<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="admin, dashboard">
    <meta name="author" content="Henry Augusta">
    <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Griya Sodaqo &#8211; Gerakan Sodaqo Indonesia">
    <meta property="og:title" content="Griya Sodaqo : Gerakan Sodaqo Indonesi">
    <meta property="og:description" content="Sodaqo.id telah memiliki banyak partner kolaborasi yang bersedia membantu orang orang yang membutuhkan bantuan. Selaijn itu, untuk saat ini SODAQO.id fokus menyantuni Anak Yatim Duafa yang tersebar di 17 Panti Asuhan yang berlokasi di Kota Bandung. Total penerima manfaat SODAQO adalah 451 Anak yatim">
    <meta property="og:image" content="http://feylabs.my.id/fm/apk/cover_sodaqo.png">
    <meta name="format-detection" content="telephone=no">

    <!-- PAGE TITLE HERE -->
    <title>Sodaqo.id</title>

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('/168_res') }}/images/favicon.png">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito">
    <link href="{{ asset('/168_res') }}/vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/168_res') }}/vendor/nouislider/nouislider.min.css">
    <!-- Style css -->
    <link href="{{ asset('/168_res') }}/css/style.css" rel="stylesheet">

    <link href="{{ asset('/168_res') }}/vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

    @stack('css')

    <style>
        @stack("css_content")
    </style>

</head>
<body>

<!--*******************
    Preloader start
********************-->
@include("168_component.preloader.preloader")
<!--*******************
    Preloader end
********************-->

<!--**********************************
    Main wrapper start
***********************************-->
<div id="main-wrapper">

    <!--**********************************
        Nav header start
    ***********************************-->
    @include("168_component.header.nav_header")
    <!--**********************************
        Nav header end
    ***********************************-->

    <!--**********************************
        Chat box start
    ***********************************-->
    @include('168_component.chatbox.chatbox')
    <!--**********************************
        Chat box End
    ***********************************-->

    <!--**********************************
        Header start
    ***********************************-->
    @include('168_component.header.header')
    <!--**********************************
        Header end ti-comment-alt
    ***********************************-->

    <!--**********************************
        Sidebar start
    ***********************************-->
    @include('168_component.sidebar.sidebar')
    <!--**********************************
        Sidebar end
    ***********************************-->

    <!--**********************************
        Content body start
    ***********************************-->
    @yield("page_content")
    <!--**********************************
        Content body end
    ***********************************-->


    <!--**********************************
        Footer start
    ***********************************-->
    @include('168_component.footer.footer')
    <!--**********************************
        Footer end
    ***********************************-->


</div>
<!--**********************************
    Main wrapper end
***********************************-->

<!--**********************************
    Scripts
***********************************-->
<!-- Required vendors -->
<script src="{{ asset('/168_res') }}/vendor/global/global.min.js"></script>
<script src="{{ asset('/168_res') }}/vendor/chart.js/Chart.bundle.min.js"></script>
<script src="{{ asset('/168_res') }}/vendor/jquery-nice-select/js/jquery.nice-select.min.js"></script>

<!-- Apex Chart -->
<script src="{{ asset('/168_res') }}/vendor/apexchart/apexchart.js"></script>
<script src="{{ asset('/168_res') }}/vendor/nouislider/nouislider.min.js"></script>
<script src="{{ asset('/168_res') }}/vendor/wnumb/wNumb.js"></script>

<!-- Dashboard 1 -->
<script src="{{ asset('/168_res') }}/js/dashboard/dashboard-1.js"></script>

<script src="{{ asset('/168_res') }}/js/custom.min.js"></script>
<script src="{{ asset('/168_res') }}/js/dlabnav-init.js"></script>
{{--<script src="{{ asset('/168_res') }}/js/demo.js"></script>--}}
{{--<script src="{{ asset('/168_res') }}/js/styleSwitcher.js"></script>--}}


<script src="{{ asset('/168_res') }}/vendor/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="{{ asset('/168_res') }}/js/plugins-init/sweetalert.init.js"></script>


<script>
    function onErrorImg(e) {
        e.onerror = null;
        e.src = 'https://avatarsb.s3.amazonaws.com/others/panda-black-toy1-31-min.png';
    }
</script>
<script>
    window.addEventListener('beforeunload', function () {
        // Show the loading indicator when the page is about to be unloaded
        document.querySelector('#preloader').style.display = 'block';
    });
</script>


<script>
    // $(document).ajaxStart(function () {
    //     // Show the loading indicator
    //     showLoadingP()
    // });
    //
    // $(document).ajaxStop(function () {
    //     // Hide the loading indicator
    //     hideLoadingP()
    // });
</script>


@stack('script')

</body>
</html>
