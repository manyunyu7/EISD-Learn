@extends('lessons._template')

@section('head-section')
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
    <div class="container-fluid">
        <form method="POST" action="{{ route('course.submission.store') }}" enctype="multipart/form-data">
            @csrf

            <input type="text" class="form-control d-none" name="course_id" value="{{ $lesson->id }}">

            <input type="hidden" name="course_id" value="{{ $lesson->id }}">
            <div class="container mt-5">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <script>
                                    toastr.error('{{ session('
                                        success ') }}', '{{ session('
                                        error ') }}');

                                </script>
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @else
                @endif

                <h1> <strong> Submission Project Untuk Kelas </strong></h1>
                <h2> {{ $lesson->course_title }}</h2>
                <div class="card">
                    <div class="card-body">
                        <blockquote class="blockquote">
                            Hi, Selamat , karena kamu sudah mencapai tahap akhir dari proses pembelajaran di {{ config('app.name') }} , Silakan submit project Akhir
                            untuk mendapatkan sertifikat hasil belajar dari mentor kelas ini,
                            <footer class="card-blockquote"><cite title="Source title"></cite></footer>
                        </blockquote>
                        <p>
                        <ul>
                            <li>Perhatikan Petunjuk Submission Yang Diberikan Guru di bagian Project Akhir</li>
                            <li>Submission yang dikirimkan akan diperiksa oleh guru yang bersangkutan</li>
                            <li>Jika submission kamu diterima, sertifikat kelulusan akan dikirimkan oleh guru ke email kamu</li>
                        </ul>
                        </p>
                        <hr>
                        Kamu juga bisa ucapkan terimakasih untuk mentor yang sudah mengajar dengan cara berdonasi untuk guru yang mengajar
                        <a href="http://themekita.com/atlantis-bootstrap-dashboard.html" class="btn btn-danger btn-block">
                            <span class="btn-label mr-2"> <i class="fa fa-heart"></i> </span>Donasi Untuk Guru</a>
                        </li>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-with-nav">
                            <div class="card-header">
                                <div class="row row-nav-line">
                                    <ul class="nav nav-tabs nav-line nav-color-secondary w-100 pl-3" role="tablist">
                                        <li class="nav-item"> <a class="nav-link active show" data-toggle="tab" href="#home" role="tab" aria-selected="true">
                                                <h3>Project Akhir</h3>
                                            </a> </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Student Name</label>
                                            <input id="inputTitle" disabled type="text" class="form-control @error('name') is-invalid has-error @enderror" name="name" value="{{ old('name', Auth::user()->name) }}" placeholder="Nama Anda">

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" disabled class="form-control @error('email') is-invalid has-error @enderror" name="email" placeholder="Email" value="{{ old('email', Auth::user()->email) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Upload File Project Akhir</label> 
                                            <input type="file" class="form-control-file @error('project_file')
                                            is-invalid
                                            @enderror form-control " name="project_file" id="" placeholder="" aria-describedby="fileHelpId">
                                            @error('project_file') <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <div class="row mt-3 mb-1">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Catatan Tambahan Untuk Guru</label>
                                            <textarea class="form-control  @error('note') is-invalid has-error @enderror " name="note" placeholder="Note" rows="10"> {{ old('note')}}</textarea>
                                        </div>
                                        @error('note') <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="text-right mt-3 mb-3">



                                    <button class="btn btn-success">Save</button>
                                    <button class="btn btn-danger">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @forelse ($dayta as $lesson)


                        <div class="col-md-4">

                            <div class="card card-post card-round">
                                <img class="card-img-top" id="imgPreview" src="{{ Storage::url('public/class/cover/') . $lesson->course_cover_image }}" alt="Cover Kelas">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="avatar">
                                            <img src="{{ Storage::url('public/profile/') . $lesson->profile_url }}" alt="..." class="avatar-img rounded-circle">
                                        </div>
                                        <div class="info-post ml-2">
                                            <p class="username">{{ $lesson->mentor_name }}</p>
                                            <p class="date text-muted">{{ $lesson->created_at }}</p>
                                        </div>
                                    </div>
                                    <div class="separator-solid"></div>
                                    <h3 class="card-title" id="previewName">
                                        <a href="#">
                                            {{ $lesson->course_title }}
                                        </a>
                                    </h3>
                                    <p class="card-text">{!! $lesson->course_description !!} </p>
                                    <a href="#" class="btn btn-primary btn-rounded btn-sm">Read More</a>
                                </div>
                            </div>
                        </div>
                    @empty

                    @endforelse
                </div>
                <div class="card">

             
                <div class="card-body">
                    <div class="btn btn-primary btn-border btn-round mb-3">Riwayat Submission</div>
                    <table style="width: 100%" id="basic-datatables" class="table table-bordered table-responsive      @if (count($dayta) < 1)
                    d-none
                  @endif">
                        <thead>
                          <tr>
                            <th scope="col">No</th>
                            <th scope="col">File</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Status</th>
                            <th scope="col">Catatan Guru</th>
                            <th scope="col">Nilai</th>
                            <th scope="col">Cetak Sertifikat</th>
                          </tr>
                        </thead>
                        <tbody>
                          @forelse ($submission as $data)
                          <tr>
                              <td>{{$loop->iteration}}</td>
                              <td>
                                <a href="{{ Storage::url("public/class/submission/$data->lesson_id/").$data->file }}">
                                    <button type="button" class="btn btn-outline-primary">Download File</button>
                                </a>
                            </td>

                         
                            <td>{{$data->created_at}}</td>
                            @if ($data->status==1)
                            <td>   <span class="badge badge-primary">Sudah Dinilai</span></td>
                            @endif
                            @if ($data->status==0)
                            <td>   <span class="badge badge-warning">Menunggu Dinilai</span></td>
                            @endif
                            @if ($data->status==3)
                            <td>   <span class="badge badge-danger">Ditolak</span></td>
                            @endif
                            <td>{{$data->teacher_note}}</td>
                            <td> {{$data->nilai}}</td>
                            <td> 
                                @if ($data->status==1)
                                <a href="{{ route('course.certificate',$data->id) }}">
                                    <button type="button" class="btn btn-outline-primary">Cetak Sertifikat</button>
                                </a>
                                @else
                                <span class="badge badge-danger">Sertifikat Hanya Bisa Dicetak Setelah Tugas Diapprove</span>
                                @endif

                           </td>
                          </tr>
                       
                          @empty
                              <div class="alert alert-danger">
                                  Anda Belum Mensubmit Proyek
                              </div>
                          @endforelse
                        </tbody>
                      </table>  
                </div>
            </div>
            </div>
        </form>
    </div>




    @if (session()->has('success'))
        <script>
            toastr.success('{{ session('
                success ') }}', '{{ session('
                success ') }}');

        </script>
    @elseif(session()-> has('error'))
        <script>
            toastr.error('{{ session('
                error ') }}', '{{ session('
                error ') }}');

        </script>

    @endif


@endsection

@section('script')



@endsection
