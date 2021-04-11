@extends('main.template')

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

        {{-- <input type="text" class="form-control d-none" name="course_id" value="{{ $lesson->id }}"> --}}
        {{-- <input type="hidden" name="course_id" value="{{ $lesson->id }}"> --}}
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

            <h1> <strong> Submission Project Untuk Semua Kelas </strong></h1>
            <h2>
                {{-- {{ $lesson->course_title }} --}}
            </h2>
            <div class="card">
                <div class="card-body">
                    <div class="btn btn-primary btn-border btn-round mb-3">Riwayat Submission</div>
                    <table style="width: 100%" id="basic-datatables" class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama Siswa</th>
                                <th scope="col">File</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Status</th>
                                <th scope="col">Nilai</th>
                                <th scope="col">Input Nilai</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dayta as $data)
                                <div class="modal fade  bd-example-modal-xl" id="input-modal{{ $loop->iteration }}">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title"><b>ID Tugas {{ $data->id }}</b></h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="box-body">
                                                    <form method="POST" action="{{ route('course.submission.scoring') }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div>
                                                       
                                                              <input type="text" class="form-control d-none" name="project_id" value="{{$data->id}}"  placeholder="">
                                                
                                                            <h4>Nama Siswa : </h4>
                                                            <p>{{ $data->name }}</p>
                                                            <h4>Catatan Dari Siswa : </h4>
                                                            <p>{{ $data->note }}</p>
                                                            <div class="form-group">
                                                                <label for="">Status</label>
                                                                <select class="form-control" name="status" id="">
                                                                    <option value="1">Diterima</option>
                                                                    <option value="3">Ditolak</option>
                                                                    <option value="0">Menunggu Dinilai</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="">Nilai</label>
                                                                <input type="number" class="form-control" name="score" id="" placeholder="" value="{{ $data->nilai }}">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="">Catatan Penilaian</label>
                                                                <textarea class="form-control" name="teacher_note" id="" rows="3">
                                                                    {{$data->teacher_note}}
                                                                </textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>
                                            <a href="{{ Storage::url("public/class/submission/$data->lesson_id/").$data->file }}">
                                                <button type="button" class="btn btn-outline-primary">Download File</button>
                                            </a>
                                        </td>

                                        <td>{{ $data->created_at }}</td>
                                        @if ($data->status == 1)
                                            <td> <span class="badge badge-primary">Sudah Dinilai</span></td>
                                        @endif
                                        @if ($data->status == 0)
                                            <td> <span class="badge badge-warning">Menunggu Dinilai</span></td>
                                        @endif
                                        @if ($data->status == 3)
                                            <td> <span class="badge badge-danger">Ditolak</span></td>
                                        @endif
                                        {{-- <td> <span class="badge badge-warning">{{ $data->teacher_note }}</span></td> --}}
                                        <td> <span class="badge badge-success">{{ $data->nilai }}</span></td>
                                        <td> <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#input-modal{{ $loop->iteration }}">Input Nilai</button></td>
                                    </tr>
                                @empty
                                    <div class="alert alert-danger">
                                        Belum Ada Siswa Yang Submit Proyek
                                    </div>
                                @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

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
