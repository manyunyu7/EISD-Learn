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
                <li class="breadcrumb-item"><a href={{url('/exam/manage-exam-v2')}}>Exam</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Exam</li>
            </ol>
        </nav>
    </div>

    <div class="page-inner">
        {{-- SETUP QUIZ --}}
        <div class="container container-soal" style="background-color: cyan">
            <div class="page-header">
                <h2><b>SetUp Quiz/Pre-Test/Post-Test/Evaluation</b></h2>
            </div>

            <div class="mb-3">
                <label for="" class="mb-2">Judul Ujian</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                </div>
            </div>
            <div class="mb-3">
                <label for="" class="mb-2">Jenis Ujian</label>
                <div class="input-group mb-3">
                    <select class="form-control form-select-lg" aria-label="Default select example">
                        <option selected>Pilih Jenis Soal</option>
                        <option value="1">Pre Test</option> 
                        <option value="2">Post Test</option>
                        <option value="3">Quiz</option> 
                        <option value="4">Evaluation</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="" class="mb-2">Batas Waktu (Menit)</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                </div>
            </div>
        </div>
        {{-- SOAL UJIAN --}}
        <div class="container container-soal" style="background-color: salmon">
            <div class="page-header">
                <h2><b>Soal Ujian</b></h2>
            </div>
    
            <div class="mb-3">
                <label for="" class="mb-2">Soal</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                </div>
            </div>
            <div class="mb-3">
                <label for="" class="mb-2">Gambar Soal</label>
                <div class="mb-3">
                    <input class="form-control" type="file" id="formFileMultiple" multiple>
                </div>
            </div>
            <div class="mb-3">
                <label for="" class="mb-2">Jumlah Soal</label>
                <div class="input-group mb-3">
                    <select class="form-control form-select-lg" aria-label="Default select example">
                        <option selected>Pilih Jenis Soal</option>
                        <option value="1">Pilihan Ganda</option>
                        <option value="2">Multiple Choice</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

@endsection




