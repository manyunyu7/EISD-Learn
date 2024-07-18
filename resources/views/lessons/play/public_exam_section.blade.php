<div class="container-fluid">
    <div class="main-content-container container-fluid px-4 mt-5">
        <section id="exam-information">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="">
                        <div class="pt-2">
                            <h1 class="card-title">{{ $exam->title }}</h1>

                            <h5 style="font-size: 22px; color: slategray;">
                                {{ 'Anda Sudah Mengambil Quiz ini : ' . count($examResults) . ' Kali' }}</h5>
                            <h5 style="font-size: 21px; margin-top: 20px">{!! $session->instruction !!}</h5>

                            <!-- Time -->
                            <div class="form-group">
                                <h5>Batas Waktu : {{ $session->time_limit_minute }} Menit</h5>
                            </div>

                            <div class="form-group">
                                <h5>Multiple Attempt: {{ $session->allow_multiple === 'y' ? 'Ya' : 'Tidak' }}</h5>
                            </div>


                            <!-- Number of Questions -->
                            <div class="form-group">
                                <label>Jumlah Pertanyaan :</label>
                                <span>{{ $question_count }}</span>
                            </div>

                            <!-- Student Name -->
                            <div class="form-group">
                                <label for="fullName">Nama Peserta Quiz :</label>
                                <input type="text" id="fullName" name="fullName" class="form-control"
                                    placeholder="Enter your full name" required style="max-width: 50%"
                                    @auth
