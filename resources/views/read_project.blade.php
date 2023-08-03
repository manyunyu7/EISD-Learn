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
            background-color: #0275d8 !important;
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
            transform: scale(1.15) translateY(10px);
        }

        .img-rounded {
            display: block;
            position: relative;
            overflow: hidden;
            border-radius: 50px;
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
@forelse ($portfolio as $item)
    

    <header>
        <div class="container" data-aos="fade-up">
            <div class="img-rounded">
                <img width="100%" src="{{ Storage::url('public/portfolio/') . $item->image }}">
            </div>
        </div>
        <p  data-aos="flip-down" class="gloss mb-0" style="color: #ffffff !important">{{ $item->title }}</p>
        <div class="card bg-transparent">
            {{-- <img class="card-img-top" src="holder.js/100x180/" alt=""> --}}
            <div class="card-body">
                <h4 class="card-text"  data-aos="fade-up">Ditulis Oleh : </h4>
            
                    <div class="">
                        <div class="avatar"  data-aos="fade-up">
                            <img src="{{ Storage::url('public/profile/') . $item->owner_profile }}" alt="..." class="avatar-img rounded-circle">
                        </div>
                        <div class="info-post ml-2">
                            <div class="card-text"  data-aos="flip-left">{{ $item->owner_name }}</div>
                            <div class="card-text"  data-aos="flip-up">{{ $item->created_at }}</div>
                            <a href="{{ URL::previous() }}">
                                <button type="button" class="btn btn-outline-light mt-2">Kembali Ke Halaman Sebelumnya</button>
                            </a>
                           
                        </div>
                    </div>

            </div>
        </div>
        <div style="text-align: center">
        </div>

    </header>

    <div class="header-top">

    </div>

    <div class="container-fluid"  data-aos="zoom-in-up">

        <hr class="worm">
        <div class="card border-primary">
            <div class="card-body">
                {!! $item->content !!}
            </div>
        </div>




    </div>

    <hr class="container-fluid">
    @empty
    
@endforelse
@endsection
