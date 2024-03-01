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
                <li class="breadcrumb-item"><a href={{url('/exam/manage-exam-v2')}}>Exam</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Exam</li>
            </ol>
        </nav>
    </div>

    <div class="page-inner">
        {{-- SOAL UJIAN --}}
        <div class="container load-soal" style="background-color: white">
            <div class="page-header">
                <h2><b>Soal Ujian ExamID: {{ $examId }}</b></h2>
            </div>
            <form id="addSessionForm" method="post" action="{{ route('store.question') }}" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <input hidden name="exam_id" type="text" value="{{ $examId }}">
                
                <div class="mb-3">
                    <label for="" class="mb-2">Soal<span style="color: red">*</span></label>
                    <div class="input-group mb-3">
                        <input required name="question" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="" class="mb-2">Gambar Soal</label>
                    <div class="mb-3">
                        <input name="question_images" class="form-control" type="file" id="formFileMultiple" multiple>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="" class="mb-2">Jenis Soal<span style="color: red">*</span></label>
                    <div class="input-group mb-3">
                        <select required name="type_questions" class="form-control form-select-lg" aria-label="Default select example">
                            <option value="" disabled selected>Pilih jenis soal</option>
                            <option value="Multiple Choice">Multiple Choice</option>
                            <option value="Single Multiple Choice">Single Multiple Choice</option>
                        </select>
                    </div>
                </div>

                {{-- INPUT PILIHAN JAWABAN --}}
                <div class="card" style="border-block: 1px">
                    <div class="card-header" style="background-color:rgb(44, 84, 108); color: white">
                        <h3>Input Pilihan Jawaban</h3>
                    </div>
                    <div class="card-body" style="background-color:rgb(186, 215, 233)">
                        <div class="row " id="segment_multipleChoices">
                            <div class="input-group mb-3" style="background-color:antiquewhite">
                                <input readonly style="text-align: center" width="50%" type="text" value="Statements" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <input readonly style="text-align: center" width="50%" type="text" value="Scores" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                            </div>
                        </div>
                        <div class="row " id="segment_multipleChoices">
                            <div class="input-group mb-1">
                                <input required name="stm_1" width="50%" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <input required name="scr_1" width="50%" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                            </div>
                            <div class="input-group mb-1">
                                <input required name="stm_2" width="50%" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <input required name="scr_2" width="50%" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                            </div>
                            <div class="input-group mb-1">
                                <input required name="stm_3" width="50%" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <input required name="scr_3" width="50%" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                            </div>
                            <div class="input-group mb-1">
                                <input required name="stm_4" width="50%" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <input required name="scr_4" width="50%" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
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




        <div class="container list-soal-temp">
            {{-- MENAMPILKAN SOAL --}}
            @forelse ($questionAnswer as $data)
                @if($data->exam_id == $examId)
                    <div class="card">
                        <div class="card-header" style="background-color: rgb(184, 199, 216)">
                            <b>Soal :</p>
                            <p>{{ $data->question }}</p>

                            @if ($data->image !== null)
                                <div class="text-center">
                                    <img src="{{ Storage::url('public/exam/question/' . $data->image) }}" 
                                        style="width: auto; height:350px"
                                        class="rounded" 
                                        alt="...">
                                </div>
                            @endif
                        </div>
                       
                        <ul class="list-group list-group-flush">
                            @php
                                $jsonData = $data->choices;
                                $examQuestionAnswers = json_decode($jsonData, true);
                            @endphp
                            @foreach ($examQuestionAnswers as $answer)
                                <li class="list-group-item">{{ $answer['text'] }}  (Score: {{ $answer['score'] }})</li>
                            @endforeach
                            <li class="list-group-item">
                                <div class="mb-3" style="display: flex; justify-content: flex-end;">
                                    <div style="flex-grow: 1;"></div>
                                    <div style="width: 200px;">
                                        <div class="input-group">
                                            <button type="button" class="btn btn-warning" style="width: 45%; margin-right: 5px;">Edit</button>
                                            <button type="submit" id="saveEditBtn" class="btn btn-danger" style="width: 45%; margin-left: 5px;">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                @else
                @endif
            @empty
            @endforelse
        </div>

    </div>

@endsection




