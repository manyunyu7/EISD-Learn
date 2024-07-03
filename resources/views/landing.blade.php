    @extends('template')

    @section('styling')

    @endsection
    @section('body')

    @push('InternalStyle')
    <style>
        /* General styles for badges and text */
        .badge {
            font-size: 58px;
        }

        span {
            font-size: 28px;
        }

        .mdln- {
            font-size: 47px;
        }

        /* Penyesuaian untuk tampilan lebih kecil */
        @media screen and (max-width: 768px) {

            /* Reset widths for smaller screens */
            .section1,
            .section2 {
                width: 100%;
            }

            .section1 {
                margin-left: 13px;
            }

            /* Adjust badge and text sizes for smaller screens */
            .badge {
                font-size: 20px;
            }

            span {
                font-size: 14px;
            }

            .mdln {
                font-size: 38px;
            }

            .mdln- {
                font-size: 45px;
            }

            .imagesHeader {
                max-width: 100%;
                height: auto;
                display: block;
                margin-top: 55px;
            }

            .imgHeader_2 {
                max-width: 100%;
                height: auto;
                margin-left: 15px;
            }

            .tblContent {
                margin-left: 50px;
                margin-top: -30px;
                width: 110%;
                text-align: left;
            }

            .imgPembelajaran {
                width: 20vw;
                height: auto;
                margin-left: -50px
            }

            .titlePembelajaran {
                font-size: 18px;
            }

            .deskripsiPembelajaran {
                font-size: 13px;
            }

            .BtnModulTitle {
                width: 185px;
                height: 50px;
                pointer-events: none;
                margin-left: -50px
            }

            .modulTitle {
                font-size: 20px;
            }

            .BtnModulTitle_FAQ {
                width: 114px;
                height: 50px;
                pointer-events: none;
                margin-left: 15px
            }

            .modulTitle_FAQ {
                font-size: 20px;
            }

            .accordion-body p {
                font-size: 13px;
                text-align: left;
            }

        }

        /* Penyesuaian untuk tampilan sedang */
        @media screen and (min-width: 820px) and (max-width: 1180px) and (orientation: portrait) {

            /* Adjust widths for medium-sized screens */
            .section1 {
                width: 60%;
            }

            .section2 {
                width: 40%;
            }

            /* Adjust badge and text sizes for medium-sized screens */

            .badge {
                font-size: 60px;
            }

            span {
                font-size: 16px;
            }

            .mdln {
                font-size: 45px;
            }

            .mdln- {
                font-size: 52px;
            }

            .imagesHeader {
                max-width: 138%;
                height: auto;
                display: block;
                margin-top: 450px;
                margin-left: -550px;
            }

            .imgHeader_2 {
                max-width: 70%;
                height: auto;
            }

            .tblContent {
                margin-left: -130px;
                margin-top: -50px;
                width: 80%;
            }

            .BtnModulTitle {
                width: 185px;
                height: auto;
                pointer-events: none;
                margin-left: -220px;
                margin-bottom: 30px;
            }

            .imgPembelajaran {
                width: 10vw;
                height: auto;
                margin-left: -50px
            }

            .titlePembelajaran {
                font-size: 18px;
            }

            .deskripsiPembelajaran {
                font-size: 13px;
            }

            .BtnModulTitle {
                width: 185px;
                height: 50px;
                pointer-events: none;
                margin-left: -220px
            }

            .modulTitle {
                font-size: 20px;
            }

            .BtnModulTitle_FAQ {
                width: 114px;
                height: 50px;
                pointer-events: none;
                margin-left: 15px
            }

            .modulTitle_FAQ {
                font-size: 20px;
            }

            .accordion-body p {
                font-size: 14px;
                text-align: left;
            }
        }

        /* Penyesuaian untuk tampilan sedang */
        @media screen and (min-width: 820px) and (max-width: 1180px) and (orientation: landscape) {

            /* Adjust widths for medium-sized screens */
            .section1 {
                width: 60%;
            }

            .section2 {
                width: 40%;
            }

            /* Adjust badge and text sizes for medium-sized screens */

            .badge {
                font-size: 60px;
            }

            span {
                font-size: 16px;
            }

            .mdln {
                font-size: 45px;
            }

            .mdln- {
                font-size: 52px;
            }

            .imagesHeader {
                max-width: 120%;
                height: auto;
                display: block;
                margin-top: 200px;
                margin-left: -150px;
            }

            .imgHeader_2 {
                max-width: 100%;
                height: auto;
            }

            .tblContent {
                margin-left: 50px;
                margin-top: -50px;
                width: 100%;
            }

            .imgPembelajaran {
                width: 8vw;
                height: auto;
                margin-left: -50px
            }

            .titlePembelajaran {
                font-size: 20px;
            }

            .deskripsiPembelajaran {
                font-size: 15px;
            }

            .BtnModulTitle {
                width: 195px;
                height: 50px;
                pointer-events: none;
                margin-left: -40px;
                margin-bottom: 25px;

            }

            .modulTitle {
                font-size: 25px;
            }

            .BtnModulTitle_FAQ {
                width: 114px;
                height: 50px;
                pointer-events: none;
                margin-left: 15px
            }

            .modulTitle_FAQ {
                font-size: 20px;
            }

            .accordion-body p {
                font-size: 14px;
                text-align: left;
            }
        }

        /* Penyesuaian untuk tampilan besar */
        @media screen and (min-width: 1440px) {

            /* Adjust container width for large screens */
            .container {
                width: 800px;
                margin-left: auto;
                margin-right: auto;
            }

            .welcomeText {
                font-size: 48px;
            }

            .mdln {
                font-size: 98px;
            }

            .mdln- {
                font-size: 115px;
            }

            span {
                font-size: 35px;
            }

            .btn {
                width: 150px;
                height: 70px;
                font-size: 150px;
            }

            .imagesHeader {
                max-width: 100%;
                height: auto;
                display: block;
                margin-top: 325px;
            }

            .imgPembelajaran {
                width: 8vw;
                height: auto;
                margin-left: -15px
            }

            .tblContent {
                margin-left: 50px;
            }

            .titlePembelajaran {
                font-size: 48px;
                margin-left: 20px
            }

            .deskripsiPembelajaran {
                font-size: 30px;
                margin-left: 20px
            }

            .BtnModulTitle {
                width: 585px;
                height: 100px;
                pointer-events: none;
            }

            .modulTitle {
                font-size: 60px;
            }

            .BtnModulTitle_FAQ {
                width: 320px;
                height: 110px;
                pointer-events: none;
            }

            .modulTitle_FAQ {
                font-size: 70px;
            }

            .fa_Text {
                font-size: 20px;
            }

            .accordion-body p {
                font-size: 24px;
                text-align: left;
            }
        }
    </style>
    @endpush


    <div class="container w-100" data-aos="flip-right">
    </div>
    {{-- HEADER SECTION --}}
    <div style="text-align: center; background-color: #F5F5F5; min-height:100%" class="sectionA">
        <div class="row">
            <div class="section1 col-md-6 col-12 col-lg-6" style="background: #F5F5F5; text-align: left; padding: 4vw;">
                <br><br><br>
                <img style="max-width: 30%; height: auto; margin-top:-45px" src="{{ URL::to('/') }}/ic_toga.png" alt="Illustration of the evolution of development by octocats" class="home-footer-illustration position-relative z-1 width-full d-block events-none">
                <h3 class="welcomeText"><b>Selamat Datang di</b></h3>
                <h1 class="mdln"><b>Modernland</b>
                    <span class="badge bg-danger md-2 lg-10" style="color: white; "><b> Learning</b></span>
                </h1>
                <h1 class="mdln-"><b><span class="badge bg-danger md-2 lg-10" style="color: white; ">Management System</span></b></h1>
                <span>
                    Metode pendidikan yang menggunakan teknnologi digital untuk memberikan
                    akses fleksibel dan mandiri kepada karyawan untuk melakukan pelatihan atau
                    pembelajaran melalui platform online
                </span>
                <br><br><br>
                <button type="button" class="btn btn-danger" href="{{ url('login') }}">
                    <b>Get Started</b>
                </button>
            </div>
            <div class="section2 col-md-6 col-12 col-lg-6" style="background: #F5F5F5;">
                <img class="imagesHeader" src="{{ URL::to('/') }}/headerIMG.png" alt="Illustration of the evolution of development by octocats" class="home-footer-illustration position-relative z-1 width-full d-block events-none">
            </div>
        </div>
    </div>

    {{-- PEMBELAJARAN SECTION --}}
    <div class="container-fluid row h4-container">
        <div class="col-md-6 center ">
            <br><br>
            <img width="100%" src="{{ URL::to('/') }}/headerIMG_2.png" alt="Illustration of the evolution of development by octocats" class="home-footer-illustration position-relative z-1 width-full d-block events-none imgHeader_2">
        </div>
        <div class="col-md-6" data-aos="flip-left">
            <br><br>
            <div>
                <div class="row" style="margin-left: 50px">
                    <div class="col-2">
                        <button class="btn btn-dark BtnModulTitle">
                            <span class="modulTitle"><b>Pembelajaran</b></span>
                        </button>
                        <br><br>
                    </div>
                </div>
                <br>
                <table class="tblContent">

                    <tr>
                        <td>
                            <div class="col-2">
                                <img src="{{ URL::to('/') }}/ic_pelatihan.png" alt="Illustration of the evolution of development by octocats" class="imgPembelajaran home-footer-illustration position-relative z-1 width-full d-block events-none">
                            </div>
                        </td>
                        <td>
                            <div class="col-10">
                                <h4 class="titlePembelajaran"><b>Pelatihan</b></h4>
                                <p class="deskripsiPembelajaran">Materi pelatihan disajikan dalam bentuk yang menarik dengan elemen interaktif.</p>
                            </div>
                        </td>

                    </tr>

                    <tr>
                        <td>
                            <div class="col-2">
                                <img src="{{ URL::to('/') }}/ic_evaluasi.png" alt="Illustration of the evolution of development by octocats" class="imgPembelajaran home-footer-illustration position-relative z-1 width-full d-block events-none">
                            </div>
                        </td>
                        <td>
                            <div class="col-10">
                                <h4 class="titlePembelajaran"><b>Ujian dan Evaluasi</b></h4>
                                <p class="deskripsiPembelajaran">
                                    Dapat mengukur pemahaman karyawan melalui ujian dan evaluasi secara online.
                                </p>
                            </div>
                        </td>

                    </tr>

                    <tr>
                        <td>
                            <div class="col-2">
                                <img src="{{ URL::to('/') }}/ic_leaderboard.png" alt="Illustration of the evolution of development by octocats" class="imgPembelajaran home-footer-illustration position-relative z-1 width-full d-block events-none">
                            </div>
                        </td>
                        <td>
                            <div class="col-10">
                                <h4 class="titlePembelajaran"><b>Leaderboard</b></h4>
                                <p class="deskripsiPembelajaran">Menghadirkan elemen kompetisi dan motivasi tambahan dalam proses pembelajaran online.</p>
                            </div>
                        </td>

                    </tr>

                    <tr>
                        <td>
                            <div class="col-2">
                                <img src="{{ URL::to('/') }}/ic_video.png" alt="Illustration of the evolution of development by octocats" class="imgPembelajaran home-footer-illustration position-relative z-1 width-full d-block events-none">
                            </div>
                        </td>
                        <td>
                            <div class="col-10">
                                <h4 class="titlePembelajaran"><b>Video Pembelajaran</b></h4>
                                <p class="deskripsiPembelajaran">Pembelajaran berbasis video dalam membantu menyampaikan informasi dengan cara yang lebih menarik.</p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <hr>

    {{-- FAQ SECTION --}}
    <div class="container-fluid row h4-container">
        <div class="col-md-6" data-aos="flip-left">
            <br><br>
            <button class="btn btn-dark BtnModulTitle_FAQ">
                <span class="modulTitle_FAQ"><b>FAQ</b></span>
            </button>
            <br><br>
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingOne">
                        <button class="accordion-button collapsed fa_Text" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            <span>Apa itu Modernland Learning?</span>
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <p>
                                Modernland Learning adalah platform pembelajaran digital yang memungkinkan karyawan untuk mengakses materi pelatihan dan kursus secara online.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingTwo">
                        <button class="accordion-button collapsed fa_Text" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                            <span>Bagaimana cara saya mengakses Modernland Learning?</span>
                        </button>
                    </h2>
                    <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <p>
                                Setelah login ke platform, anda dapat mengaksesnya dari perangkat apa pun dengan koneksi internet.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingThree">
                        <button class="accordion-button collapsed fa_Text" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                            <span>Apakah ada batas waktu untuk menyelesaikan kursus?</span>
                        </button>
                    </h2>
                    <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <p>
                                Beberapa course mungkin memiliki batas waktu. Namun, sebagian besar dapat diakses sesuai dengan kebutuhan dan kenyamanan Anda.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingFour">
                        <button class="accordion-button collapsed fa_Text" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                            <span>Apa manfaat menggunakan Modernland Learning dalam pengembangan karyawan?</span>
                        </button>
                    </h2>
                    <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <p>
                                Modernland Learning memungkinkan pengembangan mandiri, akses fleksibel, dan meningkatkan efisiensi waktu pembelajaran.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingFive">
                        <button class="accordion-button collapsed fa_Text" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                            <span>Apakah hasil pembelajaran saya dipantau?</span>
                        </button>
                    </h2>
                    <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <p>
                                Ya, kemajuan Anda dipantau.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingSix">
                        <button class="accordion-button collapsed fa_Text" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                            <span>Apakah materi pembelajaran dapat diakses secara offline?</span>
                        </button>
                    </h2>
                    <div id="flush-collapseSix" class="accordion-collapse collapse" aria-labelledby="flush-headingSix" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <p>
                                Saat ini, materi hanya dapat diakses secara online. Namun materi yang ada di dalam kelas dapat diunduh.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingSeven">
                        <button class="accordion-button collapsed fa_Text" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false" aria-controls="flush-collapseSeven">
                            <span>Apakah ada ujian atau tugas dalam setiap kursus?</span>
                        </button>
                    </h2>
                    <div id="flush-collapseSeven" class="accordion-collapse collapse" aria-labelledby="flush-headingSeven" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <p>
                                Setiap kursus mungkin memiliki elemen evaluasi seperti pre-test, post-test, maupun quiz untuk mengukur pemahaman Anda.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingEight">
                        <button class="accordion-button collapsed fa_Text" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseEight" aria-expanded="false" aria-controls="flush-collapseEight">
                            <span>Apakah Modernland Learning memiliki sumber daya pendukung, seperti referensi tambahan?</span>
                        </button>
                    </h2>
                    <div id="flush-collapseEight" class="accordion-collapse collapse" aria-labelledby="flush-headingEight" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <p>
                                Ya, Modernland Learning menyediakan sumber daya tambahan seperti bacaan, video, dan tautan untuk memperkaya pembelajaran Anda.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingNine">
                        <button class="accordion-button collapsed fa_Text" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseNine" aria-expanded="false" aria-controls="flush-collapseNine">
                            <span>Apakah Leaderboard tersedia di Modernland Learning?</span>
                        </button>
                    </h2>
                    <div id="flush-collapseNine" class="accordion-collapse collapse" aria-labelledby="flush-headingNine" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <p>
                                Ya, leaderboard tersedia di Modernland Learning.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 center ">
            <br><br>
            <img width="100%" src="{{ URL::to('/') }}/headerIMG_3.png" alt="Illustration of the evolution of development by octocats" class="home-footer-illustration position-relative z-1 width-full d-block events-none">
        </div>
    </div>

    <script>
        console.log("%c 💻 Modernland.com is Hiring: %chttps://hc.modernland.co.id/karir/module/display/%c ", "background:#000;color:#0f0;font-family:Lucida console;font-size:20px;letter-spacing:-1px;display:block;padding:5px;box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255,255,255,0.4) inset;animation:blink 1s infinite;", "color:#0f0;text-decoration:underline;", "@keyframes blink{0%,100%{opacity:1}50%{opacity:0}}");
    </script>

    @endsection