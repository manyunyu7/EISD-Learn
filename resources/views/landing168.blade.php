<!---
<p>Welcome! We're absolutely delighted that you've stumbled upon our corner of the web! 😊 However, it appears that there's still room for enhancement, and we believe that your expertise as a skilled front-end developer, particularly with experience in frameworks like Laravel, Flutter, and other cutting-edge technologies, could be the missing ingredient to take this page to even greater heights of beauty and functionality. So, are you ready to embark on this exciting challenge with us and bring your expertise in these areas to help shape the future of our platform?</p>
---->

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap"
          rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet">
    {{--    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>--}}

    <style>

        #first-section {
            height: 100vh;
        }

        #first-section .left {
            margin-left: 88px;
        }

        #first-section .right {
            margin-left: 88px;
        }

        @media screen and (max-width: 1200px) {
            #jamal-talking {
                display: none;
            }
        }

        @media screen and (max-width: 768px) {
            #first-section .left {
                margin-left: 0px;
            }

            .social-icon {
                padding-left: 50px;
                padding-right: 50px;
            }

        }

        .social-link {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 40px; /* Adjust the width as needed */
            height: 40px; /* Adjust the height as needed */
            margin: 5px; /* Optional: Add margin between the links */
            background-color: #ccc; /* Optional: Add a background color */
        }

        .social-link img {
            max-width: 80%;
            max-height: 80%;
        }

        .font-mulish {
            font-family: "Mulish", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
        }

        .font-inter {
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
            font-variation-settings: "slnt" 0;
        }

        .row-no-padding {
            [class*="col-"] {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
        }

        .nopadding {
            padding: 0 !important;
            margin: 0 !important;
        }


    </style>
    <title>LMS</title>
</head>
<body>


<nav class="navbar navbar-expand navbar-dark bg-white" aria-label="Second navbar example">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img src="{{asset("/home_icons/icon_navbar_modern.png")}}" alt="" height="24"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample02" aria-controls="navbarsExample02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample02">
            <ul class="navbar-nav me-auto">

            </ul>
            @auth
                <a href="{{ url('/home') }}" class="btn btn-danger font-mulish">Home</a>
            @else
                <a href="{{ url('/login') }}" class="btn btn-danger font-mulish">Login</a>
            @endauth
        </div>
    </div>
</nav>

{{--<nav style="background-color: white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);"--}}
{{--     class="navbar navbar-expand-lg sticky-top"--}}
{{--     aria-label="Fifth navbar example">--}}
{{--    <div class="container-fluid">--}}
{{--        <a class="navbar-brand" href="#">--}}
{{--            <img src="{{asset("/home_icons/icon_navbar_modern.png")}}" alt="" height="24">--}}
{{--        </a>--}}
{{--        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample05"--}}
{{--                aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">--}}
{{--            <span class="navbar-toggler-icon"></span>--}}
{{--        </button>--}}

{{--        <div class="collapse ">--}}
{{--            <ul class="navbar-nav me-auto mb-2 mb-lg-0">--}}
{{--                <li class="nav-item dropdown d-none">--}}
{{--                    <a class="nav-link dropdown-toggle" href="#" id="dropdown05" data-bs-toggle="dropdown"--}}
{{--                       aria-expanded="false">Dropdown</a>--}}
{{--                    <ul class="dropdown-menu" aria-labelledby="dropdown05">--}}
{{--                        <li><a class="dropdown-item" href="#">Action</a></li>--}}
{{--                        <li><a class="dropdown-item" href="#">Another action</a></li>--}}
{{--                        <li><a class="dropdown-item" href="#">Something else here</a></li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
{{--            </ul>--}}

{{--            <form class="form-inline my-2 my-md-0">--}}
{{--                <input class="form-control" type="text" placeholder="Search">--}}
{{--            </form>--}}

{{--            @auth--}}
{{--                <a href="{{ url('/home') }}" class="btn btn-danger font-mulish">Home</a>--}}
{{--            @else--}}
{{--                <a href="{{ url('/login') }}" class="btn btn-danger font-mulish">Login</a>--}}
{{--            @endauth--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</nav>--}}

<div id="first-section" class="container-fluid">
    <div class="row">

        <div class="col-md-6 col-lg-6" data-aos="fade-right" data-aos-delay="300">
            <div style="height: 100%" class="bg-red">
                <div class="box w-100">
                    <div class="container p-5 w-100">
                        <img src="{{url("home_icons/icon_toga_home.png")}}" style="max-height: 150px"
                             class="rounded float-start"
                             alt="...">
                    </div>
                    <div class="container d-flex flex-column justify-content-center">
                        <h3 style="font-size: 44px" class="font-inter">Selamat Datang di</h3>
                        <h3 style="font-size: 54px; font-weight: bolder">Modernland
                            <span
                                style="background-color: red; color: white; padding: 5px 10px; border-radius: 10px;">Academy</span>
                        </h3>
                        <div class="mt-4">
                            <h3 class="font-inter" style="font-weight: normal">Pembelajaran Efektif, Kreatif, dan
                                Interaktif
                                dengan
                                Modernland Academy System.</h3>
                        </div>

                        <div class="mt-4">
                            <button style="background-color: #FC1E01" type="button" class="btn btn-danger"
                                    href="{{ url('login') }}">
                                <b>Get Started</b>
                            </button>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="col-md-6 col-lg-6 vh-100 d-sm-none d-xl-block d-md-block d-none"
             data-aos="fade-left" data-aos-delay="800"
             style="position: relative;">
            <img src="{{url("home_icons/uwes_talking.png")}}"
                 style="height: 90%; object-fit: contain; object-position: bottom; position: absolute; bottom: 0; right: 0;"
                 class="rounded float-start img-fluid"
                 alt="...">
        </div>
    </div>
</div>
<hr style="height: 1px; border: none; color: #000; background-color: #000; margin-top: -1px">
<div id="second-section" class="container-fluid">
    <div class="row">
        <div id="jamal-talking" class="col-md-6 col-lg-6 vh-100 "
             data-aos="fade-up-right" data-aos-delay="500"
             style="position: relative;">
            <img src="{{url("home_icons/jamal_talking.png")}}"
                 style="height: 100%; object-fit: contain; object-position: left; position: absolute; left: 0;"
                 class="rounded float-start img-fluid"
                 alt="...">
        </div>

        <div class="col-md-6 col-lg-6 mt-5">
            <div style="height: 100%">
                <div class="">
                    <div class="container justify-content-center" style="margin-top: 8px">

                        <div class="mb-5"
                             data-aos="fade-left" data-aos-delay="500">
                            <h3 style="font-size: 34px; font-weight: bolder">
                            <span
                                style="background-color: black; color: white; padding: 5px 10px; border-radius: 10px;">Konten</span>
                            </h3>
                        </div>


                        <div class="row mt-3 mb-5" style="display: flex; flex-wrap: wrap;"
                             data-aos="fade-left" data-aos-delay="500"
                        >
                            <div class="col-md-3" style="flex: 0 0 auto; width: 100%; max-width: 25%;">
                                <div class="d-flex justify-content-end justify-content-center">
                                    <img src="{{ URL::to('/home_icons') }}/ic_content_pelatihan.png" class="img-fluid"
                                         alt="Image 2" style="max-height: 88px; margin-left: 10px;">
                                </div>
                            </div>
                            <div class="col-md-9" style="flex: 0 0 auto; width: 100%; max-width: 75%;">
                                <h2 style="margin-bottom: 5px;">Pelatihan</h2>
                                <p style="margin-top: 5px; margin-bottom: 0;">Materi pelatihan disajikan dalam bentuk
                                    yang menarik dengan elemen interaktif.</p>
                            </div>
                        </div>

                        <div class="row mt-3 mb-5" style="display: flex; flex-wrap: wrap;"
                             data-aos="fade-left" data-aos-delay="500"
                        >
                            <div class="col-md-3" style="flex: 0 0 auto; width: 100%; max-width: 25%;">
                                <div class="d-flex justify-content-end justify-content-center">
                                    <img src="{{ URL::to('/home_icons') }}/ic_content_ujian.png" class="img-fluid"
                                         alt="Image 2" style="max-height: 100px; margin-right: 10px;">
                                </div>
                            </div>
                            <div class="col-md-9" style="flex: 0 0 auto; width: 100%; max-width: 75%;">
                                <h2 style="margin-bottom: 5px;">Ujian dan Evaluasi</h2>
                                <p style="margin-top: 5px; margin-bottom: 0;">Dapat mengukur pemahaman karyawan melalui
                                    ujian dan evaluasi secara online.</p>
                            </div>
                        </div>

                        <div class="row mt-3 mb-5" style="display: flex; flex-wrap: wrap;"
                             data-aos="fade-left" data-aos-delay="500"
                        >
                            <div class="col-md-3" style="flex: 0 0 auto; width: 100%; max-width: 25%;">
                                <div class="d-flex justify-content-end justify-content-center">
                                    <img src="{{ URL::to('/home_icons') }}/ic_content_leaderboard.png" class="img-fluid"
                                         alt="Image 2" style="max-height: 100px; margin-right: 10px;">
                                </div>
                            </div>
                            <div class="col-md-9" style="flex: 0 0 auto; width: 100%; max-width: 75%;">
                                <h2 style="margin-bottom: 5px;">Leaderboard</h2>
                                <p style="margin-top: 5px; margin-bottom: 0;">Menghadirkan elemen kompetisi dan motivasi
                                    tambahan dalam proses pembelajaran online.</p>
                            </div>
                        </div>

                        <div class="row mt-3 mb-5" style="display: flex; flex-wrap: wrap;"
                             data-aos="fade-left" data-aos-delay="500"
                        >
                            <div class="col-md-3" style="flex: 0 0 auto; width: 100%; max-width: 25%;">
                                <div class="d-flex justify-content-end justify-content-center">
                                    <img src="{{ URL::to('/home_icons') }}/ic_content_video.png" class="img-fluid"
                                         alt="Image 2" style="max-height: 100px; margin-right: 10px;">
                                </div>
                            </div>
                            <div class="col-md-3" style="flex: 0 0 auto; width: 100%; max-width: 75%;">
                                <h2 style="margin-bottom: 5px;">Video Pembelajaran</h2>
                                <p style="margin-top: 5px; margin-bottom: 0;">Menghadirkan elemen kompetisi dan motivasi
                                    tambahan dalam proses pembelajaran online.</p>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
<hr style="height: 1px; border: none; color: #000; background-color: #000; margin-top: -1px">
<div id="third-section" class="container-fluid">
    <div class="row">

        <div class="col-md-8 col-lg-8 mt-5">
            <div style="height: 100%">
                <div class="">
                    <div class="container justify-content-center" style="margin-top: 8px">

                        <div class="mb-5 col-12"
                             data-aos="fade-left" data-aos-delay="500"
                        >
                            <h3 style="font-size: 34px; font-weight: bolder">
                            <span
                                style="background-color: black; color: white; padding: 5px 10px; border-radius: 10px;">FAQ</span>
                            </h3>
                        </div>

                        <div class="accordion"
                             data-aos="fade-left" data-aos-delay="500"
                        >
                            <div class="accordion-item"
                                 data-aos="fade-left" data-aos-delay="700"
                            >
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne"
                                            aria-expanded="true" aria-controls="collapseOne">
                                        Accordion Item #1
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show"
                                     aria-labelledby="headingOne"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <strong>This is the first item's accordion body.</strong> It is shown by
                                        default, until the collapse
                                        plugin adds the appropriate classes that we use to style each element. These
                                        classes control the
                                        overall appearance, as well as the showing and hiding via CSS transitions. You
                                        can modify any of
                                        this with custom CSS or overriding our default variables. It's also worth noting
                                        that just about any
                                        HTML can go within the <code>.accordion-body</code>, though the transition does
                                        limit overflow.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item"
                                 data-aos="fade-left" data-aos-delay="700"
                            >
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo" aria-expanded="false"
                                            aria-controls="collapseTwo">
                                        Accordion Item #2
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <strong>This is the second item's accordion body.</strong> It is hidden by
                                        default, until the
                                        collapse plugin adds the appropriate classes that we use to style each element.
                                        These classes
                                        control the overall appearance, as well as the showing and hiding via CSS
                                        transitions. You can
                                        modify any of this with custom CSS or overriding our default variables. It's
                                        also worth noting that
                                        just about any HTML can go within the <code>.accordion-body</code>, though the
                                        transition does limit
                                        overflow.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item"
                                 data-aos="fade-left" data-aos-delay="700"
                            >
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree">
                                        Accordion Item #3
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse"
                                     aria-labelledby="headingThree"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <strong>This is the third item's accordion body.</strong> It is hidden by
                                        default, until the
                                        collapse plugin adds the appropriate classes that we use to style each element.
                                        These classes
                                        control the overall appearance, as well as the showing and hiding via CSS
                                        transitions. You can
                                        modify any of this with custom CSS or overriding our default variables. It's
                                        also worth noting that
                                        just about any HTML can go within the <code>.accordion-body</code>, though the
                                        transition does limit
                                        overflow.
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-4 vh-100 d-sm-none d-xl-block d-md-block d-none"
             data-aos="fade-left" data-aos-delay="500"
             style="position: relative;">
            <div style="display: flex; justify-content: center; margin-top: 100px">
                <img src="{{url("home_icons/cluster_modern.png")}}"
                     style="height: 90%; object-fit: contain; object-position: bottom;"
                     class="rounded img-fluid"
                     alt="...">
            </div>
        </div>

    </div>
</div>


<footer class="d-flex bg-dark flex-wrap justify-content-between align-items-center pt-4 pb-4 mt-4  border-top"
        style="padding-bottom: -150px"
>
    <div class="col-md-4 d-flex align-items-center">
        <a href="/" class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
            <svg class="bi" width="30" height="24">
                <use xlink:href="#bootstrap"></use>
            </svg>
        </a>
        <div class="container">
            <img src="{{asset("/home_icons/logo_modern_light.png")}}" alt="" height="24">
            <p>Test</p>
            <p style="color: #8C94A3">Commercial Area 5th, Green Central City, Jl. Gajah Mada, RT.3/RW.5, Glodok, Kec.
                Taman Sari, Jakarta, Daerah Khusus Ibukota Jakarta 11120</p>
        </div>
    </div>


    <div class="nav col-md-4 justify-content-center  d-flex">
        <div class="social-icon">
            <a href="https://facebook.com/modernlandrealty/" class="social-link" style="background-color: #363B4766;">
                <img src="{{URL::to('/')}}/Facebook.svg" alt="">
            </a>
            <a href="https://www.instagram.com/modernlandrealty" class="social-link"
               style="background-color: #363B4766;">
                <img src="{{URL::to('/')}}/Instagram.svg" alt="">
            </a>
            <a href="https://www.linkedin.com/company/modernland/mycompany/" class="social-link"
               style="background-color: #363B4766;">
                <img src="{{URL::to('/')}}/Linkedin.svg" alt="">
            </a>
            <a href="https://www.youtube.com/channel/UCY55_aSJ51DrMuucN4M4kuw" class="social-link"
               style="background-color: #363B4766;">
                <img src="{{URL::to('/')}}/Youtube.svg" alt="">
            </a>
        </div>
    </div>


    @guest
        <div class="col-12 text-center">
            <div>
                <b style="color: white">You're not Login
                    <a style="text-decoration: none" href="{{url("/login")}}">
                        (Login Here)
                    </a>
                </b>
            </div>
            <div>
                <b>Copyright ©2024 PT. Modernland Realty Tbk.</b>
            </div>
            <div>
                <b>All Right Reserved</b>
            </div>
        </div>
    @endguest

</footer>

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
-->


<script>

    window.addEventListener("load", () => {
        console.log("%c 💻 Modernland.com is Hiring: %chttps://facebook.com%c ", "background:#000;color:#0f0;font-family:Lucida console;font-size:20px;letter-spacing:-1px;display:block;padding:5px;box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255,255,255,0.4) inset;animation:blink 1s infinite;", "color:#0f0;text-decoration:underline;", "@keyframes blink{0%,100%{opacity:1}50%{opacity:0}}");
        // Calculate the loading time
    });


</script>

{{--<script src="https://unpkg.com/aos@next/dist/aos.js"></script>--}}
<script>
    // AOS.init();
</script>
</body>
</html>
