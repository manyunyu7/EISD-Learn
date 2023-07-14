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
    <link rel="stylesheet" href="{{ URL::to('/') }}/library/feylabs-video-css.css" />
    <script src="https://cdn.plyr.io/3.6.3/demo.js" crossorigin="anonymous"></script>
    <script src="{{ asset('library/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('main')
    @push('custom-scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>

        <script>
            // Wait for the page to load
            document.addEventListener("DOMContentLoaded", function() {
                // Get the video element
                var video = document.getElementById("playVideo168");

                // Get the "Next Lesson" button element
                var nextButton = document.getElementById("nextLessonButton");

                nextButton.addEventListener("click", function(event) {
                    // Calculate the video progress as a percentage
                    var progress = (video.currentTime / video.duration) * 100;

                    if (progress >= 90) {
                        // Allow the default behavior of the button
                        return;
                    } else {
                        // Prevent the default behavior of the button
                        event.preventDefault();
                        // Show an alert informing the user to complete the video first
                        Swal.fire({
                            title: "Video Progress",
                            text: "Pengguna harus menyelesaikan video terlebih dahulu.",
                            icon: "warning",
                            confirmButtonText: "OK",
                        });
                    }
                });

                // Add an event listener to track video progress
                video.addEventListener("timeupdate", function() {
                    // Calculate the video progress as a percentage
                    var progress = (video.currentTime / video.duration) * 100;

                    // Toggle the visibility of the "Next Lesson" button
                    if (progress >= 10) {
                        nextButton.classList.remove("hidden");
                    } else {
                        nextButton.classList.add("hidden");
                    }
                });
            });
        </script>
    @endpush


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
                    <video controls crossorigin playsinline id="playVideo168" width="100%" class="video-mask"
                        disablePictureInPicture controlsList="nodownload">
                        <!-- Video files -->
                        <source
                            src="{{ Storage::url('public/class/content/' . $sectionSpec->lesson_id . '/' . $sectionSpec->section_video . '?random1') }}">
                        <!-- Caption files -->
                        <!-- <track kind="captions" label="English" srclang="en" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.en.vtt" default /> -->
                        <!-- <track kind="captions" label="Français" srclang="fr" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.fr.vtt" /> -->

                        <!-- Fallback for browsers that don't support the <video> element -->
                        <!-- <a href="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4" download>Download</a> -->
                    </video>
                </div>

                <div class="card mt-5">
                    <img class="card-img-top" src="holder.js/100x180/" alt="">
                    <div class="card-body">

                        <div class="d-flex justify-content-between mt-2 mb-4">
                            @if ($prev_section != null)
                                <a href="{{ url('/') . "/course/$courseId/section/$prev_section" }}"
                                    class="btn btn-primary hidden">Previous Lesson</a>
                            @endif
                            @if ($next_section != null)
                                <a href="{{ url('/') . "/course/$courseId/section/$next_section" }}" id="nextLessonButton"
                                    class="btn btn-primary ">Next
                                    Lesson</a>
                            @endif

                        </div>

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
