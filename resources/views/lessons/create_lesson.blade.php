@extends('main.template')

@section('head-section')
    <style>
        #previewCover {
            object-fit: cover;
            height: 200px;
            width: 100%;
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


    <form action="{{ route('lesson.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid">
            <div class="main-content-container container-fluid px-4 mt-5">

                {{-- @include('blog.breadcumb') --}}


                <!-- Page Header -->
                <div class="page-header row no-gutters mb-4">
                    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                        <span class="text-uppercase page-subtitle">Class</span>
                        <h3 class="page-title">Tambah Kelas Baru</h3>
                    </div>
                </div>


                <!-- End Page Header -->
                <div class="row">
                    {{-- Side Bar --}}
                    <div class="col-lg-4 col-md-12">
                        {{-- Card Preview --}}
                        <div class="card card-post card-round">
                            <img class="card-img-top" id="imgPreview"
                                 src="{{ asset('atlantis/examples') }}/assets/img/blogpost.jpg" alt="Card image cap">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="avatar">
                                        <img src="{{ Storage::url('public/profile/' . Auth::user()->profile_url) }}"
                                             alt="..." class="avatar-img rounded-circle" }}
                                             onerror="this.onerror=null;this.src='http://feylabs.my.id/fm/mdln_asset/learning/default/default_profile.png'">
                                    </div>
                                    <div class="info-post ml-2">
                                        <p class="username">{{ Auth::user()->name }}</p>
                                        <p class="date text-muted">24 Agustus 2000</p>
                                    </div>
                                </div>
                                <div class="separator-solid"></div>
                                <p class="card-category text-info mb-1"><a href="#">Design</a></p>
                                <h3 class="card-title" id="previewName">
                                    <a href="#">
                                        Judul Kelas Anda Ditampilkan Disini
                                    </a>
                                </h3>
                                <p class="card-text">Deksripsi Kelas Akan Ditampilkan Disini</p>
                                <a href="#" class="btn btn-primary btn-rounded btn-sm">Read More</a>
                            </div>
                        </div>


                        <div class='card card-small mb-3'>
                            <div class="card-header border-bottom">
                                <h6 class="m-0">Akses Konten</h6>
                            </div>
                            <div class='card-body container-fluid'>
                                {{--                                <small class="form-text text-muted"><strong>Kosongkan Input Ini</strong> jika kelas ini bisa diakses setiap saat oleh siswa</small>--}}
                                {{--                                <div class="form-group">--}}
                                {{--                                    <label>Tanggal Start</label>--}}
                                {{--                                    <div class="input-group">--}}
                                {{--                                        <input type="datetime-local" class="form-control" name="start_time">--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}

                                {{--                                <div class="form-group">--}}
                                {{--                                    <label>Tanggal Selesai</label>--}}
                                {{--                                    <div class="input-group">--}}
                                {{--                                        <input type="datetime-local" class="form-control" name="end_time">--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}

                                <div class="form-group">
                                    <label>Konten Bisa Diakses ? </label>
                                    <select class="form-control" name="access">
                                        <option value="y">Ya</option>
                                        <option value="n">Tidak</option>
                                    </select>
                                </div>

                            </div>
                        </div>


                        <div class='card card-small mb-3'>
                            <div class="card-header border-bottom">
                                <h6 class="m-0">Link Preview</h6>
                            </div>
                            <div class='card-body container-fluid'>

                                <iframe class="embed-responsive" src="https://www.youtube.com/embed/B6nhmJ-o01Y"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>

                                <div class="form-group">
                                    <input required id="input-video" type="file" onchange="previewVideo()"
                                           class="form-control @error('video') is-invalid @enderror"
                                           name="video" accept="video/*">

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
                                           value="{{ old('title') }}" placeholder="Masukkan Nama Kelas">

                                    <!-- error message untuk title -->
                                    @error('title')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Category</label>

                                    <select class="form-control" name="category_id" id="">
                                        @forelse($categories as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @empty

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


                                        <div class="form-group">
                                            <label class="font-weight-bold">GAMBAR</label>
                                            <input id="input-image" type="file" onchange="previewPhoto()"
                                                   class="form-control @error('image') is-invalid @enderror"
                                                   name="image" accept="image/*">

                                            <!-- error message untuk title -->
                                            @error('image')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>


                                        <div class="form-group">
                                            <label class="font-weight-bold">Deskripsi Kelas</label>
                                            <textarea
                                                class="form-control ckeditor @error('content') is-invalid @enderror"
                                                name="content" rows="5"
                                                placeholder="Masukkan Deskripsi Kelas">{{ old('content') }}</textarea>

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
