@extends('main.template')

@section('head-section')
    @include('main.home._styling_home_student')

    <!-- Datatables -->
    <style>
        .max-lines {
            display: block;
            /* or inline-block */
            text-overflow: ellipsis;
            word-wrap: break-word;
            overflow: hidden;
            max-height: 3.6em;
            line-height: 1.8em;
        }

    </style>
    <script src="{{ asset('atlantis/examples') }}/assets/js/plugin/datatables/datatables.min.js"></script>
@endsection

@section('main')
    <div class="container-fluid mt-3">

        {{-- <input type="text" class="form-control d-none" name="course_id" value="{{ $lesson->id }}"> --}}
        {{-- <input type="hidden" name="course_id" value="{{ $lesson->id }}"> --}}
        <div class="container mt-5">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <script>
                                toastr.error('{{ session('success') }}', '{{ session('error ') }}');

                            </script>
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @else
            @endif

            <h1> <strong>Manage Portfolio / Karya</strong></h1>
            <div class="row row-eq-height mt-5 mx-5">
                @forelse ($portfolio as $data)
                    <div class="col-lg-4 col-sm-6 my-2">
                        <div class="album-poster-parent" style="background-color: white !important">
                            <a href="javascript:void();" class="album-poster" data-switch="0">
                                <img class="fufufu" src="{{ Storage::url('public/portfolio/') . $data->image }}" alt="La Noyee">
                            </a>
                            <br>
                            <div class="course-info">
                                <h4>{{ $data->title }}</h4>

                            </div>
                            <p><span class="badge badge-primary">{{ $data->category }}</span></p>
                            <div class="d-flex">
                                <div class="avatar">
                                    <img src="{{ Storage::url('public/profile/') . $data->owner_profile }}" alt="..." class="avatar-img rounded-circle">
                                </div>
                                <div class="info-post ml-2">
                                    <p class="username">{{ $data->owner_name }}</p>

                                </div>
                            </div>
                            <div>
                                <a href="{{ route('portfolio.show', $data->id) }}">
                                    <button type="button" class="btn btn-outline-dark mt-2 btn-xs">Lihat Proyek</button>
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('portfolio.edit', $data->id) }}">
                                    <button type="button" class="btn btn-outline-dark mt-2 btn-xs">Edit Proyek</button>
                                </a>
                            </div>
                            <div class="info-post ml-2">
                                <p>Link Proyek : <br>
                                    <a href="{{ $data->link }}">Buka Link </a>
                                </p>
                            </div>
                            <div>
                                <form id="delete-post-form" action="{{ route('portfolio.destroy', $data->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button  onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger ">Hapus Proyek</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- <p>{{ $data->mentor_name }}</p> --}}
                @empty
                    <div class="alert alert-primary col-12" role="alert">
                        <strong>Belum Ada Project Yang Anda Upload</strong>
                    </div>
                    <a href="{{ url('/portfolio/create') }}">
                        <button type="button" name="" id="" class="btn btn-primary btn-border btn-lg btn-block">Upload Proyek</button>
                    </a>
                @endforelse
            </div>



        </div>

    </div>




    @if (session()->has('success'))
        <script>
            toastr.success('{{ session('success ') }}', '{{ session('success') }}');

        </script>
    @elseif(session()-> has('error'))
        <script>
            toastr.error('{{ session('error') }}', '{{ session('error') }}');
        </script>

    @endif


@endsection

@section('script')



@endsection
