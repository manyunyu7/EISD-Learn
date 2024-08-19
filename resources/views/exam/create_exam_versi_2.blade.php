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

    <div class="page-inner  bg-white">

        <div class="col-md-12" >
            {{-- BREADCRUMB --}}
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href={{url('/home')}}>Home</a></li>
                    <li class="breadcrumb-item"><a href={{url('/exam/manage-exam-v2')}}>Exam</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Exam</li>
                </ol>
            </nav>
        </div>


        {{-- SETUP QUIZ --}}
        <div class="col-12">
            <div class="page-header">
                {{-- <h1><b>STEP 1</b></h1><br> --}}
            </div>
            <div class="page-header">
                <h2><b>SetUp Quiz/Pre-Test/Post-Test/Evaluation</b></h2>
            </div>
            <form id="addSessionForm" method="post" action="{{ route('store.quiz') }}">
                @csrf
                @method('POST')

                {{-- Input Judul Ujian --}}
                <div class="mb-3">
                    <label for="" class="mb-2">Judul Ujian</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" name="title">
                    </div>
                </div>
                {{-- Input Jenis Soal --}}
                <div class="mb-3">
                    <label for="" class="mb-2">Jenis Ujian</label>
                    <div class="input-group mb-3">
                        <select name="exam_type" class="form-control form-select-lg" aria-label="Default select example">
                            <option value="Pre Test">Pre Test</option>
                            <option value="Post Test">Post Test</option>
                            <option value="Quiz">Quiz</option>
                            <option value="Evaluation">Evaluation</option>
                        </select>
                    </div>
                </div>
                {{-- Input Batas Waktu --}}
                <div class="mb-3">
                    <label for="" class="mb-2">Batas Waktu (Menit)</label>
                    <div class="input-group mb-3">
                        <input name="times_limit" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    </div>
                </div>
                {{-- Input Start and End Date --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="" class="mb-2">Start Date</label>
                        <div class="input-group mb-3">
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="" class="mb-2">End Date</label>
                        <div class="input-group mb-3">
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date">
                        </div>
                    </div>
                </div>
                {{-- Input Instruksi Exam --}}
                <div class="mb-3">
                    <label for="" class="mb-2">Instruksi Exam</label>
                    <textarea id="editor" class="form-control" name="instruction"></textarea>
                    <script>
                        ClassicEditor
                            .create( document.querySelector( '#editor' ) )
                            .catch( error => {
                                console.error( error );
                            } );
                    </script>
                </div>


                {{-- SETUP ACCESS --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="" class="mb-2">Public Access</label>
                        <div class="input-group mb-3">
                            <input readonly type="text" value="Tidak Aktif" name="public_access" id="public-access-btn" class="btn btn-danger" style="width: 100%">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="" class="mb-2">Show Result</label>
                        <div class="input-group mb-3">
                            <input readonly type="text" value="Tidak Aktif" name="show_result_on_end" id="show-result-btn" class="btn btn-danger" style="width: 100%">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="" class="mb-2">Allow Review</label>
                        <div class="input-group mb-3">
                            <input readonly type="text" value="Tidak Aktif" name="allow_review" id="allow-review-btn" class="btn btn-danger" style="width: 100%">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="" class="mb-2">Allow Multiple</label>
                        <div class="input-group mb-3">
                            <input readonly type="text" value="Tidak Aktif" name="allow_multiple" id="allow-multiple-btn" class="btn btn-danger" style="width: 100%">
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            var btn_public_access = document.getElementById('public-access-btn');
                            var btn_show_result = document.getElementById('show-result-btn');
                            var btn_allow_review = document.getElementById('allow-review-btn');
                            var btn_allow_multiple = document.getElementById('allow-multiple-btn');

                            var isActive_PA = false;
                            var isActive_SR = false;
                            var isActive_AR = false;
                            var isActive_AM = false;

                            // Public Access Setup
                            btn_public_access.addEventListener('click', function () {
                                // Tidak Aktif
                                if (isActive_PA) {
                                    btn_public_access.classList.remove('btn-success');
                                    btn_public_access.classList.add('btn-danger');
                                    btn_public_access.textContent = 'Tidak Aktif';
                                    btn_public_access.value ='Tidak Aktif';
                                    isActive_PA = false;
                                }
                                // Aktif
                                else {
                                    btn_public_access.classList.remove('btn-danger');
                                    btn_public_access.classList.add('btn-success');
                                    btn_public_access.textContent = 'Aktif';
                                    btn_public_access.value ='Aktif';
                                    isActive_PA = true;
                                }
                            });

                            // Show Result Setup
                            btn_show_result.addEventListener('click', function () {
                                // Tidak Aktif
                                if (isActive_SR) {
                                    btn_show_result.classList.remove('btn-success');
                                    btn_show_result.classList.add('btn-danger');
                                    btn_show_result.textContent = 'Tidak Aktif';
                                    btn_show_result.value = 'Tidak Aktif';
                                    isActive_SR = false;
                                }
                                // Aktif
                                else {
                                    btn_show_result.classList.remove('btn-danger');
                                    btn_show_result.classList.add('btn-success');
                                    btn_show_result.textContent = 'Aktif';
                                    btn_show_result.value = 'Aktif';
                                    isActive_SR = true;
                                }
                            });

                            // Allow Review Setup
                            btn_allow_review.addEventListener('click', function () {
                                // Tidak Aktif
                                if (isActive_AR) {
                                    btn_allow_review.classList.remove('btn-success');
                                    btn_allow_review.classList.add('btn-danger');
                                    btn_allow_review.textContent = 'Tidak Aktif';
                                    btn_allow_review.value = 'Tidak Aktif';
                                    isActive_AR = false;
                                }
                                // Aktif
                                else {
                                    btn_allow_review.classList.remove('btn-danger');
                                    btn_allow_review.classList.add('btn-success');
                                    btn_allow_review.textContent = 'Aktif';
                                    btn_allow_review.value = 'Aktif';
                                    isActive_AR = true;
                                }
                            });

                            // Allow Multiple Setup
                            btn_allow_multiple.addEventListener('click', function () {
                                // Tidak Aktif
                                if (isActive_AM) {
                                    btn_allow_multiple.classList.remove('btn-success');
                                    btn_allow_multiple.classList.add('btn-danger');
                                    btn_allow_multiple.textContent = 'Tidak Aktif';
                                    btn_allow_multiple.value = 'Tidak Aktif';
                                    isActive_AM = false;
                                }
                                // Aktif
                                else {
                                    btn_allow_multiple.classList.remove('btn-danger');
                                    btn_allow_multiple.classList.add('btn-success');
                                    btn_allow_multiple.textContent = 'Aktif';
                                    btn_allow_multiple.value = 'Aktif';
                                    isActive_AM = true;
                                }
                            });
                        });
                    </script>
                </div>

                <div class="mb-3" style="display: flex; justify-content: flex-end;">
                    <div style="flex-grow: 1;"></div>
                    <div style="width: 200px;">
                        <div class="input-group mb-3">
                            <button type="button" class="btn btn-danger" style="width: 45%; margin-right: 5px;">Cancel</button>
                            <button type="submit" id="saveEditBtn" class="btn btn-primary" style="width: 45%; margin-left: 5px;">Next</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection




