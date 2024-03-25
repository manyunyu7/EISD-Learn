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
                <li class="breadcrumb-item"><a href={{url('/lesson/manage-materials/'.$lesson_id)}}>Add Materials</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Materials</li>
            </ol>
        </nav>
    </div>

    <div class="container page-inner">
        <div class="page-header">
            <h1><strong>Edit Materi</strong></h1>
        </div>
        {{-- FORM TAMBAH MATERI --}}
        <div class="col-md-12">
            <div class="container load-soal" style="background-color: none">
                <form id="addSessionForm" action="{{ url('/update-material/'. $lesson_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- <input hidden name="exam_id" type="text" value="{{ $examId }}"> --}}
                    <div class="row">  
                        <div class="col-md-12">
                            
                            <input hidden name="lessonId" type="text" value="{{ $lesson_id }}">
                            <input hidden name="sectionId" type="text" value="{{ $section_id }}">
                            {{-- Judul Kelas --}}
                            <div class="mb-3">
                                <label for="" class="mb-2">Judul Materi<span style="color: red">*</span></label>
                                <div class="input-group mb-3">
                                    <input required name="update_title" value="{{ $data_course_section_to_edit->section_title }}" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                </div>
                            </div>
                            
                            {{-- Akses Konten --}}
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
                                    <label for="" class="mb-2">Akses Konten<span style="color: red">*</span></label>
                                    <div class="input-group mb-3">
                                        <select required name="update_is_access" class="form-control form-select-lg" aria-label="Default select example">
                                            <option value="" disabled> </option>
                                            <option value="Y" {{ $data_course_section_to_edit->can_be_accessed == 'Y' ? 'selected' : '' }}>Ya</option>
                                            <option value="T" {{ $data_course_section_to_edit->can_be_accessed == 'T' ? 'selected' : '' }}>Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
                                    <label for="" class="mb-2">Exam<span style="color: red">*</span></label>
                                    <div class="input-group mb-3" name="quiz_session_id" id="isExam">
                                        <select required name="update_is_examId" class="form-control form-select-lg" aria-label="Default select example">
                                            <option value="-" {{ $data_course_section_to_edit->is_examId == '-' ? 'selected' : '' }}>-</option>
                                            @foreach($examSessions as $examSession)
                                                <option value="{{ $examSession->id }}" {{ $data_course_section_to_edit->quiz_session_id == $examSession->id ? 'selected' : '' }}>{{ $examSession->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Upload File --}}
                            <div class="mb-3">
                                <label for="" class="mb-2">Upload File<span style="color: red">*</span></label>
                                <div class="mb-3">
                                    <input name="question_images" value="{{ $data_course_section_to_edit->section_video }}"  class="form-control" type="file" id="formFileMultiple" multiple>
                                </div>
                            </div>

                            {{-- Deskripsi Kelas --}}
                            <div class="mb-3">
                                <label for="" class="mb-2">Deskripsi Kelas<span style="color: red">*</span></label>
                                <textarea id="editor" class="form-control" name="update_content">{!! $data_course_section_to_edit->section_content !!}
                                </textarea>
                                <script>
                                    ClassicEditor
                                        .create( document.querySelector( '#editor' ) )
                                        .then( editor => {
                                            editor.setData("{!! $data_course_section_to_edit->section_content !!}");
                                        } )
                                        .catch( error => {
                                            console.error( error );
                                        } );
                                </script>
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
    </div>
@endsection




