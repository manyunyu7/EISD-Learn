    @extends('template')

    @section('styling')

    @endsection
    @section('body')

@push('InternalStyle')
    <style>
        /* Gaya CSS */
        .h4-container {
            margin-top: 2%; /* Atur margin top untuk ruang di atas h4 */
            margin-bottom: 2%; /* Atur margin bottom untuk ruang di bawah h4 */
        }

        /* Contoh tag h4 yang menggunakan kelas di atas */
        .h4-container h4 {
            margin-bottom: 0.5rem; /* Atur margin bottom untuk ruang di bawah setiap h4 */
        }
        .headerText {
            font-size: 28px; /* Atur ukuran teks sesuai keinginan Anda */
        }

    </style>
@endpush
    

    <div class="container w-100" data-aos="flip-right" >
    </div>
    <div style="text-align: center; background-color: #F5F5F5; min-height:100%" class="section1">
        <div class="row">
            <div class="col" style="text-align: left; margin: 35px; ">
                <br><br><br>
                <img style="width: 200px; height:175px;" src="{{ URL::to('/') }}/ic_toga.png" alt="Illustration of the evolution of development by octocats" class="home-footer-illustration position-relative z-1 width-full d-block events-none">
                <h3 style="font-size: 50px;"><b>Selamat Datang di</b></h3>
                <h1 style="font-size: 100px;"><b>Modernland</b>
                <span class="badge bg-danger" style="color: white;"><b> Learning</b></span></h1>
                <h1><b><span class="badge bg-danger" style="color: white; font-size: 88px;">Management System</span></b></h1>
                <span class="headerText">
                    Metode pendidikan yang menggunakan teknnologi digital untuk memberikan 
                    akses fleksibel dan mandiri kepada karyawan untuk melakukan pelatihan atau 
                    pembelajaran melalui platform online
                </span>
                <br><br><br>

                <button type="button" class="btn btn-danger" 
                    style="width: 130px; height:50px;" 
                    href="{{ url('login') }}">
                    <b>Get Started</b>
                </button>
            </div>
            <div class="col">
                <br><br><br><br><br><br><br><br>
                {{-- <p class="gloss mb-0"  style="font-family: 'Bebas Neue', sans-serif !important; color: #BB0014 !important; background-color: #F5F5F5;">{{ config('app.name') }}</p> --}}
                <img style="width: 1430px; height: 1000px;" src="{{ URL::to('/') }}/headerIMG.png" alt="Illustration of the evolution of development by octocats" class="home-footer-illustration position-relative z-1 width-full d-block events-none">
            </div>
        </div>
    </div>

    <div class="container-fluid row h4-container" >
        <div class="col-md-6 center ">
            <br><br>
            <img width="100%" src="{{ URL::to('/') }}/headerIMG_2.png" alt="Illustration of the evolution of development by octocats" class="home-footer-illustration position-relative z-1 width-full d-block events-none">
        </div>
        <div class="col-md-6" data-aos="flip-left">
            <br><br>
            <h1 style="margin-left: 50px"><span class="badge bg-dark" style="color: white;  height:65px; text-align: center;"><b> Pembelajaran</b></span></h1>
            <br>

            <div class="row" style="margin-left: 50px">
                <div class="col-2">
                    <img style="width: 100px; height:75px;" src="{{ URL::to('/') }}/ic_pelatihan.png" alt="Illustration of the evolution of development by octocats" class="home-footer-illustration position-relative z-1 width-full d-block events-none">
                </div>
                <div class="col-10">
                    <h4><b>Pelatihan</b></h4>
                    Materi pelatihan disajikan dalam bentuk yang menarik dengan elemen interaktif.
                </div>
            </div>
            <br>
            <div class="row" style="margin-left: 50px">
                <div class="col-2">
                    <img style="width: 100px; height:75px;" src="{{ URL::to('/') }}/ic_evaluasi.png" alt="Illustration of the evolution of development by octocats" class="home-footer-illustration position-relative z-1 width-full d-block events-none">
                </div>
                <div class="col-10">
                    <h4><b>Ujian dan Evaluasi</b></h4>
                    Dapat mengukur pemahaman karyawan melalui ujian dan evaluasi secara online.
                </div>
            </div>
            <br>
            <div class="row" style="margin-left: 50px">
                <div class="col-2">
                    <img style="width: 100px; height: 100px;" src="{{ URL::to('/') }}/ic_leaderboard.png" alt="Illustration of the evolution of development by octocats" class="home-footer-illustration position-relative z-1 width-full d-block events-none">
                </div>
                <div class="col-10">
                    <h4><b>Leaderboard</b></h4>
                    Menghadirkan elemen kompetisi dan motivasi tambahan dalam proses pembelajaran online.
                </div>
            </div>
            <br>
            <div class="row" style="margin-left: 50px">
                <div class="col-2">
                    <img style="width: 110px; height: 90px;" src="{{ URL::to('/') }}/ic_video.png" alt="Illustration of the evolution of development by octocats" class="home-footer-illustration position-relative z-1 width-full d-block events-none">
                </div>
                <div class="col-10">
                    <h4><b>Video Pembelajaran</b></h4>
                    Pembelajaran berbasis video dalam membantu menyampaikan informasi dengan cara yang lebih menarik.
                </div>
            </div>
        </div>
    </div>
    <hr>

