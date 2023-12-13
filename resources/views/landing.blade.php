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
    {{-- HEADER SECTION --}}
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

    {{-- PEMBELAJARAN SECTION --}}
    <div class="container-fluid row h4-container" >
        <div class="col-md-6 center ">
            <br><br>
            <img width="100%" src="{{ URL::to('/') }}/headerIMG_2.png" alt="Illustration of the evolution of development by octocats" class="home-footer-illustration position-relative z-1 width-full d-block events-none">
        </div>
        <div class="col-md-6" data-aos="flip-left">
            <br><br>
            {{-- <h1 style="margin-left: 50px"><span class="badge bg-dark" style="color: white;  height:65px; text-align: center;"><b> Pembelajaran</b></span></h1> --}}
            

            <div class="row" style="margin-left: 50px">
                <div class="col-2">
                    <button type="label" class="btn btn-dark" 
                        style="width: 285px; height:56px; font-size:30px; pointer-events: none;">
                        <b>Pembelajaran</b>
                    </button>
                    <br><br>
                </div>
                <div class="col-10">
                </div>
            </div>
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

    {{-- FAQ SECTION --}}
    <div class="container-fluid row h4-container" >
        <div class="col-md-6" data-aos="flip-left">
            <br><br>
            {{-- <h1 style="margin-left: 50px"><span class="badge bg-dark" style="color: white; width: 114px; height:56px; font-size: 30px;"><b> FAQ</b></span></h1> --}}
            <button type="label" class="btn btn-dark" 
                    style="width: 114px; height:56px; font-size:30px; pointer-events: none;">
                    <b>FAQ</b>
            </button>
            <br><br>
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

    <script>
        console.log("%c 💻 Modernland.com is Hiring: %chttps://careers.blibli.com%c ", "background:#000;color:#0f0;font-family:Lucida console;font-size:20px;letter-spacing:-1px;display:block;padding:5px;box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255,255,255,0.4) inset;animation:blink 1s infinite;", "color:#0f0;text-decoration:underline;", "@keyframes blink{0%,100%{opacity:1}50%{opacity:0}}");
        console.log("%c 💻 Modernland.com is Hiring: %chttps://careers.blibli.com%c ", "background:#000;color:#0f0;font-family:Lucida console;font-size:20px;letter-spacing:-1px;display:block;padding:5px;box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255,255,255,0.4) inset;animation:blink 1s infinite;", "color:#0f0;text-decoration:underline;", "@keyframes blink{0%,100%{opacity:1}50%{opacity:0}}");
        console.log("%c 💻 Modernland.com is Hiring: %chttps://careers.blibli.com%c ", "background:#000;color:#0f0;font-family:Lucida console;font-size:20px;letter-spacing:-1px;display:block;padding:5px;box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255,255,255,0.4) inset;animation:blink 1s infinite;", "color:#0f0;text-decoration:underline;", "@keyframes blink{0%,100%{opacity:1}50%{opacity:0}}");
        console.log("%c 💻 Modernland.com is Hiring: %chttps://careers.blibli.com%c ", "background:#000;color:#0f0;font-family:Lucida console;font-size:20px;letter-spacing:-1px;display:block;padding:5px;box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255,255,255,0.4) inset;animation:blink 1s infinite;", "color:#0f0;text-decoration:underline;", "@keyframes blink{0%,100%{opacity:1}50%{opacity:0}}");
        console.log("%c 💻 Modernland.com is Hiring: %chttps://careers.blibli.com%c ", "background:#000;color:#0f0;font-family:Lucida console;font-size:20px;letter-spacing:-1px;display:block;padding:5px;box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255,255,255,0.4) inset;animation:blink 1s infinite;", "color:#0f0;text-decoration:underline;", "@keyframes blink{0%,100%{opacity:1}50%{opacity:0}}");
        console.log("%c 💻 Modernland.com is Hiring: %chttps://careers.blibli.com%c ", "background:#000;color:#0f0;font-family:Lucida console;font-size:20px;letter-spacing:-1px;display:block;padding:5px;box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255,255,255,0.4) inset;animation:blink 1s infinite;", "color:#0f0;text-decoration:underline;", "@keyframes blink{0%,100%{opacity:1}50%{opacity:0}}");
    </script>

    @endsection
