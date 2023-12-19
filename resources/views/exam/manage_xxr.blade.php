<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Question Form</title>
    <!-- Add Bootstrap CSS link here -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <form id="questionForm" name="questionForm" method="POST" action="submit.php">
        <div class="form-group">
            <label class="font-weight-bold">Jenis Pertanyaan</label>
            <select id="questionType" class="form-control" name="questionType">
                <option value="multiple_choice">Pilihan Ganda</option>
                <option value="essay">Essay</option>
                <option value="true_false">True/False</option>
            </select>
        </div>

        <div class="form-group">
            <label class="font-weight-bold">Pertanyaan</label>
            <input type="text" class="form-control" name="title" placeholder="Masukkan Pertanyaan">
        </div>

        <div id="choices" style="display: none;">
            <div class="form-group">
            </div>
            <button type="button" class="btn btn-primary add-choice">Tambah Pilihan</button>
        </div>

        <div id="recommendation" style="display: none;">
            <div class="alert alert-info">Rekomendasi: Gunakan pertanyaan True/False untuk pertanyaan dengan dua pilihan yang jelas (benar/salah).</div>
        </div>

        <div id="essay" style="display: none;">
            <div class="form-group">
                <label class="font-weight-bold">Jawaban Essay</label>
                <textarea class="form-control" name="essay_answer" rows="5" placeholder="Masukkan Jawaban Essay"></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Simpan Pertanyaan</button>
    </form>
</div>

<script>
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
</body>
</html>
