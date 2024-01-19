@extends('main.template')
@section('main')

    <div class="container-fluid">

        <div class="container mt-5">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <script>
                                $(function () { //ready
                                    toastr.error('{{ session('
                                            $error ') }}', '{{ $error }}');
                                });

                            </script>
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- BREADCRUMB --}}
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href={{url('/home')}}>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">My Profile</li>
                </ol>
            </nav>
            <h4 class="page-title">Akun Saya</h4>
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        <div class="row">
                            {{-- SECTION PROFILE FORM --}}
                            <div class="col-md-8" >
                                <div class="">
                                        @csrf
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Lengkap</label>
                                                    <input id="inputTitle" type="text"
                                                        class="form-control @error('name') is-invalid has-error @enderror"
                                                        name="name" value="{{ old('name', Auth::user()->name) }}"
                                                        placeholder="Nama Anda" readonly>
            
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Belakang</label>
                                                    <input type="text" class="form-control-file form-control" name="imagez"
                                                        id="" placeholder="" aria-describedby="fileHelpId" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Username</label>
                                                    <input type="text"
                                                        class="form-control @error('username') is-invalid has-error @enderror"
                                                        name="username" placeholder="Username"
                                                        value="{{ old('email', Auth::user()->username) }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid has-error @enderror"
                                                        name="email" placeholder="Email"
                                                        value="{{ old('email', Auth::user()->email) }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Jabatan</label>
                                                    <input type="text"
                                                        class="form-control @error('email') is-invalid has-error @enderror"
                                                        name="jabatan" placeholder="Jabatan"
                                                        value="{{ old('jabatan', Auth::user()->jabatan) }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Department</label>
                                                    <input type="text"
                                                        class="form-control @error('email') is-invalid has-error @enderror"
                                                        name="department" placeholder="Department"
                                                        value="{{ old('department', Auth::user()->department) }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Unit Business</label>
                                                    <input type="text"
                                                        class="form-control @error('email') is-invalid has-error @enderror"
                                                        name="unit_business" placeholder="Unit Business"
                                                        value="{{ old('location', Auth::user()->location) }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>No Telepon</label>
                                                    <input type="text"
                                                        class="form-control  @error('phone') is-invalid has-error @enderror"
                                                        value="{{ old('phone', Auth::user()->contact) }}" name="phone"
                                                        placeholder="Phone">
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                
                                
                            </div>
                            {{-- SECTION PROFILE PICTURE --}}
                            <div class="col-md-4">
                                <div class="card mt-5">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <div class="card">
                                                <img id="profileImage" 
                                                     src="{{ Storage::url('public/profile/').Auth::user()->profile_url }}" 
                                                     {{-- onerror="this.onerror=null; this.src='{{ url('/default/default_profile.png') }}';"  --}}
                                                     class="rounded" 
                                                     alt="...">
                                            </div>
                                            <div class="input-group mb-3">
                                                <input type="file" name="profile_image" class="form-control" id="inputGroupFile02" accept="image/*" onchange="previewImage()">
                                            </div>
                                            <p style="color: red">{{ Auth::user()->profile_url }}</p>
                                            <small width="100%">Image size should be under 1 MB and image ratio needs to be 1:1</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card card-profile">
                                    <div class="card-header" style="background-image: url('../assets/img/blogpost.jpg')">
                                        <div class="profile-picture">
                                            <div class="avatar avatar-xl">
                                                <img src="{{ Storage::url('public/profile/') . Auth::user()->profile_url }}"
                                                    alt="..." class="avatar-img rounded-circle">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="user-profile text-center">
                                            <div class="name">{{ Auth::user()->name }}</div>
                                            <div class="job">{{ Auth::user()->jabatan }}</div>
                                            <div class="view-profile d-none">
                                                <a href="#" class="btn btn-secondary btn-block">Simpan Foto Profile</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <script>
                                function previewImage() {
                                    var input = document.getElementById('inputGroupFile02');
                                    var image = document.getElementById('profileImage');
                                    var reader = new FileReader();
                            
                                    reader.onload = function (e) {
                                        var img = new Image();
                                        img.src = e.target.result;
                            
                                        img.onload = function () {
                                            // Cek apakah gambar memiliki rasio 1:1
                                            if (img.width === img.height) {
                                                image.src = e.target.result;
                                            } else {
                                                alert("Image size should be under 1 MB and image ratio needs to be 1:1");
                                                // Reset input
                                                input.value = "";
                                            }
                                        };
                                    };
                            
                                    if (input.files[0]) {
                                        reader.readAsDataURL(input.files[0]);
                                    }
                                }
                            </script>
                            
                            <div class="col-md-12" >
                                <div class="text-right mt-3 mb-3">
                                    <button class="btn btn-success">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            

            <div class="row">
                <div class="col-md-12" style="background-color: yellow">
                    <div class="">
                        {{-- GANTI PASSWORD --}}
                        <form method="POST" action="{{ url('/dfef') }}">
                            @csrf
                            <div class="row mt-3">
                                <!-- Add form fields for the change password tab -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Current Password</label>
                                        <input type="password" class="form-control" name="current-password" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>New Password</label>
                                        <input type="password" class="form-control" name="new-password" required>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right mt-3 mb-3">
                                <button class="btn btn-success">Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
