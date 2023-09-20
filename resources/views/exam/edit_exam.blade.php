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


    <div class="container-fluid mt-3">
        <div class="main-content-container container-fluid px-4">

            <div class="page-header  row no-gutters mb-4">
                <h4 class="page-title">Edit Exam</h4>
                <ul class="breadcrumbs">
                    <li class="nav-home">
                        <a href="#">
                            <i class="flaticon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Exam</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="{{url("/")."/exam/manage"}}">Manage Exam</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="{{url("/")."/exam/".$exam->id."/edit"}}">{{$exam->title}}</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Edit Exam</a>
                    </li>
                </ul>
            </div>


            <!-- End Page Header -->
            <div class="row">
                <div class="col-lg-8 col-md-12">


                    <!-- Add New Post Form -->
                    <div class="card card-small mb-3">
                        <div class="card-body">

                            <a href="{{url("/")."/exam/".$exam->id."/question"}}" class="btn btn-info" role="button">Edit
                                Pertanyaan Quiz</a>
                            <a href="{{url("/")."/exam/".$exam->id."/session"}}"
                               class="btn btn-info" role="button">Edit
                                Sesi Quiz</a>

                            <form action="{{ url('/exam/update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{$exam->id}}">
                                <div class="row">
                                    <div class="col-md-12">

                                        @csrf
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

                                        <div class="form-group d-none">
                                            <h4 class="font-weight-bold">Tanggal Mulai</h4>
                                            <input id="inputTitle" type="datetime-local"
                                                   class="form-control @error('startDate') is-invalid @enderror"
                                                   name="startDate"
                                                   value="{{ old('startDate') }}"
                                                   placeholder="Masukkan Judul Exam/Quiz">
                                        </div>

                                        <div class="form-group d-none">
                                            <h4 class="font-weight-bold">Tanggal Selesai</h4>
                                            <input id="inputTitle" type="datetime-local"
                                                   class="form-control @error('endDate') is-invalid @enderror"
                                                   name="endDate"
                                                   value="{{ old('endDate') }}" placeholder="Masukkan Judul Exam/Quiz">
                                            <p>Kosongkan Tanggal Jika Quiz bisa diakses kapan saja</p>

                                        </div>


                                        <div class="form-check d-none">
                                            <label class="form-check-label">
                                                <input class="form-check-input" name="randomize" type="checkbox"
                                                       value="n">
                                                <span class="form-check-sign">Acak Soal ?</span>
                                            </label>
                                        </div>

                                        <div class="form-check d-none">
                                            <label class="form-check-label">
                                                <input class="form-check-input" name="can_access" type="xcheckbox"
                                                       value="n">
                                                <span class="form-check-sign">Apakah Exam bisa diakses siswa ?</span>
                                            </label>
                                        </div>

                                        <div class="form-group d-none">
                                            <h4 class="font-weight-bold">Instruksi</h4>
                                            <p>Masukkan Instruksi Quiz/Exam disini</p>
                                            <textarea
                                                class="form-control ckeditor @error('instruction') is-invalid @enderror"
                                                name="instruction" rows="5"
                                                placeholder="">{{ old('content') }}</textarea>

                                            <!-- error message untuk content -->
                                            @error('instruction')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Judul Exam</label>
                                    <input id="inputTitle" type="text"
                                           class="form-control @error('title') is-invalid @enderror" name="title"
                                           value="{{ old('title',$exam->title) }}"
                                           placeholder="Masukkan Judul Exam/Quiz">

                                    <!-- error message untuk title -->
                                    @error('title')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <h4 class="font-weight-bold">Deskripsi Exam</h4>
                                    <textarea
                                        class="form-control  @error('content') is-invalid @enderror"
                                        name="description" rows="5"
                                        placeholder="Deskripsi Exam">{{ old('content',$exam->description) }}</textarea>

                                    <!-- error message untuk content -->
                                    @error('content')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-md btn-primary">SIMPAN</button>
                                    <button type="reset" class="btn btn-md btn-warning">RESET</button>
                                </div>


                            </form>
                        </div>
                    </div>

                </div>
                {{-- Side Bar --}}
                <div class="col-lg-4 col-md-12">
                    {{-- Card Preview --}}
                    <div class="card card-post card-round">
                        <img class="card-img-top" id="imgPreview"
                             src="{{$exam->img_full_path}}" alt="Card image cap">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="avatar">
                                    <img src="{{ Storage::url('public/profile/'). Auth::user()->profile_url }}"
                                         alt="..."
                                         class="avatar-img rounded-circle">
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
                                    Judul Quiz Anda Ditampilkan Disini
                                </a>
                            </h3>
                            <p class="card-text">Deskripsi Exam.</p>
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

                    <!-- / Post Overview -->
                </div>
            </div>


        </div>
    </div>


    <script>
        // Interactive JavaScript
        const addQuestionBtn = document.getElementById('addQuestionBtn');
        const questionsContainer = document.getElementById('questionsContainer');

        addQuestionBtn.addEventListener('click', () => {
            // Create question container
            const questionDiv = document.createElement('div');
            questionDiv.classList.add('question');

            // Add question inputs (question_text, question_type)
            questionDiv.innerHTML = `
            <input type="text" name="questions[][question_text]" placeholder="Question" required>
            <select name="questions[][question_type]" required>
                <option value="multiple_choice">Multiple Choice</option>
                <option value="multi_select">Multi-Select</option>
                <option value="essay">Essay</option>
            </select>
            <button type="button" class="addChoiceBtn">+ Add Choice</button>
            <div class="choicesContainer"></div>
              `;

            // Add question container to questionsContainer
            questionsContainer.appendChild(questionDiv);

            // Add choice button logic
            const addChoiceBtn = questionDiv.querySelector('.addChoiceBtn');
            const choicesContainer = questionDiv.querySelector('.choicesContainer');

            addChoiceBtn.addEventListener('click', () => {
                // Create choice input
                const choiceInput = document.createElement('input');
                choiceInput.type = 'text';
                choiceInput.name = 'choices[][][choice_text]';
                choiceInput.placeholder = 'Choice';
                choicesContainer.appendChild(choiceInput);
            });
        });
    </script>

@endsection
