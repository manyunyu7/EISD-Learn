@extends('main.template')

@section('head-section')
    <style>
        #previewCover {
            object-fit: cover;
            height: 170px;
            width: 100%;
        }

        .video-mask {
            border-radius: 20px;
            overflow: hidden;
        }
    </style>
    <script src="{{ asset('library/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('script')
    {{-- Toastr --}}
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Datatables -->
    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>
    <script>

        $(document).ready(function () {
            var el = document.getElementById('input-video');
            el.onchange = function () {
                var fileReader = new FileReader();
                fileReader.readAsDataURL(document.getElementById("input-video").files[0])
                fileReader.onload = function (oFREvent) {
                    document.getElementById("vidPrev").src = oFREvent.target.result;
                };
            }

            $('#basic-datatables').DataTable({});

            $('#multi-filter-select').DataTable({
                "pageLength": 5,
                initComplete: function () {
                    this.api().columns().every(function () {
                        var column = this;
                        var select = $('<select class="form-control"><option value=""></option></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                }
            });

            // Add Row
            $('#add-row').DataTable({
                "pageLength": 5,
            });

            var action = '<td> <div class="form-button-action"> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

            $('#addRowButton').click(function () {
                $('#add-row').dataTable().fnAddData([
                    $("#addName").val(),
                    $("#addPosition").val(),
                    $("#addOffice").val(),
                    action
                ]);
                $('#addRowModal').modal('hide');

            });
        });
    </script>


    <script>
        //message with toastr
        @if (session() -> has('success'))
        toastr.success('{{ session('success') }}', 'BERHASIL!');
        @elseif(session() -> has('error'))
        toastr.error('{{ session('error') }}', 'GAGAL!');
        @endif
    </script>

@endsection


@section('main')
    <script>
        $('.toast').toast('show');
    </script>



    <div class="container-fluid">
        <div class="main-content-container container-fluid px-4 mt-5">

            <div class="mt-4">
                <nav aria-label="breadcrumb">
                    @php
                        $lessonzURI = "/lesson/$lesson->course_id";
                    @endphp
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/lesson/manage')}}">Manage Kelas</a></li>
                        <li class="breadcrumb-item">{{$lesson->course_title}}</li>
                    </ol>
                </nav>
            </div>


            <div class="page-header row no-gutters mb-4">
                <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                    <span class="text-uppercase page-subtitle">Manage Section</span>
                    <h3 class="page-title">Manage Materi Kelas</h3>
                    <h4>{{ $lesson->course_title }}</h4>
                    {{-- {{ $lesson }} --}}
                </div>
            </div>


            <div class="card col-12">
                <div class="card-header">
                    <h4 class="card-title">Tambah Materi Baru Untuk Kelas</h4>
                    <h4>{{ $lesson->course_title }}</h4>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <form action="{{ route('section.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="d-none">
                        <input type="text" class="form-control" name="course_id" value="{{$lesson->id}}" placeholder="">
                        <input type="text" class="form-control" name="" aria-describedby="helpId" placeholder="">
                        <input type="text" class="form-control" name="" aria-describedby="helpId" placeholder="">
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2 col-md-12">
                                <div class="nav flex-column nav-pills nav-secondary nav-pills-no-bd nav-pills-icons"
                                     id="v-pills-tab-with-icon" role="tablist" aria-orientation="horizontal">
                                    <a class="nav-link active" id="v-pills-home-tab-icons" data-toggle="pill"
                                       href="#v-pills-home-icons" role="tab" aria-controls="v-pills-home-icons"
                                       aria-selected="true">
                                        <i class="flaticon-interface-6"></i>
                                        Informasi Dasar Materi
                                    </a>
                                    <a class="nav-link" id="v-pills-profile-tab-icons" data-toggle="pill"
                                       href="#v-pills-profile-icons" role="tab" aria-controls="v-pills-profile-icons"
                                       aria-selected="false">
                                        <i class="flaticon-play-button-1"></i>
                                        File Utama Materi
                                    </a>
                                    <a class="nav-link" id="v-pills-profile-tab-icons" data-toggle="pill"
                                       href="#v-class-content" role="tab" aria-controls="v-pills-profile-icons"
                                       aria-selected="false">
                                        <i class="flaticon-pencil"></i>
                                        Konten Materi/Penjelasan Singkat
                                    </a>
                                    <button type="submit"
                                            class="btn btn-primary btn-border btn-round mb-3">Submit
                                    </button>
                                </div>
                            </div>


                            <div class="col-lg-10 col-md-12">
                                <div class="tab-content" id="v-pills-with-icon-tabContent">
                                    <div class="tab-pane fade show active" id="v-pills-home-icons" role="tabpanel"
                                         aria-labelledby="v-pills-home-tab-icons">

                                        <div class="form-group row d-none">
                                            <div class="col-6">
                                                <label for="">Lesson ID</label>
                                                <input type="text" class="form-control" value="{{$lesson->id}}"
                                                       @error('course_id') is-invalid @enderror name="course_id"
                                                       id="lesson_id">
                                            </div>
                                            <div class="col-6">
                                                <label for="">Lesson Name</label>
                                                <input type="text" class="form-control"
                                                       value="{{$lesson->course_title}}"
                                                       @error('course_name') is-invalid
                                                       @enderror name="course_name">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold">JUDUL MATERI</label>
                                            <input id="inputTitle" type="text"
                                                   class="form-control @error('title') is-invalid @enderror"
                                                   name="title"
                                                   value="{{ old('title') }}" placeholder="Masukkan Judul Materi">
                                        </div>


                                        <div class="form-group">
                                            <label>Konten Bisa Diakses ?</label>
                                            <select class="form-control" name="access">
                                                <option value="y">Ya</option>
                                                <option value="n">Tidak</option>
                                            </select>
                                        </div>

                                        
                                        
                                        <div class="form-group" id="examCheck">
                                            <label>Pilih Exam yang akan dimuat</label>
                                            <select class="form-control" name="quiz_session_id" id="isExam">
                                                <option value="-" selected>-</option>
                                                @foreach($examSessions as $examSession)
                                                    <option value="{{ $examSession->id }}">{{ $examSession->quiz_name }} - {{ $examSession->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="form-group" id="durationTake" style="display: none;">
                                            <label>Exam Content?</label>
                                            <select class="form-control" name="durationTake">
                                                <option value="15 Menit" selected>15 Menit</option>
                                                <option value="20 Menit">20 Menit</option>
                                                <option value="25 Menit">25 Menit</option>
                                                <option value="30 Menit">30 Menit</option>
                                            </select>
                                        </div>
                                        
                                        <script>
                                            document.getElementById('isExam').addEventListener('change', function() {
                                                var selectedValue = this.value;
                                                var durationTake = document.getElementById('durationTake');
                                        
                                                if (selectedValue === '-') {
                                                    durationTake.style.display = 'none';
                                                } else {
                                                    durationTake.style.display = 'block';
                                                }
                                            });
                                        </script>
                                        
                                        
                                        <div class="form-group">
                                            <label class="font-weight-bold">Materi Ke- :</label>
                                            <div class="container row">
                                                <input id="section_order" type="text" min="0"
                                                       class="form-control col-6 @error('section_order') is-invalid @enderror"
                                                       name="section_order" value="{{ old('section_order') }}"
                                                       placeholder="Urutan Materi">
                                            </div>


                                            <p> - Isi dengan angka 01 jika ini adalah materi pertama
                                                <br> - 1 Bilangan Urutan Materi hanya boleh muncul 1 kali di kelas
                                                yang sama
                                                {{--                                                <br> - Referensi Urutan dapat dilihat pada tabel materi anda dibawah--}}
                                            </p>

                                            <!-- error message untuk materi -->
                                            @error('title')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-profile-icons" role="tabpanel"
                                         aria-labelledby="v-pills-profile-tab-icons">
                                        <h2><strong> Course Materi </strong></h2>
                                        <p>Replace/Upload Materi untuk megganti file materi (pdf/video/image)</p>
                                        <div class="form-group">
                                            <input id="input-video" type="file"
                                                   onchange="previewVideo()"
                                                   class="form-control @error('video') is-invalid @enderror"
                                                   value="{{ old('video') }}" name="video">
                                        </div>

                                        <div class="embed-responsive embed-responsive-16by9 video-mask">
                                            <video id="vidPrev" loop controls class="embed-responsive-item">
                                                <source
                                                    src="{{ Storage::url('public/class/trailer/') . $lesson->course_trailer }}"
                                                    type=video/mp4>
                                            </video>
                                        </div>


                                    </div>
                                    <div class="tab-pane fade" id="v-class-content" role="tabpanel"
                                         aria-labelledby="v-class-content">
                                        <div class="form-group">
                                            <h3>Penjelasan Materi</h3>
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

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>


            <div class="container-fluid col-12">
                {{-- Start Of Flash Message --}}
                @if(session() -> has('success'))
                    <div class="alert alert-primary alert-dismissible fade show mx-2 my-2" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <strong>{{Session::get( 'success' )}}</strong>
                    </div>

                @elseif(session() -> has('error'))

                    <div class="alert alert-primary alert-dismissible fade show mx-2 my-2" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <strong>{{Session::get( 'error' )}}</strong>

                        @endif
                    </div>


                    <div class="container card col-12">

                        <div class="card-body">


                            {{-- End Of Flash Message --}}

                            <h4 class="card-title">Manage Materi Kelas</h4>
                            <div class="table-responsive">
                                <table id="basic-datatables" class="table table-bordered w-100 @if (count($dayta) < 1)   d-none @endif">
                                    <thead>
                                    <tr>
                                        <th scope="col">Urutan</th>
                                        <th scope="col">Judul Materi</th>
                                        <th scope="col">Materi</th>
                                        <th scope="col">Input Nilai</th>
                                        <th scope="col">Hapus Materi</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($dayta as $data)
                                        <div class="modal fade " id="edit-modal{{$loop->iteration}}">
                                            <div class="modal-dialog modal-xl modal-fullscreen">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title"><b>Edit Materi</b></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form
                                                            action="{{ route('section.update', [$data->section_id]) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="box-body">
                                                                <div>
                                                                    <video loop controls width="100%"
                                                                           class="video-mask d-none">
                                                                        <source
                                                                            src="{{ Storage::url("public/class/content/".$data->lesson_id."/".$data->section_video)}}"
                                                                            type=video/mp4>
                                                                    </video>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Judul Materi</label>
                                                                    <input type="text" class="form-control"
                                                                           name="section_u_title" placeholder="User ID"
                                                                           value="{{$data->section_title}}">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Konten Bisa Diakses ?</label>
                                                                    <select class="form-control" name="access">
                                                                        <option
                                                                            value="y" {{$data->can_be_accessed === "y" ? "selected" : ""}}>
                                                                            Ya
                                                                        </option>
                                                                        <option
                                                                            value="n" {{$data->can_be_accessed === "n" ? "selected" : ""}}>
                                                                            Tidak
                                                                        </option>
                                                                    </select>
                                                                </div>

                                                                <div class="form-group">
                                                                    <select class="form-control" name="quiz_session_id">
                                                                        <option>-</option>
                                                                        @foreach($examSessions as $examSession)
                                                                            <option value="{{ $examSession->id }}" @if($data->quiz_session_id == $examSession->id) selected @endif>
                                                                                {{ $examSession->quiz_name }}, Sesi: ({{ $examSession->start_date }} - {{ $examSession->end_date }})
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="">Ganti File</label>
                                                                    <input id="input-video"
                                                                           type="file"
                                                                           onchange="previewVideo()"
                                                                           class="form-control @error('video') is-invalid @enderror"
                                                                           value="{{ old('section_u_video') }}"
                                                                           name="section_u_video">
                                                                    <small id="helpId" class="form-text text-muted">Kosongkan
                                                                        Jika Tidak Ingin Diganti</small>
                                                                </div>


                                                                <div class="form-group">
                                                                    @php
                                                                        $myString = $data->section_order;
                                                                        $myArray = explode('-', $myString);
                                                                        $realSectionOrder = $myArray[1];
                                                                    @endphp

                                                                    <label>Urutan Materi</label>
                                                                    <input type="number" class="form-control"
                                                                           name="section_u_order"
                                                                           placeholder="Enter username"
                                                                           value="{{$realSectionOrder}}">
                                                                    <small id="helpId" class="form-text text-muted">Urutan
                                                                        Materi dengan angka sama hanya boleh ada 1 di
                                                                        kelas yang sama</small>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Penjelasan Singkat Materi</label>
                                                                    <textarea
                                                                        class="form-control ckeditor @error('content') is-invalid @enderror"
                                                                        name="section_u_content" rows="5"
                                                                        placeholder="Masukkan Deskripsi Kelas">{{$data->section_content,old('section_u_content') }}</textarea>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                            class="btn btn-default pull-left"
                                                                            data-dismiss="modal">Close
                                                                    </button>
                                                                    <button type="submit" class="btn btn-primary">Save
                                                                        changes
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <tr>

                                            <div class="modal fade modal-dialog modal-xl bd-example-modal-xl"
                                                 id="video-modal{{$loop->iteration}}">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title"><b>Materi</b></h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="box-body">
                                                                <div>
                                                                    <h1>{{$data->section_title}}</h1>
                                                                    <video loop controls width="100%"
                                                                           class="video-mask">
                                                                        <source
                                                                            src="{{ Storage::url("public/class/content/".$data->lesson_id."/".$data->section_video."?random1")}}"
                                                                            type=video/mp4>
                                                                    </video>
                                                                    <br>
                                                                    <hr>
                                                                    {!! $data->section_content !!}
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                            class="btn btn-default pull-left"
                                                                            data-dismiss="modal">Close
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- END OF VIDEO MODAL --}}

                                                {{-- Start OF Table Data --}}
                                                <td>{{ $data->section_order }} <br>
                                                <td>{{ $data->section_title }} <br>
                                                <td>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                            data-target="#edit-modal{{$loop->iteration}}">
                                                        Lihat/Edit Detail
                                                    </button>
                                                </td>
                                                <td>
                                                    <a href="{{url("/lesson/".$data->lesson_id."/section/".$data->section_id."/input-score")}}">
                                                        <button type="button" class="btn btn-primary">
                                                            Input Nilai
                                                        </button>
                                                    </a>
                                                </td>
                                                {{--                                                <td>--}}
                                                {{--                                                    <button type="button" class="btn btn-outline-primary"--}}
                                                {{--                                                            data-toggle="modal"--}}
                                                {{--                                                            data-target="#video-modal{{$loop->iteration}}">Lihat Materi--}}
                                                {{--                                                    </button>--}}
                                                {{--                                                </td>--}}
                                                <td class="text-center">
                                                    <form id="delete-post-form"
                                                          action="{{ route('section.destroy', [$data->section_id])}}"
                                                          method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button onclick="return confirm('Are you sure?')"
                                                                class="btn btn-sm btn-danger ">HAPUS
                                                        </button>
                                                    </form>
                                                </td>
                                            </div>
                                        </tr>
                                        
                                    @empty
                                        <div class="alert alert-danger">
                                            Kelas Ini Belum Memiliki Materi
                                        </div>

                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>


            </div>

@endsection
