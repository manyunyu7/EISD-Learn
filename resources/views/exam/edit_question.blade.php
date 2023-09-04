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




    <form id="questionForm">
        <div class="container-fluid">
            <div class="main-content-container container-fluid px-4">

                <!-- Page Header -->
                <div class="page-header row no-gutters mb-4 mt-5">
                    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                        <span class="text-uppercase page-subtitle">Exam : {{$exam->title}}</span>
                        <h3 class="page-title">Question & Answer</h3>
                    </div>
                </div>

                <div class="mt-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{url("/")."/exam/manage"}}">Exam</a></li>
                            <li class="breadcrumb-item"><a href="">{{$exam->title}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pertanyaan & Jawaban</li>
                        </ol>
                    </nav>
                </div>

                <!-- End Page Header -->
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <!-- Add New Post Form -->
                        <div class="card card-small mb-3">
                            <div class="card-body">
                                    <input type="hidden" name="exam_id" value="{{ $exam->id }}">
                                    <input type="hidden" name="question_id" value="{{ $question->id }}">

                                    <div class="form-group">
                                        <img class="rounded" id="imgPreview" src=""
                                             style="max-height: 300px; max-width: 100%;">
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">GAMBAR</label>
                                        <input id="input-image" type="file" onchange="previewPhoto()"
                                               class="form-control @error('image') is-invalid @enderror"
                                               name="image"
                                               accept="image/*">

                                        <!-- error message untuk title -->
                                        @error('image')
                                        <div class="alert alert-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Jenis Pertanyaan</label>
                                        <select id="questionType" class="form-control" name="questionType">
                                            <option @if($question->question_type=="multiple_choice") selected
                                                    @endif value="multiple_choice">Pilihan Ganda
                                            </option>
                                            <option @if($question->question_type=="essay") selected
                                                    @endif value="essay">Essay
                                            </option>
                                            <option value="true_false">True/False</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Pertanyaan</label>
                                        <input type="text" class="form-control" value="{{$question->question}}"
                                               name="title"
                                               placeholder="Masukkan Pertanyaan">
                                    </div>

                                    <div id="choices" style="
                                    @if($question->question_type!="multiple_choice")
                                    display: none;"
                                        @endif>
                                        <div class="form-group">
                                            @if(is_array($choices))
                                                @forelse($choices as $choice)
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Pilihan</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="choice[]"
                                                                   placeholder="Pilihan" value="{{ $choice['text'] }}">
                                                            <input type="number" class="form-control" name="score[]"
                                                                   placeholder="Skor" value="{{ $choice['score'] }}">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-danger remove-choice">
                                                                    Hapus
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p>No choices available.</p>
                                                @endforelse
                                            @else
                                                <p>Choices is not an array.</p>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-primary btn-border add-choice">Tambah
                                            Pilihan
                                        </button>
                                    </div>

                                    <div id="recommendation" style="display: none;">
                                        <div class="alert alert-info">Rekomendasi: Gunakan pertanyaan True/False untuk
                                            pertanyaan dengan dua pilihan yang jelas (benar/salah).
                                        </div>
                                    </div>

                                    <div id="essay" style="display: none;">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Jawaban Essay</label>
                                            <textarea class="form-control" name="essay_answer" rows="5"
                                                      placeholder="Masukkan Jawaban Essay"></textarea>
                                        </div>
                                    </div>

                                    <button id="saveQuestion" type="submit" class="btn btn-success mt-5">Simpan
                                        Pertanyaan
                                    </button>
                            </div>
                        </div>

                    </div>


                    {{-- Side Bar --}}
                    <div class="col-lg-4 col-md-12 d-none">
                        {{-- Card Preview --}}
                        <div class="card card-post card-round">
                            <img class="card-img-top" id="imgPreview"
                                 src="{{ asset('atlantis/examples') }}/assets/img/blogpost.jpg" alt="Card image cap">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="avatar">
                                        <img src="{{ Storage::url('public/profile/') . Auth::user()->profile_url }}"
                                             alt="..." class="avatar-img rounded-circle">
                                    </div>
                                    <div class="info-post ml-2">
                                        <p class="username">{{ Auth::user()->name }}</p>
                                        <p class="date text-muted">20 Jan 18</p>
                                    </div>
                                </div>
                                <div class="separator-solid"></div>
                                <p class="card-category text-info mb-1"><a href="#">Design</a></p>
                                <h3 class="card-title" id="previewName">
                                    <a href="#">
                                        Judul Blog Anda Ditampilkan Disini
                                    </a>
                                </h3>
                                <p class="card-text">Some quick example text to build on the card title and make up the
                                    bulk
                                    of the card's content.</p>
                                <a href="#" class="btn btn-primary btn-rounded btn-sm">Read More</a>
                            </div>
                        </div>

                        <!-- Post Overview -->
                        <div class='card card-small mb-3 d-none'>
                            <div class="card-header border-bottom">
                                <h6 class="m-0">Preview</h6>
                            </div>
                            <div class='card-body'>
                                <p class="card-text">Ketik tag dan enter untuk memasukkan tag blog</p>
                                <div class="form-group">
                                    <input type="text" id="tagsinput" class="form-control" value="Blog"
                                           data-role="tagsinput">
                                </div>
                            </div>
                        </div>
                        <!-- / Post Overview -->
                        <!-- Post Overview -->
                        <div class='card card-small mb-3'>
                            <div class="card-header border-bottom">
                                <h6 class="m-0">Categories</h6>
                            </div>
                            <div class='card-body p-0'>
                                <div class="container">


                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item px-3 pb-2 row">
                                            <div class="custom-control custom-checkbox mb-1 col-12">
                                                <input type="checkbox" name="category[]" value="others"
                                                       class="custom-control-input" id="category1" checked>
                                                <label class="custom-control-label" for="category1">Lain-lain</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mb-1 1 col-12">
                                                <input type="checkbox" name="category[]" value="design"
                                                       class="custom-control-input" id="category2">
                                                <label class="custom-control-label" for="category2">Design</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mb-1 1 col-12">
                                                <input type="checkbox" name="category[]" value="3d"
                                                       class="custom-control-input" id="category3">
                                                <label class="custom-control-label" for="category3">3D Modelling</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mb-1">
                                                <input type="checkbox" name="category[]" value="office"
                                                       class="custom-control-input" id="category4">
                                                <label class="custom-control-label" for="category4">Office</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mb-1">
                                                <input type="checkbox" name="category[]" value="multiplatform"
                                                       class="custom-control-input" id="category5">
                                                <label class="custom-control-label" for="category5">Multiplatform
                                                    Programming</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mb-1">
                                                <input type="checkbox" name="category[]" value="front_end"
                                                       class="custom-control-input" id="category6">
                                                <label class="custom-control-label" for="category6">Front-End
                                                    App</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mb-1">
                                                <input type="checkbox" name="category[]" value="back_end"
                                                       class="custom-control-input" id="category7">
                                                <label class="custom-control-label" for="category7">Back-end App</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mb-1">
                                                <input type="checkbox" name="category[]" value="fullstack"
                                                       class="custom-control-input" id="category8">
                                                <label class="custom-control-label" for="category8">Fullstack</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mb-1">
                                                <input type="checkbox" name="category[]" value="mobile"
                                                       class="custom-control-input" id="category9">
                                                <label class="custom-control-label" for="category9">Mobile</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- / Post Overview -->
                    </div>
                </div>
                <script>
                    $(document).ready(function () {
                        // Function to handle the removal of choice
                        function handleRemoveChoice() {
                            $(this).closest('.form-group').remove();
                        }

                        // Event delegation for the "remove-choice" buttons
                        $(document).on('click', '.remove-choice', handleRemoveChoice);

                        // Rest of your code
                        // ...
                    });
                </script>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                <script>

                    var loaderOverlay = document.getElementById('loaderOverlay');
                    var questionForm = document.getElementById('questionForm');

                    // Function to show the loading overlay
                    function showLoaderOverlay() {
                        if (loaderOverlay) {
                            loaderOverlay.style.display = '';
                        }
                    }

                    // Function to hide the loading overlay
                    function hideLoaderOverlay() {
                        setTimeout(function () {
                            if (loaderOverlay) {
                                loaderOverlay.style.display = 'none';
                            }
                        }, 1000);
                    }

                    hideLoaderOverlay();
                    document.addEventListener('DOMContentLoaded', function () {
                        var updateQuestionButton = document.getElementById('saveQuestion');

                        // Attach a click event handler to the 'abcdButton' element
                        updateQuestionButton.addEventListener('click', function (e) {
                            e.preventDefault(); // Prevent the default form submission
                            showLoaderOverlay(); // Show the loading overlay

                            var formData = new FormData(questionForm);

                            // Send an AJAX request to save the question
                            fetch('/exam/update-question', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                                .then(function (response) {
                                    hideLoaderOverlay(); // Hide the loading overlay once the request is complete

                                    if (response.ok) {
                                        // Question saved successfully, show a success message with SweetAlert
                                        setTimeout(function () {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Success',
                                                text: 'Question saved successfully',
                                            }).then(function () {
                                                // Close the window after dismissing the SweetAlert
                                                window.close();
                                            });
                                        }, 1000);
                                    } else {
                                        // Handle any errors here and show an error message with SweetAlert
                                        setTimeout(function () {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: 'Error saving question',
                                            }).then(function () {
                                                // Close the window after dismissing the SweetAlert
                                                window.close();
                                            });
                                        }, 1000);
                                    }
                                })
                                .catch(function (error) {
                                    hideLoaderOverlay(); // Hide the loading overlay in case of an error
                                    console.error('Error:', error);

                                    // Show an error message with SweetAlert
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'An error occurred while saving the question',
                                    });
                                });
                        });

                        // Rest of your JavaScript code
                    });
                    document.addEventListener('DOMContentLoaded', function () {
                        var questionTypeSelect = document.getElementById('questionType');
                        var choicesDiv = document.getElementById('choices');
                        var recommendationDiv = document.getElementById('recommendation');
                        var essayDiv = document.getElementById('essay');

                        questionTypeSelect.addEventListener('change', function () {
                            var selectedOption = questionTypeSelect.value;
                            if (selectedOption === 'multiple_choice') {
                                choicesDiv.style.display = 'block';
                                recommendationDiv.style.display = 'none';
                                essayDiv.style.display = 'none';
                            } else if (selectedOption === 'true_false') {
                                choicesDiv.style.display = 'none';
                                recommendationDiv.style.display = 'block';
                                essayDiv.style.display = 'none';
                            } else if (selectedOption === 'essay') {
                                choicesDiv.style.display = 'none';
                                recommendationDiv.style.display = 'none';
                                essayDiv.style.display = 'block';
                            }
                        });

                        var addChoiceButton = document.querySelector('.add-choice');
                        var choicesContainer = document.getElementById('choices');

                        addChoiceButton.addEventListener('click', function () {
                            var choiceDiv = document.createElement('div');
                            choiceDiv.className = 'form-group';
                            choiceDiv.innerHTML = `
                    <label class="font-weight-bold">Pilihan</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="choice[]" placeholder="Pilihan">
                        <input type="number" class="form-control" name="score[]" placeholder="Skor">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger remove-choice">Hapus</button>
                        </div>
                    </div>
                `;

                            choicesContainer.appendChild(choiceDiv);

                            var removeChoiceButton = choiceDiv.querySelector('.remove-choice');

                            removeChoiceButton.addEventListener('click', function () {
                                choicesContainer.removeChild(choiceDiv);
                            });
                        });
                    });
                </script>

            </div>
        </div>
    </form>
@endsection


@section("script")



@endsection
