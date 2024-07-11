@extends('main.template')

@section('head-section')
    <!-- Datatables -->

    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>
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

    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/dropzone/dropzone.min.js"></script>

@endsection


@section('main')
<br><br>
    <div class="col-md-12" >
        {{-- BREADCRUMB --}}
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href={{url('/home')}}>Home</a></li>
                <li class="breadcrumb-item"><a href={{url('/lesson/manage_v2')}}>Class</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Materials</li>
            </ol>
        </nav>
    </div>

    <div class="page-inner">
        <div class="page-header">
            <h1><strong>Tambah Materi Baru</strong></h1>
        </div>
        {{-- FORM TAMBAH MATERI --}}
        <div class="col-md-12">
            <div class="load-soal" style="background-color: none">
                <form id="addSessionForm" action="{{ url('/lesson/create_materials/'.$lesson_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- <input hidden name="exam_id" type="text" value="{{ $examId }}"> --}}
                    <div class="row">
                        <div class="col-md-12">

                            <input hidden name="lessonId" type="text" value="{{ $lesson_id }}">

                            {{-- Judul Kelas --}}
                            <div class="mb-3">
                                <label for="" class="mb-2">Judul Materi<span style="color: red">*</span></label>
                                <div class="input-group mb-3">
                                    <input required name="title" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                </div>
                            </div>

                            {{-- Akses Konten --}}
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
                                    <label for="" class="mb-2">Akses Konten<span style="color: red">*</span></label>
                                    <div class="input-group mb-3">
                                        <select required name="is_access" class="form-control form-select-lg" aria-label="Default select example">
                                            <option value="" disabled selected> </option>
                                            <option value="Y">Ya</option>
                                            <option value="T">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
                                    <label for="" class="mb-2">Exam<span style="color: red">*</span></label>
                                    <div class="input-group mb-3" name="quiz_session_id" id="isExam">
                                        <select required name="is_examId" class="form-control form-select-lg" aria-label="Default select example">
                                            <option value="-" selected>-</option>
                                            @foreach($examSessions as $examSession)
                                                <option value="{{ $examSession->id }}">{{ $examSession->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Deskripsi Kelas --}}
                            <div class="mb-3">
                                <label for="" class="mb-2">Upload File<span style="color: red">*</span></label>
                                <div class="mb-3">
                                    <input name="question_images" class="form-control" type="file" id="formFileMultiple" multiple>
                                </div>
                            </div>

                            {{-- Deskripsi Kelas --}}
                            <div class="mb-3">
                                <label for="" class="mb-2">Deskripsi Kelas<span style="color: red">*</span></label>
                                <textarea style="min-height: 500px"  id="editor" class="form-control" name="content"></textarea>
                                <script>
                                    ClassicEditor
                                        .create( document.querySelector( '#editor' ) )
                                        .catch( error => {
                                            console.error( error );
                                        } );
                                </script>
                            </div>

                            {{-- Embedded File --}}
                            <div class="mb-3 d-none">
                                <label for="" class="mb-2">Embeded File<span style="color: red">*</span></label>
                                <textarea  type='text'  class="form-control" name="embeded_file"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="mb-3" style="display: flex; justify-content: flex-end;">
                        <div style="flex-grow: 1;"></div>
                        <div style="width: 200px;">
                            <div class="input-group mb-3">
                                <button type="button" class="btn btn-danger" style="width: 45%; margin-right: 5px;">Cancel</button>
                                <button type="submit" id="saveEditBtn" class="btn btn-success" style="width: 45%; margin-left: 5px;">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- BUTTON REARRANGE --}}
        <div class="page-header mb-3">
            <div class="col-xs-4 col-sm-6 col-md-3 col-lg-3">
                <button class="btn mr-2 ml--10"
                        onclick="redirectToSection('{{ url('lesson/rearrange/'.$lesson_id) }}')"
                        type="submit"
                        style=" background-color: #208DBB;
                                border-radius: 12px;
                                width:80px;
                                height: 40px;
                                position: relative;
                                padding: 0;
                                margin-left: -15px;
                                display: flex;
                                align-items: center;
                                justify-content: center;">
                        <span style="color:white">Rearrange</span>
                </button>
                <script>
                    function redirectToSection(url) {
                        window.location.href = url;
                    }
                </script>
            </div>
        </div>
        <div class="page-header">
            <h1 class="mt--8"><strong>Urutan Materi</strong></h1><br>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead style="background-color: #ebebeb;">
                    <tr class="text-center">
                        <th><h3><b>Urutan</b></h3></th>
                        <th><h3><b>Materi</b></h3></th>
                        <th><h3><b>Manage</b></h3></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dayta as $item)
                    <tr>
                        <td class="text-center">{{  $loop->iteration }}</td>
                        <td style="overflow: hidden; white-space: nowrap;">{{ $item->section_title }}</td>
                        <td>
                            <div class="d-flex justify-content-center" >
                                <form action="{{ route('absensi.manage', ['lesson_id' => $lesson_id, 'section_id' => $item->section_id]) }}" action="GET">
                                    <button class="btn mr-2" style="background-color: #208DBB;
                                                                    border-radius: 15px;
                                                                    width:50px;
                                                                    height: 40px;
                                                                    position: relative;
                                                                    padding: 0;
                                                                    display: flex;
                                                                    align-items: center;
                                                                    justify-content: center;">
                                        <img src="{{ url('/icons/absensi/absensi_btn.svg') }}" style="max-width: 100%; max-height: 100%;">
                                    </button>
                                </form>
                                <form action="{{ route('materials.edit', ['lesson' => $lesson_id, 'section_id' => $item->section_id]) }}" action="POST">
                                    <button class="btn mr-2" style="background-color: #208DBB;
                                                                    border-radius: 15px;
                                                                    width:50px;
                                                                    height: 40px;
                                                                    position: relative;
                                                                    padding: 0;
                                                                    display: flex;
                                                                    align-items: center;
                                                                    justify-content: center;">
                                                <img src="{{ url('/icons/Edit.svg') }}" style="max-width: 100%; max-height: 100%;">
                                    </button>
                                </form>
                                <form id="deleteForm_{{ $item->section_id }}" action="#" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn delete-btn" data-id="{{ $item->section_id }}" style="background-color: #FC1E01; border-radius: 15px; width:50px; height: 40px; position: relative; padding: 0; display: flex; align-items: center; justify-content: center;">
                                        <img src="{{ url('/icons/Delete.svg') }}" style="max-width: 100%; max-height: 100%;">
                                    </button>
                                </form>

                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                                <script>
                                    // Setiap tombol hapus memiliki kelas .delete-btn
                                    document.querySelectorAll('.delete-btn').forEach(item => {
                                        item.addEventListener('click', function() {
                                            const sectionId = this.getAttribute('data-id');

                                            Swal.fire({
                                                title: 'Are you sure?',
                                                text: "You won't be able to revert this!",
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                confirmButtonText: 'Yes, delete it!'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    // Set action form dengan menggunakan sectionId
                                                    document.getElementById('deleteForm_' + sectionId).action = "{{ url('/delete-material', ['lesson' => $lesson_id]) }}/" + sectionId;
                                                    document.getElementById('deleteForm_' + sectionId).submit();
                                                }
                                            });
                                        });
                                    });
                                </script>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <div class="alert alert-danger">
                            Kelas Ini Belum Memiliki Materi
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </div>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection




