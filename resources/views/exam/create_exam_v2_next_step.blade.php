@extends('main.template')

@section('head-section')
    <!-- Datatables -->
    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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


    <div class="page-inner"  style="background-color: white !important">

        <div class="col-md-12 mt-2">
            {{-- BREADCRUMB --}}
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href={{url('/home')}}>Home</a></li>
                    <li class="breadcrumb-item"><a href={{url('/exam/manage-exam-v2')}}>Exam</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Question</li>
                </ol>
            </nav>
        </div>


        {{-- SOAL UJIAN --}}
        <div class="col-12 ">
            <div class="page-header">
                <h2><b>Soal Ujian</b></h2>
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

                <div class="mb-5">
                    <div>
                        <button type="button" id="addOptionAnswers" class="btn btn-outline-primary " style="width: 12%; margin-left: 5px;">+ Add</button>
                    </div>
                    <div class="card-body row " id="segment_multipleChoices">
                    </div>
                </div>
                <script>
                    document.getElementById("addOptionAnswers").addEventListener("click", function() {
                        var deleteIconURL = "{{ url('/icons/Delete.svg') }}";
                        var segmentMultipleChoices = document.getElementById("segment_multipleChoices");
                        var divInputGroupCount = segmentMultipleChoices.querySelectorAll(".input-group.mb-3").length;

                        if (divInputGroupCount < 5) {
                            var divInputGroup = document.createElement("div");
                            divInputGroup.classList.add("input-group", "mb-3");

                            var inputOption = document.createElement("input");
                            inputOption.required = true;
                            inputOption.name = "stm_" + (divInputGroupCount + 1);
                            inputOption.placeholder = "Masukkan Opsi Jawaban";
                            inputOption.type = "text";
                            inputOption.classList.add("form-control");
                            inputOption.setAttribute("aria-label", "Recipient's username");
                            inputOption.setAttribute("aria-describedby", "basic-addon2");

                            var inputScore = document.createElement("input");
                            inputScore.required = true;
                            inputScore.name = "scr_" + (divInputGroupCount + 1);
                            inputScore.placeholder = "Masukkan Poin";
                            inputScore.type = "text";
                            inputScore.classList.add("form-control");
                            inputScore.setAttribute("aria-label", "Recipient's username");
                            inputScore.setAttribute("aria-describedby", "basic-addon2");

                            var deleteButton = document.createElement("button");
                            deleteButton.classList.add("btn", "btn-danger", "ml-2", "deleteOption");
                            deleteButton.innerHTML = '<img src="' + deleteIconURL + '" alt="Delete Icon">';
                            deleteButton.type = "button";
                            deleteButton.addEventListener("click", function() {
                                divInputGroup.remove();
                            });

                            divInputGroup.appendChild(inputOption);
                            divInputGroup.appendChild(inputScore);
                            divInputGroup.appendChild(deleteButton);
                            segmentMultipleChoices.appendChild(divInputGroup);
                        } else {
                            // alert("Anda telah mencapai batas maksimal penambahan Opsi Jawaban.");
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Anda telah mencapai batas maksimal penambahan Opsi Jawaban.',
                            });
                        }
                    });
                </script>


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


        <div class="col-12">
            {{-- MENAMPILKAN SOAL --}}
            @forelse ($questionAnswer as $data)
                @if($data->exam_id == $examId)
                    <div class="card">
                        <div class="card-header" style="background-color: #eaeaea; color: black">
                            <p>Soal :</p>
                            <p>{{ $data->question }}</p>

                            @if ($data->image !== null)
                                <div class="text-center"> 
                                    <img src="{{ asset('storage/exam/question/'. $data->image) }}"
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
                                <div class="mb-3" style="">
                                    <div ></div>
                                    <div style="width: 200px;">
                                        <div class="input-group ">
                                            {{-- BUTTON MODALS FOR EDIT --}}
                                            <button type="button"
                                                    class="btn btn-primary"
                                                    onclick="openEditWindow('{{ url('exam/question/'.$data->id.'/edit') }}')">
                                                <img src="{{ url('/icons/Edit.svg') }}" alt="Edit Icon">
                                            </button>
                                            <script>
                                                function openEditWindow(url) {
                                                    // Open a new window with the provided URL
                                                    window.open(url, '_blank', 'width=600,height=800,resizable=no');
                                                }
                                            </script>
                                            <form id="delete-post-form" action="{{ url('exam/delete-question-from-db/'. $data->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        id="saveEditBtn"
                                                        data-question-id="{{ $data->id }}"
                                                        class="btn btn-danger pull-left"
                                                        style="width: auto; margin-left: 5px;"
                                                        onclick="return confirm('Are you sure?')">
                                                        <img src="{{ url('/icons/Delete.svg') }}">
                                                </button>
                                            </form>
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




