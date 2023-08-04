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
        WebFont.load({
            google: {
                "families": ["Lato:300,400,700,900"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ['{{asset('
                    atlantis / examples ')}}/assets/css/fonts.min.css'
                ]
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <script src="home.js"></script>
    <style>
        @font-face {
            font-family: gloss;
            src: url(./home_assets/gloss.ttf);
        }

        .gloss {
            font-family: gloss, Quicksand;
            color: black;
        }
    </style>
    @yield('styling')

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{asset('atlantis/examples')}}/assets/css/bootstrap.min.css">
    {{-- <link rel="stylesheet" href="{{asset('atlantis/examples')}}/assets/css/atlantis.min.css"> --}}

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600&display=swap" rel="stylesheet">

    <style>
        nav {
            font-family: Nunito, sans-serif;
        }
    </style>

    <!-- Lottie Animate -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <!-- AOS ANIMATE -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>

<body>


    @include('top_bar')
    @yield('body')


    <footer class=" footer">
        <div class="l-footer">
            <img src="{{URL::to('/')}}/home_assets/img/esd_3.png" alt="" style="width: 280px; ">
            <hr>

            <p>
                Resource Belajar Digital Modernland Realty Tbk</p>

        </div>
        <ul class="r-footer">
            <li>
                <h2>
                    Social</h2>
                <ul class="box">
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Pinterest</a></li>
                    <li><a href="#">Dribbble</a></li>
                </ul>
            </li>
            <li class="features">
                <h2>
                    Explore</h2>
                <ul class="box h-box">
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Pricing</a></li>
                    <li><a href="#">Sales</a></li>
                    <li><a href="#">Tickets</a></li>
                    <li><a href="#">Certifications</a></li>
                    <li><a href="#">Customer Service</a></li>
                </ul>
            </li>
            <li>
                <h2>
                    Legal</h2>
                <ul class="box">
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Use</a></li>
                    <li><a href="#">Contract</a></li>
                </ul>
            </li>

        </ul>
        <div class="b-footer">
            <p>
                All rights reserved by © Kamis, <?php echo date('d F Y H:i:s'); ?> </p>
        </div>
    </footer>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>


</body>

</html>
