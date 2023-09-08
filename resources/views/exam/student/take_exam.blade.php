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
    <div class="container-fluid">
        <div class="main-content-container container-fluid px-4">

            <!-- Page Header -->
            <div class="page-header row no-gutters mb-4 mt-5">
                <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                    <span class="text-uppercase page-subtitle">Exam Session : {{$exam->title}}</span>
                    <h3 class="page-title">Question & Answer</h3>
                </div>
            </div>

            <!-- Timer -->
            <div id="floating-timer" class="timer-container" style="display: none">
                <div id="timer" class="timer">00:00:00</div>
            </div>

            {{--            <div class="mt-4">--}}
            {{--                <nav aria-label="breadcrumb">--}}
            {{--                    <ol class="breadcrumb">--}}
            {{--                        <li class="breadcrumb-item"><a href="#">Home</a></li>--}}
            {{--                        <li class="breadcrumb-item"><a href="{{url("/")."/exam/manage"}}">Exam</a></li>--}}
            {{--                        <li class="breadcrumb-item"><a href="">{{$exam->title}}</a></li>--}}
            {{--                        <li class="breadcrumb-item"><a href="{{url("/")."/exam/".$exam->id."/session"}}">Session</a>--}}
            {{--                        </li>--}}
            {{--                        <li class="breadcrumb-item active" aria-current="page">Pertanyaan & Jawaban</li>--}}
            {{--                    </ol>--}}
            {{--                </nav>--}}
            {{--            </div>--}}

            <section id="exam-information">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Quiz : {{$exam->title}}</h3>
                                {{--                                <h3 class="card-subtitle">Sesi : {{$session->id}}</h3>--}}
                                <hr>

                                <img width="300px" src="http://0.0.0.0:5253/home_assets/img/esd_3.png" alt="">


                                <div class="form-group">
                                    <label for="fullName">Nama Peserta Quiz :</label>
                                    <input type="text" id="fullName" name="fullName" class="form-control" placeholder="Enter your full name" required
                                           @auth
                                               value="{{ Auth::user()->name }}"
                                        @endauth
                                    >
                                </div>

                                <!-- Subject -->
                                <div class="form-group d-none">
                                    <label>Nama Quiz:</label>
                                    <span>{{$exam->title}}</span>
                                </div>

                                <!-- Time -->
                                <div class="form-group">
                                    <label>Waktu Ujian (Menit):</label>
                                    <span>{{$session->time_limit_minute}}</span>
                                </div>

                                <!-- Number of Questions -->
                                <div class="form-group">
                                    <label>Jumlah Pertanyaan :</label>
                                    <span>{{$question_count}}</span>
                                </div>

                                <!-- Instruktsi -->
                                <div class="form-group">
                                    <label>Petunjuk Quiz :</label>
                                    <div>{{$session->instruction}}</div>
                                </div>

                                <!-- Skor Ujian -->
                                <div class="form-group">
                                    <label>Skor Maksimal :</label>
                                    <div>{{$totalScore}}</div>
                                </div>

                                <!-- Student Name -->
                                <div class="form-group d-none">
                                    <label>Student Name:</label>
                                    <span>John Doe</span>
                                </div>

                                <!-- Additional Fields (Example: Date, Instructor, Instructions, etc.) -->
                                <div class="form-group">
                                    <label>Good Luck</label>
                                    <span></span>
                                </div>

                                <!-- Start Exam Button -->
                                <button id="startExamButton" type="button"
                                        class="btn btn-primary btn-border btn-round mb-3 mt-3"
                                        data-toggle="modal" data-target="#confirmationModal">
                                    Start Exam
                                </button>

                                <div class="alert alert-primary d-none" role="alert">
                                    <h4 class="alert-heading"></h4>
                                    <p>Klik <strong>tombol kirim</strong>, setelah ujian selesai, perhatikan sisa waktu
                                        yang tersedia<strong></strong></p>
                                    <hr>
                                </div>

                                <!-- Confirmation Modal -->
                                <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog"
                                     aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmationModalLabel">Start Exam
                                                    Confirmation</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to start the exam?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Cancel
                                                </button>
                                                <button id="confirmStartButton" type="button" data-dismiss="modal"
                                                        class="btn btn-primary">
                                                    Start Exam
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Add more additional fields as needed -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="exam-end" style="display: none;">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Waktu Habis</h3>
                                {{-- <h3 class="card-subtitle">Sesi : {{$session->id}}</h3> --}}
                                <hr>
                                <div class="form-group">
                                    <label>Quiz Selesai, Terima kasih sudah mengikuti kuis {{$exam->title}}, jawaban anda telah disimpan dan terkirim</label>
                                </div>

                                <script>
                                    function goBack() {
                                        window.history.back();
                                    }
                                </script>
                                <!-- Button to go back to the previous page -->
                                <a href="javascript:void(0);" onclick="goBack()" class="btn btn-primary btn-outlined">Go Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <script>
                // JavaScript for handling the Start Exam button and timer
                document.addEventListener('DOMContentLoaded', function () {

                });
            </script>

            <section id="exam-area" style="display: none;">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <!-- Showing Add©∫ed Questions -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-primary border-0 shadow rounded">
                                    <div class="card-header">
                                        <div class="card-title">Pertanyaan</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <button id="refreshQuestions" type="button"
                                                    class="btn btn-primary d-none btn-border btn-round">
                                                Refresh
                                            </button>
                                        </div>

                                        <section id="question-list-section">
                                            <form id="question-form"> <!-- Wrap the questions with a form -->

                                                <div id="questions-list" class="">
                                                    <!-- Questions will be displayed here -->
                                                </div>
                                                <div id="pagination" class="text-center mt-4 d-none">
                                                    <button id="prevButton" class="btn btn-primary mr-2">Previous
                                                    </button>
                                                    <button id="nextButton" class="btn btn-primary">Next</button>
                                                </div>
                                                <button type="submit" class="btn btn-dark btn-xl mt-2">Submit Answers
                                                </button> <!-- Add a submit button -->
                                            </form>
                                        </section>

                                        <div class="mb-3">
                                            <button id="finishExam" type="button"
                                                    class="btn btn-primary btn-border btn-round">
                                                Refresh
                                            </button>
                                        </div>
                                        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.3.0"></script>
                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                                        <script>
                                            // Define global variables to keep track of the current page and questions per page.
                                            var currentPage = 1;
                                            var questionsPerPage = 9999999999999999; // Change this value to the desired number of questions per page.
                                            var questionsData = null; // To store the fetched questions data
                                            let timerInterval; // Declare a variable to hold the interval ID

                                            // Add an event listener to fetch questions when the page is ready
                                            document.addEventListener('DOMContentLoaded', function () {
                                                fetchQuestions();

                                                function startTimer() {
                                                    // Set up the interval and store the interval ID in timerInterval
                                                    timerInterval = setInterval(updateTimer, 1000);
                                                }

                                                function stopTimer() {
                                                    // Clear the interval when the time is up or when needed
                                                    clearInterval(timerInterval);
                                                }


                                                const startExamButton = document.getElementById('startExamButton');
                                                const confirmStartButton = document.getElementById('confirmStartButton');
                                                const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                                                const examArea = document.getElementById('exam-area'); // Get the exam-area section
                                                const timerContainer = document.getElementById('timer-container'); // Get the timer container
                                                const timerElement = document.getElementById('timer');

                                                // Check if $session->time_limit_minutes is defined and not null, and provide a default value of 0 if it's not defined
                                                const timeLimitMinutes = {{ $session->time_limit_minute ?? 3600 }};
                                                let timeLimitSeconds = timeLimitMinutes * 60; // Convert minutes to seconds
                                                let timeLeft = timeLimitSeconds;


                                                let isTimerTriggered = false;

                                                // Function to update the timer
                                                function updateTimer() {
                                                    const hours = Math.floor(timeLeft / 3600);
                                                    const minutes = Math.floor((timeLeft % 3600) / 60);
                                                    const seconds = timeLeft % 60;

                                                    let timerText;

                                                    if (hours > 0) {
                                                        timerText = `${String(hours).padStart(2, '0')} jam, ${String(minutes).padStart(2, '0')} menit, ${String(seconds).padStart(2, '0')} detik`;
                                                    } else {
                                                        timerText = `${String(minutes).padStart(2, '0')} menit, ${String(seconds).padStart(2, '0')} detik`;
                                                    }

                                                    // Display the timer and remaining time
                                                    timerElement.textContent = `Waktu tersisa ${timerText} lagi.`;

                                                    // Change the timer color to red if time is less than 5 minutes
                                                    if (timeLeft < 300) {
                                                        timerElement.style.color = 'red';
                                                    }

                                                    // Check if time has run out
                                                    if (timeLeft === 0) {
                                                        // Handle time's up logic here
                                                        timerElement.textContent = 'Time\'s up!';

                                                        if (!isTimerTriggered) {
                                                            const startExamButton = document.getElementById('startExamButton');
                                                            const examArea = document.getElementById('exam-area'); // Get the exam-area section
                                                            const examEndArea = document.getElementById('exam-end'); // Get the exam-area section
                                                            examArea.style.display = 'none'; // Show the exam-area
                                                            // Start the exam here, e.g., show the exam-area and the timer
                                                            examEndArea.style.display = 'block'; // Show the exam-area
                                                            startExamButton.style.display = 'none'; // Show the exam-area
                                                            submitAnswer(true, false);
                                                            isTimerTriggered = true;

                                                            // Set the "display" property to "none" to hide the entire section
                                                            console.log("Waktu Habis Gan")
                                                        }



                                                        // Stop the timer when time is up
                                                        stopTimer();
                                                    } else {
                                                        timeLeft--;
                                                    }
                                                }

                                                // Add click event handler for the Start Exam button
                                                startExamButton.addEventListener('click', function () {
                                                    confirmationModal.show();
                                                });

                                                // Add click event handler for the Confirm Start button
                                                confirmStartButton.addEventListener('click', function () {
                                                    startExamButton.style.display = 'none'; // Show the exam-area
                                                    submitAnswer(false,true)
                                                });

                                                function submitAnswer(isFinished,isInitial) {
                                                    var examId = {{ $exam->id }};
                                                    var sessionId = {{ $session->id }};
                                                    var showResultOnEnd = "{{$session->show_result_on_end}}";

                                                    // Get all radio and checkbox inputs within the form
                                                    var inputs = document.querySelectorAll('#question-form input[type="radio"], #question-form input[type="checkbox"]');

                                                    // Create an object to store user answers with question IDs as keys
                                                    var userAnswers = {
                                                        examId: examId, // Include examId
                                                        sessionId: sessionId, // Include sessionId
                                                        answers: [],
                                                    };

                                                    // Loop through the inputs and collect user answers
                                                    inputs.forEach(function (input) {
                                                        if (input.checked) {
                                                            // Extract the question ID from the input's name attribute
                                                            var questionId = input.name.split('_')[1];
                                                            var answerValue = input.value;
                                                            var isMultipleSelect = input.type === 'checkbox';

                                                            // Find an existing answer object with the same ID or create a new one
                                                            var answer = userAnswers.answers.find(function (ans) {
                                                                return ans.id === questionId;
                                                            });

                                                            if (!answer) {
                                                                answer = {
                                                                    id: questionId,
                                                                    isMultipleSelect: isMultipleSelect, // Add a marker for multiple/single select
                                                                    values: [],
                                                                };
                                                                userAnswers.answers.push(answer);
                                                            }

                                                            // Add the value of the checked input to the answer's values array
                                                            answer.values.push(answerValue);
                                                        }
                                                    });

                                                    // Function to trigger confetti
                                                    function triggerConfetti() {
                                                        const confettiSettings = {
                                                            particleCount: 100,
                                                            spread: 70,
                                                            origin: {y: 0.6},
                                                        };
                                                        if (isFinished === true) {
                                                            // confetti(confettiSettings);
                                                        }
                                                    }

                                                    // Send userAnswers with fetch (replace with your API endpoint)
                                                    if (isFinished === true)
                                                        showLoaderOverlay();
                                                    const csrfToken = '{{ csrf_token() }}';

                                                    let url = "";
                                                    if (isFinished) {
                                                        url = "/your-api-endpoint"
                                                    } else {
                                                        url = "/your-api-endpoint"
                                                    }
                                                    const requestBody = {
                                                        userAnswers: userAnswers,
                                                        isFinished: isFinished,
                                                        fullName: document.getElementById('fullName').value // Assuming you have an input field with id "fullName"
                                                    };

                                                    fetch(url, {
                                                        method: 'POST',
                                                        body: JSON.stringify(requestBody),
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
                                                        },
                                                    })
                                                        .then(function (response) {
                                                            if (!response.ok) {
                                                                console.error('Network error:', response);
                                                            }
                                                            return response.json();
                                                        })
                                                        .then(function (data) {
                                                            hideLoaderOverlay();
                                                            var score = data.scores;
                                                            var message = data.message;
                                                            var error = data.error;

                                                            if (error == true) {
                                                                Swal.fire({
                                                                    title: "Error",
                                                                    text: `${message}`, // Display the score
                                                                    icon: "error",
                                                                    confirmButtonText: "OK",
                                                                })
                                                            } else {

                                                                if(isInitial){
                                                                    const examArea = document.getElementById('exam-area'); // Get the exam-area section
                                                                    // Start the exam here, e.g., show the exam-area and the timer
                                                                    examArea.style.display = 'block'; // Show the exam-area
                                                                    setInterval(updateTimer, 1000); // Update timer every second
                                                                    document.getElementById("floating-timer").removeAttribute("style");
                                                                }

                                                                if (isFinished === true) {
                                                                    Swal.fire({
                                                                        title: "Your Score",
                                                                        text: `You scored ${score} points!`, // Display the score
                                                                        icon: "success",
                                                                        confirmButtonText: "OK",
                                                                    })
                                                                        .then((result) => {
                                                                            // The first SweetAlert is closed, now show the second one after 2 seconds
                                                                            if (result.isConfirmed) {
                                                                                return new Promise((resolve) => {
                                                                                    setTimeout(() => {
                                                                                        resolve();
                                                                                    }, 3000); // Delay for 2 seconds
                                                                                });
                                                                            }
                                                                        })
                                                                        .then((result) => {
                                                                            triggerConfetti();
                                                                        });
                                                                }
                                                            }

                                                        })
                                                        .catch(function (error) {
                                                            hideLoaderOverlay();
                                                            // Handle network error
                                                            console.error('Network error:', error);
                                                        });
                                                }

                                                document.getElementById('question-form').addEventListener('submit', function (event) {
                                                    event.preventDefault(); // Prevent the default form submission behavior
                                                    submitAnswer(true,false);
                                                });
                                                // Attach a click event handler to the "Refresh" button
                                                $('#refreshQuestions').click(function () {
                                                    // Call the function to fetch and display questions
                                                    fetchQuestions();
                                                });

                                                // Function to display questions in the view
                                                function displayQuestions() {
                                                    var questionsList = document.getElementById('questions-list');
                                                    questionsList.innerHTML = ''; // Clear previous questions

                                                    questionsList.addEventListener('click', function (event) {
                                                        if (event.target && event.target.nodeName === 'INPUT') {
                                                            submitAnswer(false,false); // Call your function here
                                                        }
                                                    });

                                                    // Calculate the start and end index for the current page.
                                                    var startIndex = (currentPage - 1) * questionsPerPage;
                                                    var endIndex = startIndex + questionsPerPage;

                                                    if (questionsData) {
                                                        var questionObjects = [];

                                                        questionsData.forEach(function (question, index) {
                                                            // Create a card for each question
                                                            var questionCard = document.createElement('div');
                                                            questionCard.className = 'card mb-3';

                                                            var questionTypeUi = "ssssss";
                                                            var mQuestionType = question.question_type;
                                                            if (mQuestionType === "multiple_choice_single") {
                                                                questionTypeUi = "Pilihlah satu jawaban yang benar";
                                                            } else if (mQuestionType === "multiple_choice") {
                                                                questionTypeUi = "Pilihlah jawaban-jawaban yang anda anggap benar";
                                                            }


                                                            // Create the HTML structure for displaying the question
                                                            var questionHTML = '<div class="card-body">';
                                                            questionHTML += '<h5 class="card-title text-dark">Question: ' + question.question + '</h5>';
                                                            // Add questionType as "small" after the choices
                                                            questionHTML += '<p class="text-dark  mb-3">' + questionTypeUi + '</p>'; // Adding "small" class conditionally

                                                            // Parse the choices JSON string into an array
                                                            var choices = JSON.parse(question.choices);

                                                            // Determine if the question index is odd or even
                                                            var isOdd = index % 2 !== 0;


                                                            // Check if the question has choices
                                                            if (choices && choices.length > 0) {
                                                                var questionObject = {
                                                                    question_id: question.id,
                                                                    answers: []
                                                                };

                                                                choices.forEach(function (choice, choiceIndex) {
                                                                    var inputName = 'question_' + question.id;

                                                                    // Create an input element
                                                                    var inputElement = document.createElement(mQuestionType === "multiple_choice" ? 'input' : 'input');
                                                                    inputElement.type = mQuestionType === "multiple_choice" ? 'checkbox' : 'radio';
                                                                    inputElement.name = inputName;
                                                                    inputElement.value = choice.text;
                                                                    inputElement.className = 'selectgroup-input';

                                                                    // Create a label for the input element
                                                                    var labelElement = document.createElement('label');
                                                                    labelElement.className = 'selectgroup-item';
                                                                    labelElement.style.width = '100%';
                                                                    labelElement.appendChild(inputElement);
                                                                    labelElement.innerHTML += '<span class="selectgroup-button" style="text-align: left;">' + choice.text + '</span>';

                                                                    // Append the label to your questionHTML
                                                                    questionHTML += labelElement.outerHTML;

                                                                    questionObject.answers.push(choice.text);
                                                                });


                                                            }

                                                            questionHTML += '</div>'; // Close the card-body div
                                                            questionCard.innerHTML = questionHTML;

                                                            // Check if the current question is outside the specified index range
                                                            if (index < startIndex || index >= endIndex) {
                                                                // Add a custom CSS class for styling questions outside the range
                                                                questionCard.classList.add('outside-index-card');
                                                            }

                                                            questionsList.appendChild(questionCard);
                                                        });


                                                    }
                                                }


                                                // Function to update the display when the "Next" button is clicked.
                                                document.getElementById('nextButton').addEventListener('click', function () {
                                                    if (questionsData) {
                                                        if (currentPage < Math.ceil(questionsData.length / questionsPerPage)) {
                                                            currentPage++;
                                                            displayQuestions();
                                                        }
                                                    }
                                                });

                                                // Function to update the display when the "Previous" button is clicked.
                                                document.getElementById('prevButton').addEventListener('click', function () {
                                                    if (currentPage > 1) {
                                                        currentPage--;
                                                        displayQuestions();
                                                    }
                                                });

                                                // Function to fetch questions and trigger initial display
                                                function fetchQuestions() {
                                                    // Show the loading overlay when the fetch starts
                                                    showLoaderOverlay();

                                                    const sessionId = {{ $session->id }};
                                                    const baseUrl = window.location.origin; // Replace with your actual base URL
                                                    const url = `${baseUrl}/quiz/session/${sessionId}/mquestions`;

                                                    fetch(url) // Replace with your Laravel route
                                                        .then(response => response.json())
                                                        .then(data => {
                                                            // Hide the loading overlay when the data is loaded
                                                            hideLoaderOverlay();
                                                            questionsData = data; // Store fetched data
                                                            displayQuestions(); // Display questions after fetching
                                                        })
                                                        .catch(error => {
                                                            console.error('Error fetching questions:', error);
                                                            // Hide the loading overlay in case of an error
                                                            hideLoaderOverlay();
                                                        });
                                                }
                                            });

                                        </script>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
            <script>

                var questionForm = document.getElementById('questionForm');

                hideLoaderOverlay();
                document.addEventListener('DOMContentLoaded', function () {


                });
            </script>

        </div>
    </div>
@endsection


@section("script")



@endsection
