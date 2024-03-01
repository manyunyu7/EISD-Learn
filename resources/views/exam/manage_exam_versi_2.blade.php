@extends('main.template')

@section('head-section')
    <!-- Datatables -->

    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>
@endsection

@section('script')
    <script>
        $(document).on('click', '.button', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            swal({
                    title: "Are you sure!",
                    type: "error",
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes!",
                    showCancelButton: true,
                },
                function () {
                    $.ajax({
                        type: "POST",
                        url: "{{url('/destroy')}}",
                        data: {id: id},
                        success: function (data) {
                            //
                        }
                    });
                });
        });

    </script>
    {{-- Toastr --}}
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Datatables -->
    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>
    <script>
        $(document).ready(function () {
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
        @if(session()-> has('success'))
        toastr.success('{{ session('success') }}', 'BERHASIL!');
        @elseif(session()-> has('error'))
        toastr.error('{{ session('error') }}', 'GAGAL!');
        @endif
    </script>

@endsection


@section('main')
<br><br>
    <div class="col-md-12" >
        {{-- BREADCRUMB --}}
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href={{url('/home')}}>Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Exam</li>
            </ol>
        </nav>
    </div>

    <div class="page-inner">
        <div class="page-header">
            <button type="button" class="btn btn-custom md-col-3" style="width:10%" onclick="redirectToSection('{{ url('/exam/manage-exam-v2/create-exam') }}')">
                <span>Add</span>
            </button>
            <script>
                function redirectToSection(url) {
                    window.location.href = url;
                }
            </script>
        </div>

        <div class="page-inner">
            <table class="table">
                <thead class="table-dark" style="text-align: center;">
                  <th>Judul</th>
                  <th>Manage</th>
                </thead>
                <tbody>
                    @forelse ($dayta as $data)
                    <tr>
                        <td>
                            {{ $data->title }}
                        </td>
                        <td style="text-align: center;">
                            <button class="btn btn-primary"></button>
                            <button class="btn btn-secondary"></button>
                            <button class="btn btn-success"></button>
                            <button class="btn btn-danger" onclick="redirectToSection_edit('{{ url('/exam/manage-exam-v2/' . $data->id . '/load-exam') }}')">EDIT</button>
                        </td>
                        <script>
                            function redirectToSection_edit(url) {
                                window.location.href = url;
                            }
                        </script>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
              </table>
        </div>
        {{-- <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Daftar Quiz Milik Anda</div>
                    </div>
                    <div class="card-body">
                        <a href="{{ url('exam/new') }}">
                            <button class="btn btn-primary btn-border btn-round mb-3">Tambah Quiz Baru
                            </button>
                        </a>

                        <div class="table-responsive">
                            <table id="basic-datatables" class="table table-bordered mt-3 @if (count($dayta) < 1) d-none @endif">
                                <thead>
                                <tr>
                                    <th>GAMBAR</th>
                                    <th>JUDUL</th>
                                    <th>Soal</th>
                                    <th>Sesi Exam</th>
                                    <th>Hapus</th>
                                    <th>Edit</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($dayta as $data)
                                    <tr>
                                        <td class="text-center">
                                            <img src="{{ $data->img_full_path }}" class="rounded" style="width: 150px">
                                        </td>
                                        <td>{{ $data->title }}</td>
                                        @php
                                            $examId = $data->id
                                        @endphp
                                        <td><a href="{{url("/exam/$examId/question/")}}" class="badge badge-primary">Manage Pertanyaan</a>
                                        </td>
                                        <td><a href="{{url("/exam/$examId/session/")}}" class="badge badge-primary">Manage Sesi Quiz</a></td>
                                        <td class="text-center">
                                            <form id="delete-post-form" action="{{ route('exam.delete', $data->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">HAPUS</button>
                                            </form>
                                        </td>

                                        <td>
                                            <a href="{{ route('exam.edit', $data->id) }}" class="btn btn-sm btn-primary">EDIT</a>
                                        </td>
                                    </tr>
                                @empty
                                    <div class="alert alert-danger">
                                        Anda Belum Memiliki Kelas
                                    </div>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

@endsection




