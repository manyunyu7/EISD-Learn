@extends('main.template')

@section('head-section')
    <!-- Datatables -->
    <script src="{{ asset('atlantis/examples') }}/assets/js/plugin/datatables/datatables.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection

@section('script')
    <script>
        $(document).on('click', '.button', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            swal({
                    title: "Are you sure!",
                    type: "error",
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes!",
                    showCancelButton: true,
                },
                function() {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('/destroy') }}",
                        data: {
                            id: id
                        },
                        success: function(data) {
                            //
                        }
                    });
                });
        });
    </script>
    {{-- Toastr --}}
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Datatables -->
    <script src="{{ asset('atlantis/examples') }}/assets/js/plugin/datatables/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#basic-datatables').DataTable({});

            $('#multi-filter-select').DataTable({
                "pageLength": 5,
                initComplete: function() {
                    this.api().columns().every(function() {
                        var column = this;
                        var select = $(
                                '<select class="form-control"><option value=""></option></select>'
                            )
                            .appendTo($(column.footer()).empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function(d, j) {
                            select.append('<option value="' + d + '">' + d +
                                '</option>')
                        });
                    });
                }
            });

            // Add Row
            $('#add-row').DataTable({
                "pageLength": 5,
            });

            var action =
                '<td> <div class="form-button-action"> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

            $('#addRowButton').click(function() {
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
        @if (session()->has('success'))
            toastr.success('{{ session('success') }}', 'BERHASIL!');
        @elseif (session()->has('error'))
            toastr.error('{{ session('error') }}', 'GAGAL!');
        @endif
    </script>
@endsection


@section('main')

    <div class="page-inner  bg-white">

        <div class="col-md-12">
            {{-- BREADCRUMB --}}
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href={{ url('/home') }}>Home</a></li>
                    <li class="breadcrumb-item"><a href={{ url('/exam/manage-exam-v2') }}>Exam</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Exam</li>
                </ol>
            </nav>
        </div>


        {{-- SETUP QUIZ --}}
        <div class="col-12">
            <div class="page-header">
                {{-- <h1><b>STEP 1</b></h1><br> --}}
            </div>
            <div class="page-header">
                <h2><b>SetUp Quiz/Pre-Test/Post-Test/Evaluation</b></h2>
            </div>

            <div>
                <!-- Display All Errors at the Top -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <form id="addSessionForm" method="post" action="{{ route('update-quiz-new', ['examId' => $data->exam->id]) }}">
                @csrf
                @method('POST')

                {{-- Input Judul Ujian --}}
                <div class="mb-3">
                    <label for="title" class="mb-2">Judul Ujian</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                            name="title" value="{{ old('title', $data->exam->title) }}" aria-label="Recipient's username"
                            aria-describedby="basic-addon2">
                    </div>
                    @error('title')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Input Jenis Ujian --}}
                <div class="mb-3">
                    <label for="exam_type" class="mb-2">Jenis Ujian</label>
                    <div class="input-group mb-3">
                        <select name="exam_type" id="exam_type" class="form-control form-select-lg"
                            aria-label="Default select example">
                            <option value="Pre Test"
                                {{ old('exam_type', $data->exam_session->exam_type) == 'Pre Test' ? 'selected' : '' }}>Pre
                                Test</option>
                            <option value="Post Test"
                                {{ old('exam_type', $data->exam_session->exam_type) == 'Post Test' ? 'selected' : '' }}>
                                Post Test</option>
                            <option value="Quiz"
                                {{ old('exam_type', $data->exam_session->exam_type) == 'Quiz' ? 'selected' : '' }}>Quiz
                            </option>
                            <option value="Evaluation"
                                {{ old('exam_type', $data->exam_session->exam_type) == 'Evaluation' ? 'selected' : '' }}>
                                Evaluation</option>
                        </select>
                    </div>
                </div>
                {{-- Input Batas Waktu --}}
                <div class="mb-3">
                    <label for="times_limit" class="mb-2">Batas Waktu (Menit)</label>
                    <div class="input-group mb-3">
                        <input name="times_limit" type="text"
                            class="form-control @error('times_limit') is-invalid @enderror"
                            aria-label="Recipient's username" aria-describedby="basic-addon2"
                            value="{{ old('times_limit', $data->exam_session->time_limit_minute ?? '') }}">
                    </div>
                    @error('times_limit')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                {{-- Input Start and End Date --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="mb-2">Start Date</label>
                        <div class="input-group mb-3">
                            <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror"
                                id="start_date" name="start_date"
                                value="{{ old('start_date') ? \Carbon\Carbon::parse(old('start_date'))->format('Y-m-d\TH:i') : \Carbon\Carbon::parse($data->exam_session->start_date)->format('Y-m-d\TH:i') }}">
                        </div>
                        @error('start_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="mb-2">End Date</label>
                        <div class="input-group mb-3">
                            <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror"
                                id="end_date" name="end_date"
                                value="{{ old('end_date') ? \Carbon\Carbon::parse(old('end_date'))->format('Y-m-d\TH:i') : \Carbon\Carbon::parse($data->exam_session->end_date)->format('Y-m-d\TH:i') }}">
                        </div>
                        @error('end_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                {{-- Input Instruksi Exam --}}
                <div class="mb-3">
                    <label for="instruction" class="mb-2">Instruksi Exam</label>
                    <textarea id="editor" class="form-control @error('instruction')
                    is-invalid @enderror"
                        name="instruction">{{ old('instruction', $data->exam->instruction) }}</textarea>

                    @error('instruction')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            ClassicEditor
                                .create(document.querySelector('#editor'))
                                .catch(error => {
                                    console.error(error);
                                });
                        });
                    </script>
                </div>


                {{-- SETUP ACCESS --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="" class="mb-2">Public Access</label>
                        <div class="input-group mb-3">
                            <input readonly type="text"
                                value="{{ $data->exam_session->public_access == 'n' ? 'Tidak Aktif' : 'Aktif' }}"
                                name="public_access" id="public-access-btn"
                                class="btn {{ $data->exam_session->public_access == 'n' ? 'btn-danger' : 'btn-success' }}"
                                style="width: 100%">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="" class="mb-2">Show Result</label>
                        <div class="input-group mb-3">
                            <input readonly type="text"
                                value="{{ $data->exam_session->show_result_on_end == 'n' ? 'Tidak Aktif' : 'Aktif' }}"
                                name="show_result_on_end" id="show-result-btn"
                                class="btn {{ $data->exam_session->show_result_on_end == 'n' ? 'btn-danger' : 'btn-success' }}"
                                style="width: 100%">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="" class="mb-2">Allow Review</label>
                        <div class="input-group mb-3">
                            <input readonly type="text"
                                value="{{ $data->exam_session->allow_review == 'n' ? 'Tidak Aktif' : 'Aktif' }}"
                                name="allow_review" id="allow-review-btn"
                                class="btn {{ $data->exam_session->allow_review == 'n' ? 'btn-danger' : 'btn-success' }}"
                                style="width: 100%">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="" class="mb-2">Allow Multiple</label>
                        <div class="input-group mb-3">
                            <input readonly type="text"
                                value="{{ $data->exam_session->allow_multiple == 'n' ? 'Tidak Aktif' : 'Aktif' }}"
                                name="allow_multiple" id="allow-multiple-btn"
                                class="btn {{ $data->exam_session->allow_multiple == 'n' ? 'btn-danger' : 'btn-success' }}"
                                style="width: 100%">
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var btn_public_access = document.getElementById('public-access-btn');
                            var btn_show_result = document.getElementById('show-result-btn');
                            var btn_allow_review = document.getElementById('allow-review-btn');
                            var btn_allow_multiple = document.getElementById('allow-multiple-btn');

                            // Toggle button states
                            function toggleButton(btn, state) {
                                if (state) {
                                    btn.classList.remove('btn-danger');
                                    btn.classList.add('btn-success');
                                    btn.value = 'Aktif';
                                    btn.textContent = 'Aktif';
                                } else {
                                    btn.classList.remove('btn-success');
                                    btn.classList.add('btn-danger');
                                    btn.value = 'Tidak Aktif';
                                    btn.textContent = 'Tidak Aktif';
                                }
                            }

                            // Set initial states
                            toggleButton(btn_public_access, '{{ $data->exam_session->public_access }}' === 'y');
                            toggleButton(btn_show_result, '{{ $data->exam_session->show_result_on_end }}' === 'y');
                            toggleButton(btn_allow_review, '{{ $data->exam_session->allow_review }}' === 'y');
                            toggleButton(btn_allow_multiple, '{{ $data->exam_session->allow_multiple }}' === 'y');

                            // Public Access Setup
                            btn_public_access.addEventListener('click', function() {
                                toggleButton(btn_public_access, btn_public_access.value === 'Tidak Aktif');
                            });

                            // Show Result Setup
                            btn_show_result.addEventListener('click', function() {
                                toggleButton(btn_show_result, btn_show_result.value === 'Tidak Aktif');
                            });

                            // Allow Review Setup
                            btn_allow_review.addEventListener('click', function() {
                                toggleButton(btn_allow_review, btn_allow_review.value === 'Tidak Aktif');
                            });

                            // Allow Multiple Setup
                            btn_allow_multiple.addEventListener('click', function() {
                                toggleButton(btn_allow_multiple, btn_allow_multiple.value === 'Tidak Aktif');
                            });
                        });
                    </script>
                </div>
                <div class="mb-3" style="display: flex; justify-content: flex-end;">
                    <div style="flex-grow: 1;"></div>
                    <div style="width: 200px;">
                        <div class="input-group mb-3">
                            <button type="button" class="btn btn-danger"
                                style="width: 45%; margin-right: 5px;">Cancel</button>
                            <button type="submit" id="saveEditBtn" class="btn btn-primary"
                                style="width: 45%; margin-left: 5px;">Next</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
