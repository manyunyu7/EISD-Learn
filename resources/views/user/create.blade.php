@extends('168_template')


@section("header_name")
    Tambah User Baru
@endsection

@section("page_content")
    <div class="content-body" style="min-height: 798px;">
        <div class="container-fluid">
            <div class="row page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Pengguna</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Tambah Baru</a></li>
                </ol>
            </div>
            <!-- row -->
            <form action="{{ url('user/store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12">
                        @include("168_component.alert_message.message")
                    </div>
                    <div class="col-xl-6 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Data Pengguna</h4>
                            </div>
                            <div class="card-body">
                                <div class="basic-form">
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Nama</label>
                                            <input type="text" name="user_name" class="form-control"
                                                   placeholder="1234 Main St">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="user_email" class="form-control"
                                                   placeholder="Email">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Password</label>
                                            <input type="password" name="user_password" required class="form-control"
                                                   placeholder="Password">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Role Pengguna</label>
                                            <select class="default-select form-control wide mb-3"
                                                    style="display: none;">
                                                <option value="1">Super Admin</option>
                                                <option value="3">User</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3 col-md-12">
                                        <label class="col-form-label">Nomor Telepon (Login)</label>
                                        <input name="user_contact" type="number" class="form-control"
                                               placeholder="Nomor Telepon">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"></h4>
                            </div>
                            <div class="card-body">
                                <div class="basic-form">


                                    <div class="mb-3 row">
                                        <label class="col-form-label mt-4">Foto Baru</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="form-file">
                                                    <input type="file" name="photo"
                                                           class="form-file-input form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary mt-5">Tambah Pengguna</button>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </form>
        </div>
    </div>
@endsection
