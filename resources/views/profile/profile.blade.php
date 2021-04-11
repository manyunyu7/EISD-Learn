@extends('main.template')
@section('main')

    <div class="container-fluid">
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="container mt-5">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <script>
                                    $(function() { //ready
                                        toastr.error('{{ session('
                                            $error ') }}', '{{ $error }}');
                                    });

                                </script>
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <h4 class="page-title">User Profile</h4>
                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-with-nav">
                            <div class="card-header">
                                <div class="row row-nav-line">
                                    <ul class="nav nav-tabs nav-line nav-color-secondary w-100 pl-3" role="tablist">
                                        <li class="nav-item"> <a class="nav-link active show" data-toggle="tab" href="#home" role="tab" aria-selected="true">Timeline</a> </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input id="inputTitle" type="text" class="form-control @error('name') is-invalid has-error @enderror" name="name" value="{{ old('name', Auth::user()->name) }}" placeholder="Nama Anda">

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ganti Foto</label>
                                            <input type="file" class="form-control-file form-control" name="imagez" id="" placeholder="" aria-describedby="fileHelpId">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid has-error @enderror" name="email" placeholder="Email" value="{{ old('email', Auth::user()->email) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input type="text" class="form-control  @error('phone') is-invalid has-error @enderror" value="{{ old('phone', Auth::user()->contact) }}" name="phone" placeholder="Phone">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Institusi</label>
                                            <input type="text" class="form-control" value="{{ Auth::user()->institute }}" name="institute" placeholder="Institusi">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Profesi</label>
                                            <input type="text" class="form-control" value="{{ Auth::user()->jobs }}" name="jobs">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3 mb-1">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Motto Hidup</label>
                                            <textarea class="form-control" name="motto" placeholder="Motto" rows="3"> {{ Auth::user()->motto }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right mt-3 mb-3">



                                    <button class="btn btn-success">Save</button>
                                    <button class="btn btn-danger">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-profile">
                            <div class="card-header" style="background-image: url('../assets/img/blogpost.jpg')">
                                <div class="profile-picture">
                                    <div class="avatar avatar-xl">
                                        <img src="{{ Storage::url('public/profile/') . Auth::user()->profile_url }}" alt="..." class="avatar-img rounded-circle">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="user-profile text-center">
                                    <div class="name">{{ Auth::user()->name }}</div>
                                    <div class="job">{{ Auth::user()->jobs }}</div>
                                    <div class="desc">{{ Auth::user()->motto }}</div>
                                    <div class="social-media d-none">
                                        <a class="btn btn-info btn-twitter btn-sm btn-link" href="#">
                                            <span class="btn-label just-icon"><i class="flaticon-twitter"></i> </span>
                                        </a>
                                        <a class="btn btn-danger btn-sm btn-link" rel="publisher" href="#">
                                            <span class="btn-label just-icon"><i class="flaticon-google-plus"></i> </span>
                                        </a>
                                        <a class="btn btn-primary btn-sm btn-link" rel="publisher" href="#">
                                            <span class="btn-label just-icon"><i class="flaticon-facebook"></i> </span>
                                        </a>
                                        <a class="btn btn-danger btn-sm btn-link" rel="publisher" href="#">
                                            <span class="btn-label just-icon"><i class="flaticon-dribbble"></i> </span>
                                        </a>
                                    </div>

                                    <div class="view-profile d-none">
                                        <a href="#" class="btn btn-secondary btn-block">Simpan Foto Profile</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-none">
                                <div class="row user-stats text-center">
                                    <div class="col">
                                        <div class="number">125</div>
                                        <div class="title">Post</div>
                                    </div>
                                    <div class="col">
                                        <div class="number">25K</div>
                                        <div class="title">Followers</div>
                                    </div>
                                    <div class="col">
                                        <div class="number">134</div>
                                        <div class="title">Following</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>




    @if (session()->has('success'))
        <script>
            toastr.success('{{ session('
                success ') }}', '{{ session('success') }}');

        </script>
    @elseif(session()-> has('error'))
        <script>
            toastr.error('{{ session('
                error ') }}', '{{ session('
                error ') }}');

        </script>

    @endif

@endsection
