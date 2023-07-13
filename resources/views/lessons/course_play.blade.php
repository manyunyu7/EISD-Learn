@extends('lessons._template')
@section('head-section')
<style>
    #previewCover {
        object-fit: cover;
        height: 200px;
        width: 100%;
    }

    .video-mask {
        border-radius: 20px;
        overflow: hidden;
    }

    .course_section_name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }

    .card img {
        max-width: 100%;
        height: auto;
    }

    @media (max-width: 576px) {

        /* Adjustments for small screens (e.g., mobile devices) */
        .card img {
            /* Add any additional styles for small screens */
        }
    }
</style>
<link rel="stylesheet" href="{{URL::to('/')}}/library/feylabs-video-css.css" />
<script src="https://cdn.plyr.io/3.6.3/demo.js" crossorigin="anonymous"></script>
<script src="{{ asset('library/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('main')

<div class="container-fluid">
    <div class="main-content-container container-fluid px-4 mt-5">

        {{-- @include('blog.breadcumb') --}}
        @forelse ($section_spec as $index => $sectionSpec)

        <!-- Page Header -->
        <div class="page-header row no-gutters mb-4">
            <div class="col-12 col-sm-12 text-center text-sm-left mb-0">
                <h2 class="text-uppercase">Kelas {{ $lesson->course_title }} </h2>
                <h3 class="page-title">Materi Ke : {{ $sectionSpec->section_order }}</h3>
                <h4 class="page-title">{{ $sectionSpec->section_title }}</h4>
            </div>
        </div>

        <div class="container-fluid">
            <video controls crossorigin playsinline id="play" width="100%" class="video-mask">
                <!-- Video files -->
                <source src="{{ Storage::url('public/class/content/' . $sectionSpec->lesson_id . '/' . $sectionSpec->section_video . '?random1') }}" <!-- Caption files -->
                <!-- <track kind="captions" label="English" srclang="en" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.en.vtt" default /> -->
                <!-- <track kind="captions" label="Français" srclang="fr" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.fr.vtt" /> -->

                <!-- Fallback for browsers that don't support the <video> element -->
                <!-- <a href="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4" download>Download</a> -->
            </video>
        </div>

        <div class="card mt-5">
            <img class="card-img-top" src="holder.js/100x180/" alt="">
            <div class="card-body">
                <h4 class="card-title">{{ $lesson->course_title }}</h4>
                <p class="card-text">Materi Ke : {{ $sectionSpec->section_order }}</p>
                {!! $sectionSpec->section_content !!}
            </div>
        </div>
        @empty

        @endforelse

    </div>
</div>

@endsection