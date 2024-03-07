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

    

@endsection


@section('main')
<br><br>
    <div class="col-md-12" >
        {{-- BREADCRUMB --}}
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href={{url('/home')}}>Home</a></li>
                <li class="breadcrumb-item"><a href={{url('/lesson/manage_v2')}}>Class</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Class</li>
            </ol>
        </nav>
    </div>

    <div class="container page-inner">
        <div class="page-header" >
            <h1><b>Tambah Kelas Baru</b></h1>
        </div>
        {{-- SOAL UJIAN --}}
        <div class="container load-soal" style="background-color: none">
            <form id="addSessionForm" action="{{ route('lesson.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')

                {{-- <input hidden name="exam_id" type="text" value="{{ $examId }}"> --}}
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="" class="mb-2">Password Kelas<span style="color: red">*</span></label>
                            <div class="input-group mb-3">
                                <input required name="pass_class" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                            </div>
                        </div>
        
                        <div class="mb-3">
                            <label for="" class="mb-2">Judul Kelas<span style="color: red">*</span></label>
                            <div class="input-group mb-3">
                                <input required name="title_class" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="" class="mb-2">Kategori<span style="color: red">*</span></label>
                            <div class="input-group mb-3">
                                <select required name="type_questions" class="form-control form-select-lg" aria-label="Default select example">
                                    <option value="" disabled selected>Pilih jenis soal</option>
                                    <option value="Multiple Choice">Multiple Choice</option>
                                    <option value="Single Multiple Choice">Single Multiple Choice</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="" class="mb-2">Position<span style="color: red">*</span></label>
                            <div class="input-group mb-3">
                                <select required name="type_questions" class="form-control form-select-lg" aria-label="Default select example">
                                    <option value="" disabled selected>Pilih jenis soal</option>
                                    <option value="Unit Head">Unit Head</option>
                                    <option value="Section Head">Section Head</option>
                                    <option value="Department Head">Department Head</option>
                                </select>
                            </div>
                        </div>
        
                        <div class="mb-3">
                            <label for="" class="mb-2">Member -  Non Member<span style="color: red">*</span></label>
                            <div class="input-group">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" value="">
                                        <span class="form-check-sign">Member</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" value="">
                                        <span class="form-check-sign">Non Member</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="" class="mb-2">Deskripsi Kelas</label>
                            <textarea id="editor" class="form-control" name="instruction"></textarea>
                            <script>
                                ClassicEditor
                                    .create( document.querySelector( '#editor' ) )
                                    .catch( error => {
                                        console.error( error );
                                    } );
                            </script>
                        </div>
                        <div class="mb-3">
                            <label for="" class="mb-2">New Kelas<span style="color: red">*</span></label>
                            <div class="input-group mb-3">
                                <input required 
                                name="title_class" 
                                type="button" 
                                value="Tidak Aktif"
                                class="form-control btn btn-danger" 
                                aria-label="Recipient's username" 
                                aria-describedby="basic-addon2">
                            </div>
                        </div>
                    </div>
    
                    <div class="col-md-4">
                        <div class="card mt-5">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="card">
                                        <img id="profileImage" 
                                             src="{{ Storage::url('public/profile/').Auth::user()->profile_url }}" 
                                             onerror="this.onerror=null; this.src='{{ url('/default/default_profile.png') }}'; this.alt='Alternative Image';"
                                             class="rounded" 
                                             alt="...">
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="file" name="profile_image" class="form-control" id="inputGroupFile02" accept="image/*" onchange="previewImage()">
                                    </div>
                                    {{-- <p style="color: red">{{ Auth::user()->profile_url }}</p> --}}
                                    <small width="100%">Image size should be under 1 MB and image ratio needs to be 1:1</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                

                {{-- BUTTONS --}}
                <div class="mb-3" style="display: flex; justify-content: flex-end;">
                    <div style="flex-grow: 1;"></div>
                    <div style="width: 200px;">
                        <div class="input-group mb-3">
                            <button type="button" class="btn btn-danger" style="width: 45%; margin-right: 5px;">Cancel</button>
                            <button type="submit" id="saveEditBtn" class="btn btn-success" style="width: 45%; margin-left: 5px;">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection




