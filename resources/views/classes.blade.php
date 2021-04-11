@extends('template')

@section('styling')
    <style>
        .jargon-class-list {
            bottom: 20px !important
        }
        .navbar-custom {
            position: top;
            background-color: #0275d8 !important;
            color: white !important
        }

        .navbar-custom a {
            color: azure !important
        }

        .jargon-header {
            background-color: #0275d8 !important;
        }

        header {
            background-color: #0275d8  !important;
            color: white !important;
        }

        .h4 {
            text-transform: uppercase;
        }

        .album-poster-parent {
            padding: 10px;
            background-color: #e6e6e6 !important;
            display: block;
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            box-shadow: 0 0 3px #070f27;
            transition: all ease 1s;
        }

        .album-poster-parent:hover {
            transform: scale(0.9) translateY(10px);
        }

        .album-poster {
            display: block;
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            transition: all ease 1s;
        }


        .col-md-3,
        .col-md-2 {
            margin-bottom: 50px;
        }

        h3 {
            font-size: 34px;
            margin-bottom: 34px;
            border-bottom: 4px solid #ffffff;
        }


        .fufufu {
            object-fit: cover;
            /* background-position: center center; */
            /* background-repeat: no-repeat; */
            width: 100%;
            height: 250px;
        }

    </style>
@endsection


@section('body')

    <header>
        <div class="container w-100" data-aos="flip-right">
            <img width="100%" src="https://github.githubassets.com/images/modules/site/home/footer-illustration.svg" alt="Illustration of the evolution of development by octocats" class="home-footer-illustration position-relative z-1 width-full d-block events-none">
        </div>
        <p class="gloss mb-0" style="color: #ffffff !important">{{ config('app.name') }}</p>
        <div style="text-align: center">I want more, I want an Online sequence.
            Everything is humble with Online Course.
        </div>
        <div style="text-align: center">You’re in Good Hands with {{ config('app.name') }}</div>
        <hr>
    </header>

    <div class="header-top">

    </div>

    <div class="container-fluid ">
        <h1 style="text-align: center" class="mt-5">Katalog Kelas</h1>
        <hr class="worm">


        <div class="row row-eq-height mt-5 mx-5">
            @forelse ($dayta as $data)
                <div class="col-lg-4 col-sm-6 my-2">
                    <div class="album-poster-parent" style="background-color: white !important">
                        <a href="javascript:void();" class="album-poster" data-switch="0">
                            <img class="fufufu" onerror="this.onerror=null; this.src='./assets/album/n5'" src="{{ Storage::url('public/class/cover/') . $data->course_cover_image }}" alt="La Noyee">
                        </a>
                        <br>
                        <div class="course-info">
                            <h4>{{ $data->course_title }}</h4>

                        </div>
                        <p><span class="badge badge-primary">{{ $data->course_category }}</span></p>
                        <div class="d-flex">
                            <div class="avatar">
                                <img src="{{ Storage::url('public/profile/') . $data->profile_url }}" alt="..." class="avatar-img rounded-circle">
                            </div>
                            <div class="info-post ml-2">
                                <p class="username">{{ $data->mentor_name }}</p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ url("/lesson/$data->id") }}">
                                <button type="submit" class="btn mt-3 btn-outline-primary btn-block mb-2">Lihat Kelas</button>
                            </a>
                        </div>

                    </div>
                </div>

                {{-- <p>{{ $data->mentor_name }}</p> --}}
            @empty

            @endforelse
        </div>

    </div>
    
    <hr class="container-fluid">
@endsection
