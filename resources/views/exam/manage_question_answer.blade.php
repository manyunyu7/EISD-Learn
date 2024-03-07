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
                            <li class="breadcrumb-item"><a href="{{url("/")."/exam/".$exam->id."/edit"}}">{{$exam->title}}</a></li>
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

                                <div class="form-group">
                                    <img class="rounded" id="imgPreview" src=""
                                         style="max-height: 300px; max-width: 100%;">
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Gambar Soal</label>
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
                                        <option value="essay">Choose</option>
                                        <option value="multiple_choice_single">Pilihan Ganda (Single)</option>
                                        <option value="multiple_choice">Pilihan Ganda (Multiple Select)</option>
                                        <option class="d-none" value="essay">Essay</option>
                                        <option class="d-none" value="true_false">True/False</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Pertanyaan</label>
                                    <input type="text" class="form-control" name="title"
                                           placeholder="Masukkan Pertanyaan">
                                </div>

                                <div id="choices" style="display: none;">
                                    <div class="form-group">
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

                                <button id="saveQuestion" type="submit" class="btn btn-success mt-5">Simpan Pertanyaan
                                </button>
                            </div>
                        </div>

                        <!-- Modal for editing questions and answers -->
                        <div class="modal fade" id="editQuestionModal" tabindex="-1"
                             aria-labelledby="editQuestionModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editQuestionModalLabel">Edit Question and
                                            Answers</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Edit question and answers form -->
                                        <form id="editQuestionForm">
                                            <div class="mb-3">
                                                <label for="editedQuestion" class="form-label">Question</label>
                                                <textarea class="form-control" id="editedQuestion" name="editedQuestion"
                                                          rows="3"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editedAnswers" class="form-label">Answers</label>
                                                <textarea class="form-control" id="editedAnswers" name="editedAnswers"
                                                          rows="3"></textarea>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close
                                        </button>
                                        <button type="button" class="btn btn-primary" id="saveQuestionChanges">Save
                                            Changes
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Showing Added Questions -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card border-0 shadow rounded">

                                    <div class="card-header">
                                        <div class="card-title">Pertanyaan Yang Ditambahkan</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <button id="refreshQuestions" type="button"
                                                    class="btn btn-primary btn-border btn-round">
                                                Refresh
                                            </button>
                                            <a id="save-order-link"
                                               href="{{url("/")."/exam/".$exam->id."/question-order"}}"
                                               class="btn btn-primary btn-round">Ubah Urutan Soal</a>
                                        </div>

                                        <div id="questions-list" class="mt-5">
                                            <!-- Questions will be displayed here -->
                                        </div>

                                        <script>
                                            // Add an event listener to fetch questions when the page is ready
                                            document.addEventListener('DOMContentLoaded', function () {
                                                fetchQuestions();
                                            });

                                            // Attach a click event handler to the "Refresh" button
                                            $('#refreshQuestions').click(function () {
                                                // Call the function to fetch and display questions
                                                fetchQuestions();
                                            });

                                            function fetchQuestions() {
                                                // Show the loading overlay when the fetch starts
                                                showLoaderOverlay();
                                                var url = window.location.origin + "/exam/mquestions?id=" + <?= $exam->id ?>;
                                                fetch(url) // Replace with your Laravel route
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        // Hide the loading overlay when the data is loaded
                                                        hideLoaderOverlay();
                                                        displayQuestions(data);
                                                    })
                                                    .catch(error => {
                                                        console.error('Error fetching questions:', error);
                                                        // Hide the loading overlay in case of an error
                                                        hideLoaderOverlay();
                                                    });
                                            }

                                            // Function to display questions in the view
                                            function displayQuestions(questions) {
                                                var questionsList = document.getElementById('questions-list');
                                                questionsList.innerHTML = ''; // Clear previous questions

                                                var questionIds = []; // Create an array to store the question IDs in the new order

                                                questions.forEach(function (question) {
                                                    var questionCard = document.createElement('div');
                                                    questionCard.className = 'card mb-3';

                                                    // Parse the choices JSON string into an array
                                                    var choices = JSON.parse(question.choices);

                                                    // Create the HTML structure for displaying the question and its choices
                                                    var questionHTML = '<div class="card-body">' +
                                                        '<h5 class="card-title mb-3">Question: ' + question.question + '</h5>';

                                                    var questionType = "";
                                                    if (question.question_type === "multiple_choice_single") {
                                                        questionType = "Pilihlah satu jawaban yang benar";
                                                    } else if (question.question_type === "multiple_choice") {
                                                        questionType = "Pilihlah jawaban-jawaban yang anda anggap benar";
                                                    }

                                                    // Check if the question has choices
                                                    if (choices && choices.length > 0) {
                                                        questionHTML += '<p class="small">' + questionType + '</p>'; // Adding questionType as "small"
                                                        questionHTML += '<ul class="list-group">';
                                                        choices.forEach(function (choice) {
                                                            questionHTML += '<li class="list-group-item">' + choice.text + ' (Score: ' + choice.score + ')</li>';
                                                        });
                                                        questionHTML += '</ul>';
                                                    }

                                                    // Add an "Edit" button for each question
                                                    questionHTML += '<button type="button" class="mt-5 btn btn-primary btn-border edit-question-button mr-2" data-question-id="' + question.id + '">Edit</button>';

                                                    questionHTML += '<button type="button" class="mt-5 btn btn-danger btn-border delete-question-button" data-question-id="' + question.id + '" data-toggle="modal">Hapus Pertanyaan</button>';

                                                    questionCard.innerHTML = questionHTML;

                                                    // Add the question's ID as a data attribute to the questionCard
                                                    questionCard.setAttribute('data-question-id', question.id);

                                                    questionsList.appendChild(questionCard);

                                                    // Add the question's ID to the array
                                                    questionIds.push(question.id);
                                                });

                                                // Function to open the specific URI in a new window
                                                function openNewWindow(questionId) {
                                                    // Get the base URL
                                                    var baseUrl = window.location.origin;

                                                    // Construct the URL for the specific question's edit page
                                                    var url = baseUrl + '/exam/question/' + questionId + '/edit';

                                                    // Open the URL in a new window
                                                    var newWindow = window.open(url, '_blank', 'width=500,height=500');

                                                    // Check if the new window is closed at regular intervals
                                                    var interval = setInterval(function () {
                                                        if (newWindow.closed) {
                                                            // The new window is closed, trigger a questions refres
                                                            fetchQuestions()
                                                            clearInterval(interval); // Stop the polling
                                                        }
                                                    }, 1000); // Check every 1 second (adjust as needed)
                                                }

                                                // Attach a click event listener to each "Edit" button
                                                document.querySelectorAll('.edit-question-button').forEach(function (editButton) {
                                                    editButton.addEventListener('click', function () {
                                                        // Get the question ID from the button's data attribute
                                                        var questionId = this.getAttribute('data-question-id');

                                                        // Open the specific URI in a new window
                                                        openNewWindow(questionId);
                                                    });
                                                });
                                                // Assign the question IDs to the data-question-id attribute for sorting
                                                questionIds.forEach(function (id, index) {
                                                    questionsList.children[index].setAttribute('data-question-id', id);
                                                });
                                            }

                                            // Attach a click event handler to the "Refresh" button
                                            var refreshButton = document.getElementById('refreshQuestions');
                                            // Add click event handler for the "Save Order" button
                                            document.getElementById('save-order-button').addEventListener('click', function () {
                                                var newOrder = $('#questions-list').sortable('toArray', {attribute: 'data-question-id'});
                                                saveQuestionOrder(newOrder);
                                            });
                                            refreshButton.addEventListener('click', function () {
                                                fetchQuestions(); // Refresh the list of questions when the button is clicked
                                            });

                                            // Fetch questions using fetch when the page loads
                                            window.addEventListener('load', function () {
                                                fetchQuestions();
                                            });

                                            // Function to save the order
                                            function saveQuestionOrder(newOrder) {
                                                // Show the loading overlay before making the fetch request
                                                showLoaderOverlay();

                                                // Get the CSRF token from the <meta> tag
                                                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                                                fetch('/exam/update-question-order', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
                                                    },
                                                    body: JSON.stringify({newOrder: newOrder}),
                                                })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        // Hide the loading overlay when the data is received
                                                        hideLoaderOverlay();
                                                        console.log(data.message);
                                                    })
                                                    .catch(error => {
                                                        console.error('Error updating question order:', error);
                                                        // Hide the loading overlay in case of an error
                                                        hideLoaderOverlay();
                                                    });
                                            }
                                        </script>
                                    </div>
                                </div>
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
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

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
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                <script>

                    var questionForm = document.getElementById('questionForm');
                    // Get the base URL programmatically
                    const baseUrl = window.location.protocol + '//' + window.location.host;

                    hideLoaderOverlay();
                    document.addEventListener('DOMContentLoaded', function () {
                        // Event listener for showing the modal when a delete button is clicked
                        // Event listener for showing the confirmation dialog when a delete button is clicked
                        document.addEventListener('click', function (e) {
                            if (e.target && e.target.classList.contains('delete-question-button')) {
                                const questionId = e.target.getAttribute('data-question-id');
                                // Show the SweetAlert confirmation dialog with the question ID
                                Swal.fire({
                                    title: 'Are you sure?',
                                    // text: `Do you want to delete question with ID: ${questionId}?`,
                                    text: `Do you want to delete this question ?`,
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#d9534f',
                                    cancelButtonColor: '#5bc0de',
                                    confirmButtonText: 'Yes, delete it!',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Update the URL to the desired endpoint by combining the base URL and endpoint
                                        const deleteUrl = `${baseUrl}/exam/question/${questionId}/delete`;
                                        // Perform a fetch request to delete the question using its ID
                                        showLoaderOverlay(); // Show the loading overlay
                                        fetch(deleteUrl, {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            }
                                        })
                                            .then((response) => {
                                                if (response.ok) {
                                                    // Reload the page or update the UI as needed
                                                    fetchQuestions();
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Success',
                                                        text: 'Question deleted successfully',
                                                    });
                                                    hideLoaderOverlay()
                                                    fetchQuestions();
                                                } else {
                                                    // Handle errors here
                                                    console.error('Error deleting question');
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Error',
                                                        text: 'Error deleting question',
                                                    });
                                                }
                                            })
                                            .catch((error) => {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Error',
                                                    text: 'Error deleting question',
                                                });
                                                hideLoaderOverlay()
                                                console.error('Fetch error:', error);
                                            });
                                    }
                                });
                            }
                        });


                        var storeQuestionButton = document.getElementById('saveQuestion');
                        // Attach a click event handler to the 'abcdButton' element
                        storeQuestionButton.addEventListener('click', function (e) {
                            e.preventDefault(); // Prevent the default form submission
                            showLoaderOverlay(); // Show the loading overlay

                            var formData = new FormData(questionForm);

                            // Send an AJAX request to save the question
                            fetch('/exam/save-question', {
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
                                            });
                                        }, 1000);
                                        fetchQuestions()
                                    } else {
                                        // Handle any errors here and show an error message with SweetAlert
                                        setTimeout(function () {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: 'Error saving question',
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
                            }else if (selectedOption === 'multiple_choice_single') {
                                choicesDiv.style.display = 'block';
                                recommendationDiv.style.display = 'none';
                                essayDiv.style.display = 'none';
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
