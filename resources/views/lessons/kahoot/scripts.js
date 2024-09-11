document.addEventListener('DOMContentLoaded', () => {
    const questionTypeElement = document.getElementById('questionType');
    const questionContainer = document.getElementById('questionContainer');
    const quizForm = document.getElementById('quizForm');
    const correctSound = document.getElementById('correctSound');
    const incorrectSound = document.getElementById('incorrectSound');

    // Example data
    const exampleQuestion = "What is the capital of France?";
    const exampleAnswers = ["Berlin", "Madrid", "Paris", "Rome"];
    const questionType = "Multiple Choice"; // Example question type

    function loadQuestionType() {
        questionTypeElement.textContent = questionType;
        setTimeout(() => {
            questionTypeElement.style.opacity = 0;
            setTimeout(() => {
                loadQuestion();
            }, 1000);
        }, 2000); // Show question type for 2 seconds
    }

    function loadQuestion() {
        questionTypeElement.style.display = 'none'; // Hide question type
        questionContainer.style.opacity = 1;
        questionContainer.style.transform = 'translateY(0)';

        const questionText = document.getElementById('questionText');
        questionText.textContent = exampleQuestion;

        quizForm.innerHTML = exampleAnswers.map((answer, index) => `
            <div class="answer-option">
                <input type="radio" id="answer${index + 1}" name="answer" value="${index + 1}">
                <label for="answer${index + 1}">${answer}</label>
            </div>
        `).join('');

        setTimeout(() => {
            document.querySelectorAll('.answer-option').forEach((element) => {
                element.classList.add('active');
            });
        }, 500); // Delay for answer option animation
    }

    // Handle form submission
    document.getElementById('quizForm').addEventListener('submit', (event) => {
        event.preventDefault();

        // Simulate correct/incorrect answer
        const selectedAnswer = document.querySelector('input[name="answer"]:checked');
        if (selectedAnswer && selectedAnswer.value == 3) { // Assume answer "Paris" is correct
            correctSound.play();
            alert('Correct answer!');
        } else {
            incorrectSound.play();
            alert('Incorrect answer!');
        }

        // Load next question or finish exam (not implemented)
    });

    // Load the initial question type
    loadQuestionType();
});