{{-- BATAS --}}

    <div class="container-fluid row h4-container" >
        <div class="col-md-6" data-aos="flip-left">
            <br><br>
            <h1 style="margin-left: 50px"><span class="badge bg-dark" style="color: white;  height:65px; text-align: center;"><b> Pembelajaran</b></span></h1>
            <br>
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Accordion Item #1
                    </button>
                  </h2>
                  <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                      <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                    </div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      Accordion Item #2
                    </button>
                  </h2>
                  <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                      <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                    </div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                      Accordion Item #3
                    </button>
                  </h2>
                  <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                      <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
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

    <div class="container-fluid jargon jargon-6">
        <div class=" row">
            <div class="col-md-6 d-flex justify-content-center center" data-aos="zoom-in-up">
                <div class="ml-3">
                    <div>
                        <h2>Persiapan Kerja
                            Jadi Lebih Optimal</h2>
                    </div>
                    <div>
                        <p>{{ config('app.name') }} mempelajari corporate culture, keahlian kerja, kompetensi yang dibutuhkan di Modernland</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <lottie-player src="https://lottie.host/de4087d3-35d5-41fb-9af5-1bc46fce0dc9/g0rmfNzz8R.json" mode="bounce" background="TRANSPARENT" speed="1" style="width: 100%; height:300px;" hover loop autoplay>
                </lottie-player>
            </div>
        </div>
    </div>

    <div>
        <hr>
    </div>



    {{-- <div class="row justify-content-center align-items-center">
            <div class="col-md-3 pl-md-0 " data-aos="flip-left">
                <div class="card-pricing2 card-success">
                    <div class="pricing-header">
                        <h3 class="fw-bold">1 Bulan</h3>
                        <span class="sub-title">Paket Coba-Coba</span>
                    </div>
                    <div class="price-value">
                        <div class="value">
                            <span class="currency">Rp.</span>
                            <span class="amount">75<span></span></span>
                            <span class="month">Ribu</span>
                        </div>
                    </div>
                    <ul class="pricing-content">
                        <li>50GB Disk Space</li>
                    </ul>
                    <a href="#" class="btn btn-success btn-border btn-lg w-75 fw-bold mb-3">Daftar</a>
                </div>
            </div>
            <div class="col-md-3 pl-md-0 pr-md-0" data-aos="flip-right" data-aos-delay="500">
                <div class="card-pricing2 card-primary">
                    <div class="pricing-header">
                        <h3 class="fw-bold">3 Bulan</h3>
                        <span class="sub-title">Short Intercourse</span>
                    </div>
                    <div class="price-value">
                        <div class="value">
                            <span class="currency">Rp.</span>
                            <span class="amount">150<span></span></span>
                            <span class="month">Ribu</span>
                        </div>
                    </div>
                    <ul class="pricing-content">
                        <li>60GB Disk Space</li>
                    </ul>
                    <a href="#" class="btn btn-primary btn-border btn-lg w-75 fw-bold mb-3">Daftar</a>
                </div>
            </div>
            <div class="col-md-3 pr-md-0" data-aos="flip-right" data-aos-delay="1000">
                <div class="card-pricing2 card-secondary">
                    <div class="pricing-header">
                        <h3 class="fw-bold">1 Tahun</h3>
                        <span class="sub-title">Bootcamp</span>
                    </div>
                    <div class="price-value">
                        <div class="value">
                            <span class="currency">Rp.</span>
                            <span class="amount">750<span></span></span>
                            <span class="month">Ribu</span>
                        </div>
                    </div>
                    <ul class="pricing-content">
                        <li>70GB Disk Space</li>
                    </ul>
                    <a href="#" class="btn btn-secondary btn-border btn-lg w-75 fw-bold mb-3">Daftar</a>
                </div>
            </div>

        </div> --}}

    {{-- <div class="container-fluid">
            <hr>
        </div> --}}


    <div class="container-fluid jargon jargon-path row">
        <div class="col-md-12 text-center" data-aos="flip-right">
            <br>
            <h1 class="center">Belajar Instant Berkualitas</h1>
            <p class="center"> Dengan 3 Langkah Mudah</p>
        </div>

        <div class="col-md-4 center text-center" data-aos="bounce" data-aos-duration="1500">
            <lottie-player class="center" src="https://assets1.lottiefiles.com/packages/lf20_jcikwtux.json" background="transparent" speed="1" style="width: 200px; height: 200px;" loop autoplay>
            </lottie-player>
            <br><br><br>
            <h4>Daftar</h4>
        </div>

        <div class="col-md-4 center text-center" data-aos="bounce" data-aos-duration="1500">
            <lottie-player class="center" src="https://assets9.lottiefiles.com/packages/lf20_e9kjkvml.json" background="transparent" speed="3" style="width: 200px; height: 200px;" loop autoplay>
            </lottie-player>
            <br><br><br>
            <h4>Belajar</h4>
        </div>



        <div class="col-md-4 center text-center" data-aos="bounce" data-aos-duration="1500">
            <lottie-player class="center" src="https://assets10.lottiefiles.com/packages/lf20_n7DAEZ.json" background="transparent" speed="1" style="width: 200px; height: 200px;" loop autoplay>
            </lottie-player>
            <br><br><br>
            <h4>Evaluasi</h4>
        </div>
    </div>

    <!-- <div>
        <hr>
    </div> -->

    <div class="container center jargon">

        <div style="margin-top: 50px;" class="text-center mb-4" data-aos="zoom-in-up">
            <!-- <h1>Team</h1> -->
            <img src=" {{URL::to('/')}}/home_assets/img/esd_3.png" alt="" style="width: 280px; ">
            <p>Developed by : MDLN IT & HR</p>
        </div>


        <div class="container-fluid row">
            <!-- <div class="col-md-12 col-lg-4 col-12 card ">
                <div class="card-body">
                    <div class="text-center">
                        <img class="mb-4 text-center" width="150px" height="150px" src="{{ URL::to('/') }}/home_assets/img/lecturer1.png" alt="">
                    </div>
                    <h4 class="mt-2 text-center card-title">Rahmat Fauzi, M.T</h4>
                    <p class="card-text text-center">Pembina Lab Riset EISD & Dosen Pengampu Mata Kuliah WAD 2020</p>
                    <p class="card-text text-center">- Pembina Proyek Mata Kuliah WAD - </p>
                </div>
            </div> -->
            <!--
            <div class="col-md-12 col-lg-4 col-12 card ">
                <div class="card-body">
                    <div class="text-center">
                        <img class="mb-4 text-center" width="150px" height="150px" src="{{ URL::to('/') }}/home_assets/img/student_henry.png" alt="">
                    </div>
                    <h4 class="mt-2 text-center card-title">Henry Augusta Harsono</h4>
                    <p class="card-text text-center">Asisten Laboratorium Riset EISD & Sistem Informasi Telkom University 2018</p>
                    <p class="card-text text-center"><a href="https://github.com/henryaugusta">https://github.com/henryaugusta</a>
                    </p>
                </div>
            </div> -->

            <!-- <div class="col-md-12 col-lg-4 col-12 card ">
                <div class="card-body">
                    <div class="text-center">
                        <img class="mb-4 text-center" width="150px" height="150px" src="{{ URL::to('/') }}/home_assets/img/firriezky.png" alt="">
                    </div>
                    <h4 class="mt-2 text-center card-title">Muhammad Firriezky</h4>
                    <p class="card-text text-center">Mahasiswa Sistem Informasi Telkom University 2018</p>
                    <p class="card-text text-center"><a href="https://github.com/firriezky">https://github.com/firriezky</a>
                    </p>
                </div>
            </div> -->
        </div>
    </div>


    <div>
        <hr>
    </div>


    <div class="container-fluid jargon jargon-client mt-5">
        {{-- <div class="center">
                <h3 style="text-align: center;">Member Kami Dari Berbagai Kampus dan Perusahaan</h3>
            </div>
            <div class="d-flex justify-content-center mb-5">
                <img class="center img-fluid" src="./home_assets/img/members-campus-companies.png" alt="">
            </div> --}}

    </div>

    <div class=" container-fluid jargon jargon-final bg">
        <br><br><br>
        <div class="d-flex justify-content-center">
            <img class="" src="./home_assets/img/members.png" alt="" height="60px">
        </div>
        <div class=" center text-center">
            <h3 style="text-align: center;">"Mari Bergabung Dengan Ratusan User Lainnya di
                {{ config('app.name') }}"
            </h3>
    {{--        <p>Tidak perlu download / kartu kredit. Cukup daftar langsung belajar.</p>--}}
            <p>Creating Forward</p>
            <br><br>
    {{--        <button type="button" name="" id="" class="btn btn-primary btn-lg btn-block">Uji Coba Gratis</button>--}}
            <br><br>
        </div>
    </div>


        <script>
            console.log("%c 💻 Modernland.com is Hiring: %chttps://careers.blibli.com%c ", "background:#000;color:#0f0;font-family:Lucida console;font-size:20px;letter-spacing:-1px;display:block;padding:5px;box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255,255,255,0.4) inset;animation:blink 1s infinite;", "color:#0f0;text-decoration:underline;", "@keyframes blink{0%,100%{opacity:1}50%{opacity:0}}");
            console.log("%c 💻 Modernland.com is Hiring: %chttps://careers.blibli.com%c ", "background:#000;color:#0f0;font-family:Lucida console;font-size:20px;letter-spacing:-1px;display:block;padding:5px;box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255,255,255,0.4) inset;animation:blink 1s infinite;", "color:#0f0;text-decoration:underline;", "@keyframes blink{0%,100%{opacity:1}50%{opacity:0}}");
            console.log("%c 💻 Modernland.com is Hiring: %chttps://careers.blibli.com%c ", "background:#000;color:#0f0;font-family:Lucida console;font-size:20px;letter-spacing:-1px;display:block;padding:5px;box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255,255,255,0.4) inset;animation:blink 1s infinite;", "color:#0f0;text-decoration:underline;", "@keyframes blink{0%,100%{opacity:1}50%{opacity:0}}");
            console.log("%c 💻 Modernland.com is Hiring: %chttps://careers.blibli.com%c ", "background:#000;color:#0f0;font-family:Lucida console;font-size:20px;letter-spacing:-1px;display:block;padding:5px;box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255,255,255,0.4) inset;animation:blink 1s infinite;", "color:#0f0;text-decoration:underline;", "@keyframes blink{0%,100%{opacity:1}50%{opacity:0}}");
            console.log("%c 💻 Modernland.com is Hiring: %chttps://careers.blibli.com%c ", "background:#000;color:#0f0;font-family:Lucida console;font-size:20px;letter-spacing:-1px;display:block;padding:5px;box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255,255,255,0.4) inset;animation:blink 1s infinite;", "color:#0f0;text-decoration:underline;", "@keyframes blink{0%,100%{opacity:1}50%{opacity:0}}");
            console.log("%c 💻 Modernland.com is Hiring: %chttps://careers.blibli.com%c ", "background:#000;color:#0f0;font-family:Lucida console;font-size:20px;letter-spacing:-1px;display:block;padding:5px;box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255,255,255,0.4) inset;animation:blink 1s infinite;", "color:#0f0;text-decoration:underline;", "@keyframes blink{0%,100%{opacity:1}50%{opacity:0}}");
        </script>

    @endsection
