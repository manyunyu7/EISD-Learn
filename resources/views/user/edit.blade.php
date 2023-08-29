@extends('168_template')


@section("header_name")
    Edit Profil Pengguna
@endsection

@section("page_content")
    <div class="content-body" style="min-height: 798px;">
        <div class="container-fluid">
            <div class="row page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Pengguna</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Edit</a></li>
                </ol>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="profile card card-body px-3 pt-3 pb-0">
                        <div class="profile-head">
                            <div class="photo-content">
                                <div class="cover-photo rounded"></div>
                            </div>
                            <div class="profile-info">
                                <div class="profile-photo">
                                    <img style="width: 108px!important; height: 108px !important;"

                                         src="{{asset($data->photo)}}" class="rounded-circle" onerror="this.src='http://feylabs.my.id/fm/mdln_asset/mdln.png'">
                                </div>
                                <div class="profile-details">
                                    <div class="profile-name px-3 pt-2">
                                        <h4 class="text-primary mb-0">{{$data->name}}</h4>
                                        <p>{{$data->role_desc}}</p>
                                    </div>
                                    <div class="profile-email px-2 pt-2">
                                        <h4 class="text-muted mb-0">{{$data->email}} | {{$data->contact}}</h4>
                                        <p>Email</p>
                                    </div>
                                    <div class="dropdown ms-auto">
                                        <a href="#" class="btn btn-primary light sharp" data-bs-toggle="dropdown"
                                           aria-expanded="true">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
                                                 viewbox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"></rect>
                                                    <circle fill="#000000" cx="5" cy="12" r="2"></circle>
                                                    <circle fill="#000000" cx="12" cy="12" r="2"></circle>
                                                    <circle fill="#000000" cx="19" cy="12" r="2"></circle>
                                                </g>
                                            </svg>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li class="dropdown-item"><i
                                                    class="fa fa-user-circle text-primary me-2"></i> View profile
                                            </li>
                                            <li class="dropdown-item"><i class="fa fa-users text-primary me-2"></i> Add
                                                to btn-close friends
                                            </li>
                                            <li class="dropdown-item"><i class="fa fa-plus text-primary me-2"></i> Add
                                                to group
                                            </li>
                                            <li class="dropdown-item"><i class="fa fa-ban text-primary me-2"></i> Block
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                @include('168_component.alert_message.message')
            </div>
            <div class="row">
                <div class="col-xl-4 d-none">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="profile-interest">
                                        <h5 class="text-primary d-inline">Program</h5>
                                        <div class="row mt-4 sp4" id="lightgallery">
                                            <a href="images/profile/2.jpg" data-exthumbimage="images/profile/2.jpg"
                                               data-src="images/profile/2.jpg"
                                               class="mb-1 col-lg-4 col-xl-4 col-sm-4 col-6">
                                                <img src="images/profile/2.jpg" alt="" class="img-fluid">
                                            </a>
                                            <a href="images/profile/3.jpg" data-exthumbimage="images/profile/3.jpg"
                                               data-src="images/profile/3.jpg"
                                               class="mb-1 col-lg-4 col-xl-4 col-sm-4 col-6">
                                                <img src="images/profile/3.jpg" alt="" class="img-fluid">
                                            </a>
                                            <a href="images/profile/4.jpg" data-exthumbimage="images/profile/4.jpg"
                                               data-src="images/profile/4.jpg"
                                               class="mb-1 col-lg-4 col-xl-4 col-sm-4 col-6">
                                                <img src="images/profile/4.jpg" alt="" class="img-fluid">
                                            </a>
                                            <a href="images/profile/3.jpg" data-exthumbimage="images/profile/3.jpg"
                                               data-src="images/profile/3.jpg"
                                               class="mb-1 col-lg-4 col-xl-4 col-sm-4 col-6">
                                                <img src="images/profile/3.jpg" alt="" class="img-fluid">
                                            </a>
                                            <a href="images/profile/4.jpg" data-exthumbimage="images/profile/4.jpg"
                                               data-src="images/profile/4.jpg"
                                               class="mb-1 col-lg-4 col-xl-4 col-sm-4 col-6">
                                                <img src="images/profile/4.jpg" alt="" class="img-fluid">
                                            </a>
                                            <a href="images/profile/2.jpg" data-exthumbimage="images/profile/2.jpg"
                                               data-src="images/profile/2.jpg"
                                               class="mb-1 col-lg-4 col-xl-4 col-sm-4 col-6">
                                                <img src="images/profile/2.jpg" alt="" class="img-fluid">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="profile-news">
                                        <h5 class="text-primary d-inline">Our Latest News</h5>
                                        <div class="media pt-3 pb-3">
                                            <img src="images/profile/5.jpg" alt="image" class="me-3 rounded" width="75">
                                            <div class="media-body">
                                                <h5 class="m-b-5"><a href="post-details.html" class="text-black">Collection
                                                        of textile samples</a></h5>
                                                <p class="mb-0">I shared this on my fb wall a few months back, and I
                                                    thought.</p>
                                            </div>
                                        </div>
                                        <div class="media pt-3 pb-3">
                                            <img src="images/profile/6.jpg" alt="image" class="me-3 rounded" width="75">
                                            <div class="media-body">
                                                <h5 class="m-b-5"><a href="post-details.html" class="text-black">Collection
                                                        of textile samples</a></h5>
                                                <p class="mb-0">I shared this on my fb wall a few months back, and I
                                                    thought.</p>
                                            </div>
                                        </div>
                                        <div class="media pt-3 pb-3">
                                            <img src="images/profile/7.jpg" alt="image" class="me-3 rounded" width="75">
                                            <div class="media-body">
                                                <h5 class="m-b-5"><a href="post-details.html" class="text-black">Collection
                                                        of textile samples</a></h5>
                                                <p class="mb-0">I shared this on my fb wall a few months back, and I
                                                    thought.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="profile-tab">
                                <div class="custom-tab-1">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item active"><a href="#profile-settings" data-bs-toggle="tab"
                                                                       class="nav-link active">Setting</a>
                                        </li>
                                        <li class="nav-item"><a href="#change-password" data-bs-toggle="tab"
                                                                class="nav-link">Password</a>
                                        </li>
                                        <li class="nav-item"><a href="#change-photo" data-bs-toggle="tab"
                                                                class="nav-link">Foto</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="profile-settings" class="tab-pane fade active show">
                                            <form action="{{ url('user/update') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$data->id}}">
                                                <div class="pt-3">
                                                    <div class="settings-form">
                                                        <h4 class="text-primary">Account Setting</h4>
                                                        <div class="row">
                                                            <div class="mb-3 col-md-6">
                                                                <label class="form-label">Email</label>
                                                                <input name="user_email" type="email"
                                                                       placeholder="Email"
                                                                       class="form-control"
                                                                       value="{{$data->email}}">
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <label class="form-label">Contact</label>
                                                                <input name="user_contact" type="number"
                                                                       placeholder="Kontak"
                                                                       class="form-control"
                                                                       value="{{$data->contact}}">
                                                            </div>

                                                            <div class="mb-3 col-md-6">
                                                                <label>Role Pengguna</label>
                                                                <select name="user_role" class="default-select form-control wide mb-3"
                                                                        style="display: none;">
                                                                    <option>Pilih User Role</option>
                                                                    <option {{($data->role=="mentor") ? 'selected' : ''}} value="mentor">Mentor</option>
                                                                    <option {{($data->role=="student") ? 'selected' : ''}} value="student">Student</option>
                                                                </select>
                                                            </div>

                                                            <div class="mb-3 col-md-6">
                                                                <label class="form-label">Nama</label>
                                                                <input name="user_name" type="text" placeholder="Name"
                                                                       class="form-control" value="{{$data->name}}">
                                                            </div>

                                                        </div>

                                                        <button class="btn btn-primary" type="submit">
                                                            Simpan Perubahan
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div id="change-password" class="tab-pane fade">
                                            <form action="{{ url('user/change-password') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="redirectTo" value="">
                                                <input type="hidden" name="id" value="{{$data->id}}">
                                                <div class="pt-3">
                                                    <div class="settings-form">
                                                        <h4 class="text-primary">Change Password</h4>
                                                        <form>
                                                            <div class="row">
                                                                <div class="mb-3 col-md-6">
                                                                    <label class="form-label">Password Lama</label>
                                                                    <input name="old_password" type="password"
                                                                           placeholder="Password Lama"
                                                                           class="form-control" value="">
                                                                </div>
                                                                <div class="mb-3 col-md-6">
                                                                    <label class="form-label">Contact</label>
                                                                    <input name="new_password" type="password"
                                                                           placeholder="Password Baru"
                                                                           class="form-control" value="">
                                                                </div>
                                                            </div>

                                                            <button class="btn btn-primary" type="submit">Ganti
                                                                Password
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div id="change-photo" class="tab-pane fade">
                                            <form action='{{url("/user/$data->id/change-photo")}}' method="post"
                                                  enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="redirectTo" value="">
                                                <input type="hidden" name="id" value="{{$data->id}}">

                                                <div class="pt-3">
                                                    <div class="settings-form">
                                                        <h4 class="text-primary">Change Photo</h4>

                                                        <div class="row">
                                                            <div class="col-12">
                                                                <img
                                                                    style="width: 108px!important; height: 108px !important;"
                                                                    src="{{asset($data->full_photo_path)}}"
                                                                    class="rounded-circle" onerror="this.src='http://feylabs.my.id/fm/mdln_asset/mdln.png'">
                                                            </div>

                                                            <div class="mb-3 col-md-6">
                                                                <label class="form-label">Foto</label>
                                                                <div class="input-group">
                                                                    <div class="form-file">
                                                                        <input type="file" accept="image/*" name="photo"
                                                                               class="form-file-input form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <button class="btn btn-primary" type="submit">
                                                            Simpan Perubahan
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
