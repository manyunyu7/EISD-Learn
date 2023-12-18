<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{config('app.name')}}</title>
    <link rel="stylesheet" href="{{URL::to('/')}}/home_assets/styles/style.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/home_assets/styles/card-pricing.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/home_assets/styles/header.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/home_assets/styles/avatar.css">
    <!-- Fonts and icons -->
    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        {{--WebFont.load({--}}
        {{--    google: {--}}
        {{--        "families": ["Lato:300,400,700,900"]--}}
        {{--    },--}}
        {{--    custom: {--}}
        {{--        "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],--}}
        {{--        urls: ['{{asset('--}}
        {{--            atlantis/examples ')}}/assets/css/fonts.min.css'--}}
        {{--        ]--}}
        {{--    },--}}
        {{--    active: function () {--}}
        {{--        sessionStorage.fonts = true;--}}
        {{--    }--}}
        {{--});--}}
    </script>
    <style>
        @font-face {
            font-family: gloss;
            src: url(./home_assets/gloss.ttf);
        }

        .gloss {
            font-family: gloss, Quicksand;
            color: black;
        }

        .scrollable-table-body {
            max-height: 300px; /* Adjust the desired height */
            overflow-y: scroll;
        }


    </style>
    @yield('styling')

    <!-- CSS Files -->
    {{-- <link rel="stylesheet" href="{{asset('atlantis/examples')}}/assets/css/atlantis.min.css"> --}}
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600&display=swap" rel="stylesheet">

        <style>
            nav {
                font-family: Nunito, sans-serif;
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
                animation: blink 0.9s infinite;
            }

            .centered-image {
                max-width: 20%; /* Adjust the maximum width of the image */
                max-height: 20vh; /* Adjust the maximum height of the image */
            }

            
            


            /* Penyesuaian untuk tampilan lebih kecil */
            @media screen and (max-width: 767px) {
                .row {
                    flex-direction: column; /* Mengubah tata letak kolom menjadi satu di bawah yang lain */
                    text-align: center; /* Pusatkan konten dalam kolom */
                }

                .col-4 {
                    width: 100%; /* Gunakan lebar penuh pada tampilan layar yang lebih kecil */
                    margin-bottom: 20px; /* Tambahkan margin antara kolom */
                }

                .footerSocmed {
                    margin-left: 0; /* Atur margin kembali ke nilai default */
                }

                .col-4.footerSocmed {
                    margin-right: 0; /* Atur margin kembali ke nilai default */
                    margin-top: 0; /* Atur margin kembali ke nilai default */
                }
                .col-4{
                    margin-left: 110px;
                    column-width: 50%;
                }

                .footerLogo{
                    max-width: 50%; 
                    max-height: auto;
                    margin-left: 90px
                 }
                 .footerAddress{
                    margin-left: -50px;
                    color: #8C94A3;
                }
                .footerAddress b{
                    font-size: 8px;

                }
            }


            /* Penyesuaian untuk tampilan sedang */
            @media screen and (min-width: 820px) and (max-width: 1180px) {
                .footerLogo{
                    max-width: 30%; 
                    max-height: auto;
                    margin-left: -50px
                 }
                .footerAddress{
                    margin-left: -50px;
                    color: #8C94A3;
                }
                .footerAddress b{
                    font-size: 9px;
                }
                .footerCentral{
                    color: #8C94A3;
                    margin-right: 750px;
                    text-align: center;
                }
                .footerCentral b{
                    font-size: 9px;
                }
                .footerSocmed{
                    margin-left: 520px;
                    
                }
                .col-4.footerSocmed {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    margin-right: 850px;
                    margin-top: -200px;
                }

                .social-icon {
                    display: flex;
                }

                .social-icon a {
                    align-items: center;
                    background: hsla(#363B47);
                    border: 1px solid hsla(0, 0%, 100%, 0.5);
                    display: inline-flex;
                    height: 32px;
                    justify-content: center;
                    line-height: 1;
                    width: 32px;
                    transition: background 0.3s, border-color 0.3s;
                    margin: 0 10px;  /* Adjust as needed */
                }

                /* Efek hover */
                .social-icon a:hover {
                    background: hsla(0, 100%, 57%, 0.913);
                    border-color: hsla(0, 0%, 100%, 0.7);
                }
            }

            /* Penyesuaian untuk tampilan besar */
            @media screen and (min-width: 1440px) {
                .wrapper {
                    display: grid;
                    grid-template-columns : (auto-fit,
                                            minmax(min(11.25rem, 100%), 1 fr));
                    grid-gap: 1 rem;
                }
                .footerLogo{
                    max-width: 500%; 
                    max-height: auto;
                    margin-left: -550px
                }
                .footerAddress{
                    margin-left: -550px;
                    color: #8C94A3;
                    padding-right: 80%;
                }
                .footerCentral{
                    color: #8C94A3;
                    margin-right: 750px;
                    text-align: center;
                }
                .footerSocmed{
                    margin-left: 1350px;
                    
                }
                .col-4.footerSocmed {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    margin-right: 850px;
                    margin-top: -150px;
                }

                .social-icon {
                    display: flex;
                }

                .social-icon a {
                    align-items: center;
                    background: hsla(#363B47);
                    border: 1px solid hsla(0, 0%, 100%, 0.5);
                    display: inline-flex;
                    height: 42px;
                    justify-content: center;
                    line-height: 1;
                    width: 42px;
                    transition: background 0.3s, border-color 0.3s;
                    margin: 0 10px;  /* Adjust as needed */
                }

                /* Efek hover */
                .social-icon a:hover {
                    background: hsla(0, 100%, 57%, 0.913);
                    border-color: hsla(0, 0%, 100%, 0.7);
                }
            }

            

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
        @stack("InternalStyle")

        
    <!-- Lottie Animate -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <!-- AOS ANIMATE -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Bootstrap CSS 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>


    <div id="loaderOverlay" class="loader-overlay">
        <div class="blinking-overlay">
            <img src="{{URL::to('/')}}/mdln_long.png" alt="navbar brand" class="centered-image">
        </div>
    </div>
@include('top_bar')
@yield('body')


<footer class=" footer" style="background-color: #1D2026">
        <div class="container">
            <div class="row">
                <div class="col-4" >
                    <div class="row">
                        <img src="{{URL::to('/')}}/logoPutih.png" alt="navbar brand" class="centered-image footerLogo">
                    </div>
                    <div class="footerAddress" >
                        <br>
                        <b>Commercial Area 5th, Green Central City, Jl.Gajah Mada</b><br>
                        <b>RT.3/RW.5, Glodok, Kec. Taman Sari</b><br>
                        <b>Jakarta, Daerah Khusus Ibukota Jakarta</b><br>
                        <b>11120</b><br>
                    </div>
                </div>
                <div class="col-4 " style="background-color: red">
                    <div class="row footerCentral">
                        <br><br><br><br><br>
                        <b style="color: white">You're not Login (Login Here)</b>
                        <b>Copyright @2024 PT. Modernland Realty Tbk.</b>
                        <b>All Right Reserved</b>
                    </div>
                </div>
                <div class="col-4 footerSocmed" style="background-color: red">
                    <div class="social-icon">
                        <a href="https://github.com/KevinValerian21/">
                            <img src="{{URL::to('/')}}/Facebook.svg" alt="">
                        </a>
                        <a href="https://www.instagram.com/kevinvarn21">
                            <img src="{{URL::to('/')}}/Instagram.svg" alt="">
                        </a>
                        <a href="https://www.linkedin.com/in/kevinvalerianninia">
                            <img src="{{URL::to('/')}}/Linkedin.svg" alt="">
                        </a>
                        <a href="https://www.instagram.com/kevinvarn21">
                            <img src="{{URL::to('/')}}/Youtube.svg" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </div>
</footer>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init();
</script>

<script>
    const loaderOverlay = document.getElementById("loaderOverlay");

    window.addEventListener("load", () => {
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
</body>

</html>
