<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{$lesson->course_title}}</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{asset('lesson_template/')}}/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{asset('lesson_template/')}}/css/simple-sidebar.css" rel="stylesheet">
    <link href="{{asset('lesson_template/')}}/css/custom.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        /* Floating timer container */
        .timer-container {
            position: fixed;
            bottom: 10px; /* Adjust the top position as needed */
            right: 10px; /* Adjust the right position as needed */
            background-color: white; /* You can adjust the background color as needed */
            z-index: 9999;
            padding: 10px;
            border: 1px solid #ccc; /* Add a border for style */
            border-radius: 5px;
        }

        /* Style for the timer */
        #timer {
            font-size: 24px;
            font-weight: bold;
            color: black; /* You can adjust the text color as needed */
        }

    </style>

</head>

<body>
<!-- Timer -->
<div id="floating-timer" class="timer-container">
    <div id="timer" class="timer">00:00:00</div>
</div>

<div id="wrapper">

    <div class="small-hamburger" style="margin-top:6px; margin-right: 30px; margin-left: 20px">
        <a href="#menu-toggle" id="menu-toggle-small">
            <img src="{{asset('lesson_template/img/')}}/hamburger_button.svg" alt="Toggle Menu"
                 style="width: 57px; height: 57px;">
        </a>
    </div>

    <div class="container-fluid navbar-fixed-top large-nav-bar" style="background-color: #F5F7FA; padding: 10px 20px;">
        <div class="row">
            <div class="col-xs-1 back-button">
                <a href="{{ url()->previous() }}" class="btn btn-link">
                    <img src="{{ asset('lesson_template/img/back_button.svg') }}" alt="Back"
                         style="width: 57px; height: 57px;">
                </a>
            </div>
            <div class="col-xs-10 text-center title-top">
                <h3>{{ $lesson->course_title }}
                    @if($isExam)
                        (Exam)
                    @endif
                </h3>
            </div>
            <div class="col-xs-1 col-xs-0 text-right hamburger-button">
                <div style="margin-top:6px; margin-right: 30px">
                    <a href="#menu-toggle" id="menu-toggle">
                        <img src="{{asset('lesson_template/img/')}}/hamburger_button.svg" alt="Toggle Menu"
                             style="width: 57px; height: 57px;">
                    </a>
                </div>
            </div>
        </div>
    </div>


    <!-- Page Content -->
    <div id="page-content-wrapper">

        @if($isExam)
            <section id="exam-information">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Quiz : {{$exam->title}}</h3>
                                {{--                                <h3 class="card-subtitle">Sesi : {{$session->id}}</h3>--}}
                                <hr>

                                <img class="card-img-top" src="{{ url('/') }}/home_assets/img/esd_3.png" alt=""
                                     style="max-width: 300px; height: auto;">

                                <div class="form-group">
                                    <label for="fullName">Nama Peserta Quiz :</label>
                                    <input type="text" id="fullName" name="fullName" class="form-control"
                                           placeholder="Enter your full name" required
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
                                                    // startExamButton.style.display = 'none'; // Show the exam-area
                                                    submitAnswer(false, true)
                                                });

                                                function submitAnswer(isFinished, isInitial) {
                                                    var courseId = {{ $courseId }};
                                                    var examId = {{ $exam->id }};
                                                    var sectionId = {{ $currentSectionId }};
                                                    var sessionId = {{ $session->id }};
                                                    var showResultOnEnd = "{{$session->show_result_on_end}}";

                                                    // Get all radio and checkbox inputs within the form
                                                    var inputs = document.querySelectorAll('#question-form input[type="radio"], #question-form input[type="checkbox"]');

                                                    // Create an object to store user answers with question IDs as keys
                                                    var userAnswers = {
                                                        examId: examId, // Include examId
                                                        sectionId: sectionId, // Include sessionId
                                                        courseId: courseId, // Include sessionId
                                                        sessionId: sessionId, // Include sessionId
                                                        answers: [],
                                                    };

                                                    if (isFinished) {
                                                        const startExamButton = document.getElementById('startExamButton');
                                                        const examArea = document.getElementById('exam-area'); // Get the exam-area section
                                                        const examEndArea = document.getElementById('exam-end'); // Get the exam-area section
                                                        examArea.style.display = 'none'; // Show the exam-area
                                                        // Start the exam here, e.g., show the exam-area and the timer
                                                        examEndArea.style.display = 'block'; // Show the exam-area
                                                        startExamButton.style.display = 'none'; // Show the exam-area
                                                        stopTimer();

                                                        isTimerTriggered = true;
                                                        document.getElementById("floating-timer").setAttribute("style", "display: none;");
                                                    }

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
                                                        // showloaderoverlay();
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
                                                        examId: examId, // Include examId
                                                        sectionId: sectionId, // Include sessionId
                                                        courseId: courseId, // Include sessionId
                                                        sessionId: sessionId, // Include sessionId
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
                                                            // // hideLoaderOverlay();
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

                                                                if (isInitial) {
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
                                                            // hideLoaderOverlay();
                                                            // Handle network error
                                                            console.error('Network error:', error);
                                                        });
                                                }

                                                document.getElementById('question-form').addEventListener('submit', function (event) {
                                                    event.preventDefault(); // Prevent the default form submission behavior
                                                    submitAnswer(true, false);
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
                                                            submitAnswer(false, false); // Call your function here
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
                                                    // showloaderoverlay();

                                                    const sessionId = {{ $session->id }};
                                                    const baseUrl = window.location.origin; // Replace with your actual base URL
                                                    const url = `${baseUrl}/quiz/session/${sessionId}/mquestions`;

                                                    fetch(url) // Replace with your Laravel route
                                                        .then(response => response.json())
                                                        .then(data => {
                                                            // Hide the loading overlay when the data is loaded
                                                            // hideLoaderOverlay();
                                                            questionsData = data; // Store fetched data
                                                            displayQuestions(); // Display questions after fetching
                                                        })
                                                        .catch(error => {
                                                            console.error('Error fetching questions:', error);
                                                            // Hide the loading overlay in case of an error
                                                            // hideLoaderOverlay();
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

                // hideLoaderOverlay();
                document.addEventListener('DOMContentLoaded', function () {


                });
            </script>

        @endif

        <div class="container-fluid">
            <div class="main-content-container container-fluid px-4 mt-5">

                {{-- @include('blog.breadcumb') --}}
                @forelse ($section_spec as $index => $sectionSpec)
                    <!-- Page Header -->
                    <div class="page-header row no-gutters mb-4">
                        <div class="col-12 col-sm-12 text-center text-sm-left mb-0">
                            <h2 class="text-uppercase">Kelas {{ $lesson->course_title }} </h2>
                            <h3 class="page-title">Materi Ke : {{ $sectionSpec->section_order }}</h3>
                            <h4 class="page-title">{{ $sectionSpec->section_title }}</h4>
                        </div>
                    </div>


                    @if($sectionDetail->embedded_file!="")
                        <div class="container-fluid">
                            <div style="width: 100%">
                                {!! $sectionDetail->embedded_file !!}
                            </div>
                        </div>
                    @endif

                    @if($sectionDetail->embedded_file=="" || $sectionDetail->embedded_file==null)
                        <div class="container-fluid">
                            @if(Str::contains(Storage::url('public/class/content/' . $sectionSpec->lesson_id . '/' . $sectionSpec->section_video),'pdf'))

                                <iframe id="pdfIframe" onload=""
                                        src="{{url("/")."/library/viewerjs/src/#"}}{{ Storage::url('public/class/content/' . $sectionSpec->lesson_id . '/' . $sectionSpec->section_video) }}"
                                        width="100%" height="550" allowfullscreen="" webkitallowfullscreen=""></iframe>
                                <!-- Add this single <script> tag to the body of your HTML document -->

                                <script>
                                    // Listen for a message from the iframe
                                    window.addEventListener('message', function (event) {
                                        if (event.data === 'iframeLoaded') {
                                            startTracking();
                                        }
                                    });

                                    function startTracking() {
                                        console.log('Tracking started.');

                                        function getCurrentPage() {
                                            var iframe = document.getElementById('pdfIframe');
                                            var currentPage = iframe.contentWindow.document.querySelector('.toolbarField.pageNumber').value;
                                            return parseInt(currentPage, 10);
                                        }

                                        function getTotalPages() {
                                            var iframe = document.getElementById('pdfIframe');
                                            var totalPages = iframe.contentWindow.document.querySelector('.toolbarLabel').textContent;
                                            var match = totalPages.match(/of (\d+)/);
                                            if (match && match[1]) {
                                                return parseInt(match[1], 10);
                                            }
                                            return 0;
                                        }

                                        function calculatePercentageCompletion() {
                                            var currentPage = getCurrentPage();
                                            var totalPages = getTotalPages();

                                            if (totalPages === 0) {
                                                return 0;
                                            }

                                            return (currentPage / totalPages) * 100;
                                        }

                                        function updatePageInfo() {
                                            var currentPage = getCurrentPage();
                                            var totalPages = getTotalPages();
                                            var percentageCompletion = calculatePercentageCompletion();

                                            console.log('Current Page:', currentPage);
                                            console.log('Total Pages:', totalPages);
                                            console.log('Percentage Completion:', percentageCompletion + '%');
                                        }

                                        setInterval(updatePageInfo, 1000);
                                    }
                                </script>
                            @else
                                @php
                                    $videoFormats = ['mp4', 'webm', 'ogg']; // Add more video formats as needed
                                    $imageFormats = ['jpg', 'jpeg', 'png', 'gif']; // Add more image formats as needed
                                    $fileExtension = pathinfo($sectionSpec->section_video, PATHINFO_EXTENSION);
                                @endphp

                                @if (in_array($fileExtension, $videoFormats))
                                    <video crossorigin controls playsinline id="myVideo" autoplay="autoplay"
                                           width="100%"
                                           class="video-mask" disablePictureInPicture controlsList="nodownload">
                                        <source
                                            src="{{ Storage::url('public/class/content/' . $sectionSpec->lesson_id . '/' . $sectionSpec->section_video) }}">
                                    </video>
                                @elseif (in_array($fileExtension, $imageFormats))
                                    <img
                                        src="{{ Storage::url('public/class/content/' . $sectionSpec->lesson_id . '/' . $sectionSpec->section_video) }}"
                                        alt="Image">
                                @else
                                    <h1>Unsupported file format</h1>
                                @endif
                            @endif
                        </div>
                    @endif

                    <script>
                        function nextCuy() {
                            var nextUrl = "{{ url('/') . "/course/$courseId/section/$next_section" }}";
                            window.location.href = nextUrl;
                            return;
                            var videoPlayer = document.getElementById("myVideo");
                            var nextUrl = "{{ url('/') . "/course/$courseId/section/$next_section" }}";
                            var progress = (videoPlayer.currentTime / videoPlayer.duration * 100);

                            if (progress >= 90) {
                                window.location.href = nextUrl;
                                return;
                            } else {

                                // Prevent the default behavior of the button
                                event.preventDefault();
                                // Show a SweetAlert alert informing the user to complete the video first
                                Swal.fire({
                                    title: "Video Progress",
                                    text: "Pengguna harus menyelesaikan video terlebih dahulu.",
                                    icon: "warning",
                                    confirmButtonText: "OK",
                                });
                            }
                        }
                    </script>


                    <div class="card mt-5">
                        <img class="card-img-top" src="holder.js/100x180/" alt="">
                        <div class="card-body">


                            <h4 class="card-title">{{ $lesson->course_title }}</h4>

                            <p class="card-text">Materi Ke : {{ $sectionSpec->section_order }}</p>
                            {!! $sectionSpec->section_content !!}

                            <div class="d-flex justify-content-between mt-2 mb-4">
                                @if ($prev_section != null)
                                    <a href="{{ url('/') . "/course/$courseId/section/$prev_section" }}"
                                       class="btn btn-primary hidden">Previous Lesson</a>
                                @endif
                                @if ($next_section != null)
                                    <button style="background-color: #39AA81" id="nextLessonButton"
                                            class="btn btn-primary" onclick="nextCuy();">
                                        Next Lesson
                                    </button>

                                    <!--<a href="{{ url('/') . "/course/$courseId/section/$next_section" }}" id="nextLessonButton"-->
                                    <!--    class="btn btn-primary ">Next-->
                                    <!--    Lesson</a>-->
                                @endif

                            </div>
                        </div>
                    </div>
                @empty
                @endforelse

            </div>
        </div>


    </div>
    <!-- /#page-content-wrapper -->

    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <div class="container content-container">
                <div class="" style="max-width: 560px">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px;">
                        <div style="flex: 1; flex-shrink: 1;">
                            <div class="category-label-container"
                                 style=" background-color: red; color: white"
                            >Management Trainee
                            </div>
                        </div>
                        <div style="flex-shrink: 1;">
                            <img style="width: 12%; height: auto;" src="{{ url('/HomeIcons/Toga_MDLNTraining.svg') }}">
                            Modernland Training
                        </div>
                    </div>
                </div>

                <div
                    style="padding: 30px;  background-color: #F5F7FA; max-width: 560px; display: flex; justify-content: space-between; align-items: center;">
                    <!-- First Section -->
                    <div style="flex: 1; flex-shrink: 1;">
                        <h4 style="color: #FE1D04">Getting Started</h4>
                    </div>

                    <div style="flex-shrink: 1;">
                        <span>
                            <img
                                src="{{asset('lesson_template/img/')}}/section_folders_icon.svg" alt="Toggle Menu"/>
                            <p style="display: inline;">This is the middle section.</p>
                        </span>
                    </div>

                    <!-- Third Section -->
                    <div style="flex-shrink: 1; margin-left: 20px">
                        <span>
                            <img
                                src="{{asset('lesson_template/img/')}}/section_finished_icon.svg" alt="Toggle Menu"/>
                            <p style="display: inline;">This is the middle section.</p>
                        </span>
                    </div>
                </div>


                @forelse ($sections as $item)

                    <!--- Item Course Section Item -->
                    <div
                        style="padding: 20px;  background-color: #FFFFFF; max-width: 560px; display: flex; justify-content: space-between; align-items: center; border-bottom: 0.2px solid black;">
                        <!-- First Section -->
                        <div style="flex: 1; flex-shrink: 1;">
                            @if (isset($item) && isset($item->isTaken))
                                @php
                                    $isCurrent = $item->isCurrent ?? false; // Check if $item->isCurrent is set, if not, set it to false
                                @endphp
                                <a href="{{ route('course.see_section', [$item->lesson_id, $item->section_id]) }}"
                                   style="text-decoration: none; color: inherit;">
                                    {{-- Check if the item is marked as taken --}}
                                    @if ($item->isTaken)
                                        {{-- Display a checked checkbox icon indicating completion --}}
                                        <img src="{{ asset('lesson_template/img/checkbox_checked_icon.svg') }}"
                                             alt="Completed"/>
                                        {{-- Check if it's the current section --}}
                                    @elseif ($item->section_id == $currentSectionId)
                                        {{-- Display a checked checkbox icon indicating completion --}}
                                        <img src="{{ asset('lesson_template/img/checkbox_checked_icon.svg') }}"
                                             alt="Completed"/>
                                    @else
                                        {{-- Display an empty checkbox icon indicating incomplete --}}
                                        <img src="{{ asset('lesson_template/img/checkbox_empty_icon.svg') }}"
                                             alt="Incomplete"/>
                                    @endif
                                    {{-- Display the section title --}}
                                    <span style="display: inline-block;">
                                        {{ $item->section_title }}
                                    </span>
                                </a>
                            @endif
                        </div>

                        <!-- Third Section -->
                        <div style="flex-shrink: 1; margin-left: 20px">
                                <span>
                                    @if($item->quiz_session_id!="-"&&$item->quiz_session_id!="")
                                        <img src="{{asset('lesson_template/img/')}}/timer_icon.svg"
                                             alt="Toggle Menu"/>
                                    @endif
                                   @if($item->time_limit_minute!="-"&&$item->time_limit_minute!="")
                                        <p style="display: inline;">{{$item->time_limit_minute}}m</p>
                                    @endif
                            </span>

                        </div>
                    </div>
                @empty
                    <li class="nav-item card p-1 bg-dark" style="margin-bottom: 6px !important">
                        {{-- <a href="{{ route('course.see_section', [$item->lesson_id, $item->section_id]) }}">
                    <span class="badge badge-success ">{{ $item->section_order }}</span><br>
                    </a> --}}
                        <p style="margin-bottom: 0px !important"> Belum Ada Materi di Kelas Ini</p>
                    </li>
                @endforelse
            </div>
        </ul>
    </div>
    <!-- /#sidebar-wrapper -->

</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="{{asset('lesson_template/')}}/js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{asset('lesson_template/')}}/js/bootstrap.min.js"></script>

<!-- Menu Toggle Script -->
<script>
    $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    $("#menu-toggle-small").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
</script>

</body>

</html>