value="{{ Auth::user()->name }}" @endauth>
                            </div>

                            <!-- Start Exam Button -->
                            <button id="confirmStartButton" type="button"
                                class="btn btn-primary btn-border btn-round mb-3 mt-3" data-toggle="modal"
                                data-target="#confirmationModal">
                                Mulai Ujian
                            </button>




                            <div class="alert alert-primary d-none" role="alert">
                                <h4 class="alert-heading"></h4>
                                <p>Klik <strong>tombol kirim</strong>, setelah ujian selesai, perhatikan sisa
                                    waktu
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
                                            <button id="startExam" type="button" data-dismiss="modal"
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


        <section id="sectionFocus" style="display: none">
            @if (isset($questions) && count($questions) > 0)
                @foreach ($questions as $index => $question)
                    <div class="" style="margin-bottom: 20px; ">
                        <div class="panel-body">
                            <p style="font-size: 18px"><strong>{{ $index + 1 }}.</strong> <span
                                    style="font-size: 18px">{{ $question->question }}</span></p>
                            @if (!empty($question->image))
                                <img src="{{ Storage::url('public/exam/question') . '/' . $question->image }}"
                                    alt="Question Image" style="max-width: 100%;">
                            @endif

                            @if (stripos($question->question_type, 'single') !== false)
                                <div class="radio">
                                    @php $letters = range('A', 'Z'); @endphp
                                    @foreach (json_decode($question->choices, true) as $index => $choice)
                                        <div>
                                            <label>
                                                <input type="radio" name="answers[{{ $question->id }}]"
                                                    value="{{ $choice['text'] }}"
                                                    style="background-color: #208DBB; font-size: 18px">
                                                {{ $letters[$index] }}
                                                . {{ $choice['text'] }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="checkbox">
                                    @php $letters = range('A', 'Z'); @endphp
                                    @foreach (json_decode($question->choices, true) as $index => $choice)
                                        <div>
                                            <label>
                                                <input type="checkbox" name="answers[{{ $question->id }}][]"
                                                    value="{{ $choice['text'] }}"
                                                    style="background-color: #208DBB; font-size: 18px;">
                                                {{ $letters[$index] }}
                                                . {{ $choice['text'] }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach
                <button id="finishExam" type="submit" class="btn btn-primary">Submit</button>
            @else
                <p>No questions found.</p>
            @endif
        </section>

        @if ($examSession->show_result == 'y' || $examSession->show_result == '')
        <div class="mt-4">
            <hr>
            <h4>Riwayat Hasil Ujian : </h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Guest Name</th>
                            <th>Current Score</th>
                            <th>Finished At</th>
                            @if ($examSession->allow_review == 'y' || $examSession->allow_review == '')
                                <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examResults->reverse() as $result)
                            @if($result->token_exam == $examToken)
                            <tr>
                                <td>{{ $result['guest_name'] }}</td>
                                <td>{{ $result['current_score'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($result['finished_at'])->format('F j, Y g:i A') }}
                                </td>
                                @if ($examSession->allow_review == 'y' || $examSession->allow_review == '')
                                    <td>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModal{{ $result['id'] }}">
                                            Show Answers
                                        </button>
                                    </td>

                                    <!-- Modal -->
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal{{ $result['id'] }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">User Answers</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @foreach (json_decode($result['user_answers'], true) as $answer)
                                                        <div>
                                                            <strong>Question:</strong>
                                                            {{ $answer['question_text'] ?? '' }}
                                                        </div>
                                                        <div>
                                                            <strong>Answer:</strong><br>
                                                            @if (isset($answer['isMultipleSelect']) && $answer['isMultipleSelect'])
                                                                Multiple Choice: {{ $answer['values'][0] ?? '' }}
                                                            @else
                                                                Single Choice: {{ $answer['values'][0] ?? '' }}
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <strong>Jawaban
                                                                Pengguna:</strong><br>{{ $answer['correct_answer'] ?? '' }}
                                                        </div>
                                                        {{--                                                <div> --}}
                                                        {{--                                                    <strong>Jawaban Benar:</strong><br>{{ $answer['correct_score'] ?? '' }} --}}
                                                        {{--                                                </div> --}}
                                                        {{--                                                <div> --}}
                                                        {{--                                                    <strong>Descriptive Score:</strong> {{ $answer['descriptive_score'] ?? '' }} --}}
                                                        {{--                                                </div> --}}
                                                        {{--                                                <div> --}}
                                                        {{--                                                    <strong>User Choice Score:</strong> {{ $answer['user_choice_score'] ?? '' }} --}}
                                                        {{--                                                </div> --}}
                                                        <hr> <!-- Add a horizontal line between each answer -->
                                                    @endforeach
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5">No exam results found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
        @endif
    </div>

</div>

<!-- Add this script to the HTML file -->
<script>
    //function to fetch student result on current sections
    const startExamButton = document.getElementById("startExam");
    const confirmStartButton = document.getElementById("confirmStartButton");
    const timer = document.getElementById("timer");
    const sectionFocus = document.getElementById("sectionFocus");
    const timeLimit = {{ $session->time_limit_minute }} * 60; // Convert minutes to seconds
    let remainingTime = timeLimit;
    let timerInterval;
    const fullNameInput = document.getElementById('fullName');

    function startTimer() {
        const fullName = fullNameInput.value.trim();

        const payload = {
            userAnswers: {
                examId: {{ $examSession->exam_id }}, // Use Blade syntax to echo the variable
                sessionId: {{ $examSession->id }}, // Use Blade syntax to echo the
                answers: []
            },
            examId: {{ $examSession->exam_id }}, // Use Blade syntax to echo the variable
            sessionId: {{ $examSession->id }}, // Use Blade syntax to echo the
            isFinished: false,
            fullName: fullName // Use the typed name from the input
        };


        // Send the payload to your API endpoint using Fetch
        fetch("/exam/save-user-answer", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" // Include CSRF token
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json()) // Parse response as JSON
            .then(data => {
                var myerror = data.showError;
                if (myerror === true) {
                    Swal.fire({
                        title: "Error",
                        text: `${data.message}`, // Display the error message
                        icon: "error",
                        confirmButtonText: "OK",
                    });
                    // Stop the timer since there was an error
                    clearInterval(timerInterval);
                    return; // Don't execute the rest of the function
                }
                // Hide start exam button
                confirmStartButton.style.display = "none";
                // Show section focus
                sectionFocus.style.display = "block";
                // Start the timer
                timerInterval = setInterval(updateTimer, 1000);
            })
            .catch(error => {
                // Handle error if needed
                console.error("Error starting timer:", error);
            });
    }

    function updateTimer() {
        remainingTime--;

        if (remainingTime <= 0) {
            clearInterval(timerInterval);
            timer.textContent = "Time's up!";
            timer.style.color = "red";
            sendAllUserAnswers(true);

        } else {
            const minutes = Math.floor(remainingTime / 60);
            const seconds = remainingTime % 60;
            const formattedTime = `${padNumber(minutes)}:${padNumber(seconds)}`;
            timer.textContent = formattedTime;

            // Blink and turn red when reaching 90% of time limit
            const ninetyPercent = Math.floor(timeLimit * 0.9);
            if (remainingTime <= ninetyPercent) {
                timer.style.color = timer.style.color === "red" ? "black" : "red";
            }
        }
    }

    function padNumber(number) {
        return number < 10 ? "0" + number : number;
    }

    function toggleButtonState() {
        if (fullNameInput.value.trim() === '') {
            confirmStartButton.disabled = true;
        } else {
            confirmStartButton.disabled = false;
        }
    }

    fullNameInput.addEventListener('input', toggleButtonState);

    confirmStartButton.addEventListener('click', function(event) {
        if (fullNameInput.value.trim() === '') {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Nama Peserta Quiz harus diisi sebelum memulai ujian!',
            });
        }
    });

    toggleButtonState();

    startExamButton.addEventListener("click", startTimer);

    // Function to send all user-filled answers to the API endpoint
    function sendAllUserAnswers(isFinished) {

        const fullName = fullNameInput.value.trim();

        // Construct the payload
        const payload = {
            userAnswers: {
                examId: {{ $examSession->exam_id }}, // Use Blade syntax to echo the variable
                sessionId: {{ $examSession->id }}, // Use Blade syntax to echo the
                answers: []
            },
            examId: {{ $examSession->exam_id }}, // Use Blade syntax to echo the variable
            sessionId: {{ $examSession->id }}, // Use Blade syntax to echo the
            isFinished: isFinished,
            fullName: fullName // Use Blade syntax to echo the variable
        };

        // Retrieve all filled answers
        const radioInputs = document.querySelectorAll("input[type='radio']:checked");
        const checkboxInputs = document.querySelectorAll("input[type='checkbox']:checked");

        // Add radio answers to the payload
        radioInputs.forEach(function(input) {
            const answerId = input.name.split("[")[1].split("]")[0]; // Extract question ID
            const answerValue = input.value;
            payload.userAnswers.answers.push({
                id: answerId,
                isMultipleSelect: false,
                values: [answerValue]
            });
        });

        // Add checkbox answers to the payload
        checkboxInputs.forEach(function(input) {
            const answerId = input.name.split("[")[1].split("]")[0]; // Extract question ID
            const answerValue = input.value;
            const existingAnswer = payload.userAnswers.answers.find(answer => answer.id === answerId);
            if (existingAnswer) {
                existingAnswer.values.push(answerValue);
            } else {
                payload.userAnswers.answers.push({
                    id: answerId,
                    isMultipleSelect: true,
                    values: [answerValue]
                });
            }
        });

        // Send the payload to your API endpoint using Fetch
        fetch("/exam/save-user-answer", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" // Include CSRF token
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json()) // Parse response as JSON
            .then(data => {
                var myerror = data.showError;
                if (myerror === true) {
                    Swal.fire({
                        title: "Error",
                        text: `${data.message}`, // Display the score
                        icon: "error",
                        confirmButtonText: "OK",
                    })
                }
                // Check if the exam is finished
                if (data.is_finished) {
                    // Show Sweet Alert with score
                    Swal.fire({
                        title: 'Exam Finished!',
                        text: `Your score is ${data.scores}%.`,
                        icon: 'success',
                        confirmButtonText: 'Next Page'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // User clicked "Next Page", invoke nextCuy() function
                            location.reload();
                        }
                    });
                } else {
                    console.log("Answers submitted successfully");
                }
            })
            .catch(error => {
                // Handle error if needed
                console.error("Error submitting answers:", error);
            });
    }

    // Add event listener to capture click on the "Submit" button
    document.getElementById("finishExam").addEventListener("click", function() {
        // Show Sweet Alert confirmation dialog
        Swal.fire({
            title: 'Are you sure you want to finish the exam?',
            text: 'You won\'t be able to change your answers after submission!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // User confirmed, proceed to submit
                sendAllUserAnswers(
                    true); // Call function to send all user answers with isFinished = true
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // User cancelled, do nothing
                Swal.fire('Cancelled', 'Your exam submission was cancelled.', 'info');
            }
        });
    });

    // Add event listeners to capture checkbox and radio button changes
    document.querySelectorAll("input[type='checkbox'], input[type='radio']").forEach(function(input) {
        input.addEventListener("change", function() {
            sendAllUserAnswers(false); // Call function to send all user answers
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


<script>
    document.addEventListener("DOMContentLoaded", function() {



    });
</script>
