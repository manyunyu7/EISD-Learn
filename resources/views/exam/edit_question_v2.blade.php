@extends('main.template')

@section('head-section')
    <script src="{{ asset('library/ckeditor/ckeditor.js') }}"></script>
    <style>
        #previewCover {
            object-fit: cover;
            height: 200px;
            width: 100%;
        }

    </style>
@endsection

@section('script')
    {{-- JS-SECTION-B --}}
    <script>
        $('#tagsinput').tagsinput({
            tagClass: 'badge-info'
        });

    </script>
    <script>
        CKEDITOR.replace('content', {
            filebrowserImageBrowseUrl: '/filemanager?type=Images',
            filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: '/filemanager?type=Files',
            filebrowserUploadUrl: '/filemanager/upload?type=Files&_token='
        });

    </script>
@endsection

@section('main')
    <script>
        window.onload = function () {
            // jQuery and everything else is loaded


            var el = document.getElementById('input-image');
            el.onchange = function () {
                var fileReader = new FileReader();
                fileReader.readAsDataURL(document.getElementById("input-image").files[0])
                fileReader.onload = function (oFREvent) {
                    document.getElementById("imgPreview").src = oFREvent.target.result;
                };
            }

            $(document).ready(function () {
                $.myfunction = function () {
                    $("#previewName").text($("#inputTitle").val());
                    var title = $.trim($("#inputTitle").val())
                    if (title == "") {
                        $("#previewName").text("Judul")
                    }
                };

                $("#inputTitle").keyup(function () {
                    $.myfunction();
                });

            });
        }

    </script>

    <div class="container load-soal mb-3" style="background-color: none">
        <br><br>
        <div class="page-header mb-3">
            <h2 style="text-align: center"><b>Edit Soal Ujian</b></h2>
        </div>

        <form id="addSessionForm" method="post" action="{{ route('exam.update-question') }}" enctype="multipart/form-data">
            @csrf
            @method('POST')

            {{-- <input hidden name="exam_id" type="text" value="{{ $examId }}"> --}}
            <input type="hidden" name="exam_id" value="{{ $exam->id }}">
            <input type="hidden" name="question_id" value="{{ $question->id }}">

            <div class="mb-3">
                <label for="" class="mb-2">Soal<span style="color: red">*</span></label>
                <div class="input-group mb-3">
                    <input required name="question" value="{{ $question->question }}" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
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
                        <option value="Multiple Choice" {{ $question->question_type === 'Multiple Choice' ? 'selected' : '' }}>Multiple Choice</option>
                        <option value="Single Multiple Choice" {{ $question->question_type === 'Single Multiple Choice' ? 'selected' : '' }}>Single Multiple Choice</option>
                    </select>
                </div>
            </div>

            <div class="mb-5">
                <div>
                    <button type="button" id="addOptionAnswers" class="btn btn-outline-primary " style="width: 12%; margin-left: 5px;">+ Add</button>
                </div>
                <div class="card-body row " id="segment_multipleChoices">
                    @foreach ($choices as $index => $choice)
                        <div class="input-group mb-3">
                            <input required name="stm_{{$index + 1}}" value="{{$choice['text']}}" placeholder="Masukkan Opsi Jawaban" width="35%" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <input required name="scr_{{$index + 1}}" value="{{$choice['score']}}" placeholder="Masukkan Poin" width="35%" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <button type="button" class="deleteOption btn btn-danger ml-2 "><img src="{{ url('/icons/Delete.svg') }}"  alt="Instagram Icon"></button>
                        </div>
                    @endforeach
                </div>
            </div>
            <script>
                // Event delegation untuk tombol penghapusan
                document.addEventListener("click", function(event) {
                    if (event.target && event.target.classList.contains("deleteOption")) {
                        event.target.closest(".input-group.mb-3").remove();
                    }
                });

                // Event listener untuk tombol +Add
                document.getElementById("addOptionAnswers").addEventListener("click", function() {
                    var deleteIconURL = "{{ url('/icons/Delete.svg') }}";
                    var segmentMultipleChoices = document.getElementById("segment_multipleChoices");
                    var divInputGroupCount = segmentMultipleChoices.querySelectorAll(".input-group.mb-3").length;

                    if (divInputGroupCount < 4) {
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

                        divInputGroup.appendChild(inputOption);
                        divInputGroup.appendChild(inputScore);
                        divInputGroup.appendChild(deleteButton);
                        segmentMultipleChoices.appendChild(divInputGroup);
                    } else {
                        alert("Anda telah mencapai batas maksimal penambahan Opsi Jawaban.");
                    }
                });
            </script>



            {{-- BUTTONS --}}
            <div class="mb-3" style="display: flex; justify-content: flex-end;">
                <div style="flex-grow: 1;"></div>
                <div style="width: 200px;">
                    <div class="input-group mb-3">
                        <button type="button" id="closeEditBtn" class="btn btn-primary" style="width: 45%; margin-right: 5px;">Close</button>
                        <button type="submit" id="saveEditBtn" class="btn btn-success" style="width: 45%; margin-left: 5px;">Update</button>
                    </div>
                </div>
                <script>
                    // Event listener untuk tombol "Cancel"
                    document.getElementById("closeEditBtn").addEventListener("click", function() {
                        // Menutup jendela modal atau kembali ke halaman sebelumnya
                        window.close();
                    });
                </script>
            </div>
        </form>

    </div>

@endsection
{{-- <script>

    document.addEventListener('DOMContentLoaded', function () {
        var choicesDiv = document.getElementById('choices');
        var choicesContainer = document.getElementById('choices');

        choicesContainer.appendChild(choiceDiv);

        var removeChoiceButton = choiceDiv.querySelector('.remove-choice');

        removeChoiceButton.addEventListener('click', function () {
            choicesContainer.removeChild(choiceDiv);
        });
    });
</script> --}}


@section("script")
@endsection
