@extends('main.template')

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

    </style>
    <script src="{{ asset('library/ckeditor/ckeditor.js') }}"></script>
@endsection


@section('main')

    <script>
        window.onload = function () {
            // jQuery and everything else is loaded
            var el = document.getElementById('input-image');
            el.onchange = function () {
                var fileReader = new FileReader();
                fileReader.readAsDataURL(document.getElementById("input-image").files[0])
                fileReader.onload = function (oFREvent) {
                    document.getElementById("imgPreview").src = oFREvent.target.result;
                };
            }

            $(document).ready(function () {
                $.myfunction = function () {
                    $("#previewName").text($("#inputTitle").val());
                    var title = $.trim($("#inputTitle").val())
                    if (title == "") {
                        $("#previewName").text("Judul")
                    }
                };

                $("#inputTitle").keyup(function () {
                    $.myfunction();
                });

            });
        }

    </script>
    {{-- {{ var_dump($lesson) }} --}}


    <div class="container-fluid">
        <div class="main-content-container container-fluid px-4 mt-5">

            {{-- @include('blog.breadcumb') --}}


            <!-- Page Header -->
            <div class="page-header row no-gutters mb-4">
                <div class="col-12 col-sm-12 text-center text-sm-left mb-0">
                    <span class="text-uppercase page-subtitle">Edit Kelas</span>
                    <h3 class="page-title">Edit Kelas : {{ $lesson->course_title }}</h3>
                </div>
            </div>

            <div class="row my-1">
                <a href="#">
                    <button class="btn btn-primary btn-border btn-round">Manage Materi</button>
                </a>
            </div>
            <form action="{{ route('lesson.update', $lesson->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')


                <!-- End Page Header -->
                <div class="row">
                    {{-- Side Bar --}}
                    <div class="col-lg-4 col-md-12">
                        {{-- Card Preview --}}
                        <div class="card card-post card-round">
                            <img class="card-img-top" id="imgPreview"
                                 src="{{ Storage::url('public/class/cover/') . $lesson->course_cover_image }}"
                                 alt="Cover Kelas">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="avatar">
                                        <img src="{{ Storage::url('public/profile/'). Auth::user()->profile_url }}"
                                             alt="..."
                                             class="avatar-img rounded-circle">
                                    </div>
                                    <div class="info-post ml-2">
                                        <p class="username">{{ Auth::user()->name }}</p>
                                        <p class="date text-muted">20 Jan 18</p>
                                    </div>
                                </div>
                                <div class="separator-solid"></div>
                                {{-- <p class="card-category text-info mb-1"><a
                                        href="#">Design</a></p> --}}
                                <h3 class="card-title" id="previewName">
                                    <a href="#">
                                        {{ $lesson->course_title }}
                                    </a>
                                </h3>
                                <p class="card-text">{!! $lesson->course_description !!} </p>
                                <a href="#" class="btn btn-primary btn-rounded btn-sm">Read More</a>
                            </div>
                        </div>

                        <div class='card card-small mb-3'>
                            <div class="card-header border-bottom">
                                <h6 class="m-0">Akses Konten</h6>
                            </div>
                            <div class='card-body container-fluid'>
{{--                                <small class="form-text text-muted"><strong>Kosongkan Input Ini</strong> jika kelas ini--}}
{{--                                    bisa diakses setiap saat oleh siswa</small>--}}
{{--                                <div class="form-group">--}}
{{--                                    @php--}}
{{--                                    // Convert the database date to the correct format for datetime-local input--}}
{{--                                    if($lesson->start_date!=null){--}}
{{--                                    $start_date = date('Y-m-d\TH:i', strtotime($lesson->start_date));--}}
{{--                                    }--}}
{{--                                    @endphp--}}
{{--                                    <label>Tanggal Start</label>--}}
{{--                                    <div class="input-group">--}}
{{--                                        <input type="datetime-local" class="form-control"--}}
{{--                                               value="{{$lesson->start_date ?? ''}}" name="start_time">--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="form-group">--}}
{{--                                    <label>Tanggal Selesai</label>--}}
{{--                                    <div class="input-group">--}}
{{--                                        @php--}}
{{--                                            // Convert the database date to the correct format for datetime-local input--}}
{{--                                            if($lesson->end_date!=null){--}}
{{--                                                $end_date = date('Y-m-d\TH:i', strtotime($lesson->end_date));--}}
{{--                                            }--}}
{{--                                        @endphp--}}
{{--                                        <input type="datetime-local" value="{{ $end_date ?? '' }}" class="form-control"--}}
{{--                                               name="end_time">--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Konten Bisa Diakses ?</label>
                                    <select class="form-control" name="access">
                                        <option value="y" @if($lesson->can_be_accessed == "y") selected @endif>Ya
                                        </option>
                                        <option value="n" @if($lesson->can_be_accessed == "n") selected @endif>Tidak
                                        </option>
                                    </select>
                                </div>

                            </div>
                        </div>


                        <div class='card card-small mb-3'>
                            <div class="card-header border-bottom">
                                <h6 class="m-0">Link Preview</h6>
                            </div>
                            <div class='card-body container-fluid'>
                                <div class="embed-responsive embed-responsive-16by9 video-mask">
                                    <video loop controls class="embed-responsive-item">
                                        <source
                                            src="{{ Storage::url('public/class/trailer/') . $lesson->course_trailer }}"
                                            type=video/mp4>
                                    </video>
                                </div>

                                <div class="form-group container-fluid">
                                    <input id="input-video" type="file" onchange="previewVideo()"
                                           class="form-control @error('video') is-invalid @enderror" name="video">

                                    <!-- error message untuk video -->
                                    @error('video')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                </div>

                            </div>
                        </div>
                        <!-- End of Side Bar -->
                    </div>
                    <div class="col-lg-8 col-md-12">
                        <!-- Add New Post Form -->
                        <div class="card card-small mb-3">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="font-weight-bold">JUDUL KELAS</label>
                                    <input id="inputTitle" type="text"
                                           class="form-control @error('title') is-invalid @enderror" name="title"
                                           value="{{ old('title', $lesson->course_title) }}"
                                           placeholder="Masukkan Nama Kelas">

                                    <!-- error message untuk title -->
                                    @error('title')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                @php
                                    $category = $lesson->course_category
                                @endphp
                                <div class="form-group">
                                    <label class="form-label">Category</label>
                                    <select class="form-control" name="category_id" id="">
                                        @forelse($categories as $item)
                                            <option value="{{$item->id}}" @if($item->id == $lesson->category_id) selected @endif>
                                                {{$item->name}}
                                            </option>
                                        @empty
                                            <!-- Handle the case where there are no categories -->
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- / Add New Post Form -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card border-0 shadow rounded">
                                    <div class="card-body">

                                        @csrf
                                        <div class="form-group">
                                            <label class="font-weight-bold">GAMBAR</label>
                                            <input id="input-image" type="file" onchange="previewPhoto()"
                                                   class="form-control @error('image') is-invalid @enderror"
                                                   name="image">

                                            <!-- error message untuk title -->
                                            @error('image')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        {!! $lesson->course_description !!}

                                        <div class="form-group">
                                            <label class="font-weight-bold">Deskripsi Kelas</label>
                                            <textarea
                                                class="form-control ckeditor @error('content') is-invalid @enderror"
                                                name="content" rows="5"
                                                placeholder="Masukkan Deskripsi Kelas">{{ old('description', $lesson->course_description) }}</textarea>

                                            <!-- error message untuk content -->
                                            @error('content')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>


                                        <button type="submit" class="btn btn-md btn-primary">SIMPAN</button>
                                        <button type="reset" class="btn btn-md btn-warning">RESET</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
        </div>
    </div>
    </form>
@endsection
