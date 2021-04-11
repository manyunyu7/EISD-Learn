@extends('errors.template')
@section('body_error')
    <div class="row h-100">
        <div class="col-sm-12 my-auto">
            <!-- 404 Error Text -->
            <div class="text-center w-100">
                <!-- 16:9 aspect ratio -->

                <div class="d-flex justify-content-center">
                    <div class="embed-responsive embed-responsive-4by3 video-mask" style="width:300px; height:170px" >
                        <video loop autoplay="autoplay" muted  class="embed-responsive-item">
                            <source src="{{ URL::to('/') }}/401.mp4" type=video/mp4>
                        </video>
                    </div>
                </div>
             

                <div class="error mx-auto" data-text="401">401</div>
                <strong>
                    <h4>
                        <h2>{{ $exception->getMessage() }}</h2>
                    </h4>
                </strong>
                <p class="lead text-gray-800">Anda Tidak Punya Akses ke Halaman Ini</p>
                <a href="{{ URL::previous() }}">&larr; Back to Home</a>
            </div>
        </div>
    </div>
@endsection
