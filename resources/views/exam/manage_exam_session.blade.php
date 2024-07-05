@extends('main.template')

@section('head-section')
    <!-- Datatables -->

    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>
@endsection

@section('script')
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Datatables -->
    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>
    <script>
        $(document).ready(function () {


            var examId = {{ $exam->id }};
            const dataTable = $('#basic-datatables').DataTable({
                "ajax": {
                    "url": "/exam/refresh-session-table?id=" + examId,
                    "dataSrc": "" // This is where the data array is located in your JSON response
                },
                "columns": [
                    {"data": "title"},
                    {"data": "start_date"},
                    {"data": "end_date"},
                    {"data": "instruction"},
                    {"data": "description"},
                    {
                        "data": "can_access",
                        "render": function (data, type, full, meta) {
                            return data === 'y' ? '<span class="badge badge-success">Ya</span>' : '<span class="badge badge-danger">Tidak</span>';
                        }
                    },
                    {
                        "data": "public_access",
                        "render": function (data, type, full, meta) {
                            return data === 'y' ? '<span class="badge badge-success">Ya</span>' : '<span class="badge badge-danger">Tidak</span>';
                        }
                    },
                    {
                        "data": "show_result_on_end",
                        "render": function (data, type, full, meta) {
                            return data === 'y' ? '<span class="badge badge-success">Ya</span>' : '<span class="badge badge-danger">Tidak</span>';
                        }
                    },
                    {
                        "data": "allow_review",
                        "render": function (data, type, full, meta) {
                            return data === 'y' ? '<span class="badge badge-success">Ya</span>' : '<span class="badge badge-danger">Tidak</span>';
                        }
                    },
                    {
                        "data": "show_score_on_review",
                        "render": function (data, type, full, meta) {
                            return data === 'y' ? '<span class="badge badge-success">Ya</span>' : '<span class="badge badge-danger">Tidak</span>';
                        }
                    },
                    {
                        "data": "allow_multiple",
                        "render": function (data, type, full, meta) {
                            return data === 'y' ? '<span class="badge badge-success">Ya</span>' : '<span class="badge badge-danger">Tidak</span>';
                        }
                    },
                    {
                        "data": null,
                        "render": function (data, type, full, meta) {
                            // Create custom actions buttons as needed
                            const baseUrl = window.location.origin; // Replace with your actual base URL
                            const seeSessionUrl = `${baseUrl}/quiz/session/${data.id}/initial`;
                            return `
                        <div class="form-button-action">
                            <a href="${seeSessionUrl}" class="btn btn-link btn-primary btn-lg" title="Lihat Kelas">
                                <i class="fa fa-eye"></i>
                            </a>
                            <button class="btn btn-link btn-danger btn-lg delete-item" title="Hapus" data-toggle="modal" data-target="#deleteConfirmationModal"
                                data-item-id="${data.id}" data-item-instruction="${data.instruction}">
                                <i class="fa fa-trash"></i>
                            </button>
                            <button class="btn btn-link btn-primary btn-lg edit-item" data-toggle="modal"  data-target="#editSessionModal" data-session-id="${data.id}">
                                <i class="fa fa-edit"></i>
                            </button>
                        </div>`;
                        }
                    }
                ]
            });

            // Event delegation for delete and edit buttons
            $('#basic-datatables').on('click', '.delete-item', function () {
                const itemId = $(this).data('item-id');
                const itemInstruction = $(this).data('item-instruction');
                // Set the content of the modal dynamically
                $('#deleteItemId').text(itemId);
                $('#deleteItemInstruction').text(itemInstruction);
                // Suppose you have a delete button element
                const deleteButton = document.querySelector('#btnDestroyItem');
                deleteButton.setAttribute('data-w-deleted-instruction', itemInstruction);
                deleteButton.setAttribute('data-w-deleted-id', itemId);
            });

            $('#basic-datatables').on('click', '.edit-item', function () {
                const itemId = $(this).data('session-id');
                showLoaderOverlay()
                fetch(`/exam/session/${itemId}/data`)
                    .then(response => response.json())
                    .then(data => {
                        hideLoaderOverlay()
                        console.log(data)
                        document.getElementById('edit_item_id').value = data.id;
                        document.getElementById('start_date_edit').value = data.start_date;
                        document.getElementById('title_edit').value = data.title;
                        document.getElementById('end_date_edit').value = data.end_date;
                        document.getElementById('instruction_edit').value = data.instruction;
                        document.getElementById('time_limit_edit').value = data.time_limit_minute
                        document.getElementById('description_edit').value = data.description;
                        document.getElementById('can_access_edit').value = data.can_access;
                        document.getElementById('public_access_edit').value = data.public_access;
                        document.getElementById('show_result_on_end_edit').value = data.show_result_on_end;
                        document.getElementById('allow_review_edit').value = data.allow_review;
                        document.getElementById('show_score_on_review_edit').value = data.show_score_on_review;
                        document.getElementById('allow_multiple_edit').value = data.allow_multiple;
                    })
                    .catch(error => {
                        hideLoaderOverlay()
                        console.error('Error fetching data:', error);
                    });
            });

            // Function to add click event listeners to delete buttons
            function addDeleteButtonClickListeners() {
                document.querySelectorAll('.delete-item').forEach((deleteButton) => {
                    deleteButton.addEventListener('click', function () {
                        const itemId = this.getAttribute('data-item-id');
                        const itemInstruction = this.getAttribute('data-item-instruction');
                        // Set the content of the modal dynamically
                        document.getElementById('deleteItemId').textContent = itemId;
                        document.getElementById('deleteItemInstruction').textContent = itemInstruction;
                        // Suppose you have a delete button element
                        const deleteButton = document.querySelector('#btnDestroyItem');
                        deleteButton.setAttribute('data-w-deleted-instruction', itemInstruction);
                        deleteButton.setAttribute('data-w-deleted-id', itemId);
                        // Set the data-item-id attribute of the delete confirmation button
                    });
                });
            }

            function refreshTable() {
                showLoaderOverlay();
                // Refresh the table by reloading data from the server
                dataTable.ajax.reload();
                hideLoaderOverlayNow()
            }

            refreshTable()
            const addSessionForm = document.getElementById("addSessionForm");
            const saveChangesBtn = document.getElementById("saveChangesBtn");

            const editSessionForm = document.getElementById("editSessionForm");
            const saveEditBtn = document.getElementById("saveEditBtn");

            var deleteButton = document.getElementById("btnDestroyItem")
            deleteButton.addEventListener('click', function () {

                //show loading
                showLoaderOverlay()

                // Retrieve the itemId from the data-w-deleted-id attribute
                const itemId = this.getAttribute('data-w-deleted-id');
                // Define the ID and body data
                const requestBody = {
                    id: itemId,
                };
                const csrfToken = @json(csrf_token());
                // Define the URL of the endpoint you want to send the POST request to
                const endpointUrl = `/exam/delete-exam-session/`;
                // Create the fetch POST request
                fetch(endpointUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the request headers
                    },
                    body: JSON.stringify(requestBody), // Convert the request body to JSON format
                })
                    .then((response) => {
                        //hide loading
                        hideLoaderOverlay()
                        if (response.ok) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Session deleted successfully',
                            });
                            refreshTable()
                            // The POST request was successful
                            return response.json();
                        } else {
                            // Handle errors here, e.g., show an error message
                            console.error('Error:', response.statusText);
                        }
                        refreshTable()
                    })
                    .then((data) => {
                        // Handle the response data here
                        console.log('Response Data:', data);
                        refreshTable()
                    })
                    .catch((error) => {
                        hideLoaderOverlay()
                        refreshTable()
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error saving session',
                        });
                        // Handle network errors or other errors
                        console.error('Fetch Error:', error);
                    }).then();
                // Now you have access to itemId
                console.log("Item ID:", itemId);
                // You can also get itemInstruction in a similar way if needed
                const itemInstruction = this.getAttribute('data-item-instruction');
                console.log("Item Instruction:", itemInstruction);
            });

            editSessionForm.addEventListener("submit", function (event) {
                event.preventDefault();
                // Show loading indicator
                // Serialize form data
                const formData = new FormData(editSessionForm);
                showLoaderOverlay()
                // Replace 'your-api-endpoint' with your actual API endpoint URL
                fetch("/exam/session/update", {
                    method: "POST",
                    body: formData,
                })
                    .then((response) => response.json())
                    .then((data) => {
                        hideLoaderOverlay()
                        // Handle the response (e.g., display a success message, update UI)
                        console.log("Response:", data);
                        setTimeout(function () {
                            refreshTable()
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Session saved successfully',
                            });
                        }, 1000);
                        // Close the modal (optional)
                        refreshTable()
                        $('#editSessionModal').modal('hide');
                    })
                    .catch((error) => {
                        hideLoaderOverlay()
                        // Handle errors (e.g., display an error message)
                        console.error("Error:", error);
                        saveChangesBtn.disabled = false;
                        setTimeout(function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error saving session',
                            });
                        }, 1000);
                        refreshTable()
                    });
            });


            addSessionForm.addEventListener("submit", function (event) {
                event.preventDefault();
                // Show loading indicator
                saveChangesBtn.disabled = true;
                // Serialize form data
                const formData = new FormData(addSessionForm);
                showLoaderOverlay()
                // Replace 'your-api-endpoint' with your actual API endpoint URL
                fetch("/exam/store-exam-session", {
                    method: "POST",
                    body: formData,
                })
                    .then((response) => response.json())
                    .then((data) => {
                        hideLoaderOverlay()
                        // Handle the response (e.g., display a success message, update UI)
                        console.log("Response:", data);


                        saveChangesBtn.disabled = false;
                        setTimeout(function () {
                            refreshTable()
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Session saved successfully',
                            });
                        }, 1000);
                        // Close the modal (optional)
                        refreshTable()
                        $('#addSessionModal').modal('hide');
                    })
                    .catch((error) => {
                        hideLoaderOverlay()
                        // Handle errors (e.g., display an error message)
                        console.error("Error:", error);

                        saveChangesBtn.disabled = false;
                        setTimeout(function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error saving session',
                            });
                        }, 1000);
                        refreshTable()
                    });
            });

        });
    </script>


    <script>
        //message with toastr
        @if(session()-> has('success'))
        toastr.success('{{ session('success') }}', 'BERHASIL!');
        @elseif(session()-> has('error'))
        toastr.error('{{ session('error') }}', 'GAGAL!');
        @endif
    </script>

    <script>
        // JavaScript to handle form submission and show loading indicator
        $(document).ready(function () {
            $('#students').select2({
                placeholder: 'Select students',
                allowClear: true,
                width: 'resolve' // need to override the changed default
            });
        });
    </script>



    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


    <script>
    </script>

@endsection


@section('main')

    <!-- Edit Modal -->
    <div class="modal fade" id="editSessionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Sesi Ujian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editSessionForm" method="post">
                        @csrf
                        @method('POST')

                        <input type="hidden" id="edit_item_id" name="id" value="">

                        <div class="form-group">
                            <label>Judul Sesi</label>
                            <input placeholder="Judul" id="title_edit" type="text" class="form-control" name="title">
                        </div>

                        <div class="form-group">
                            <label for="start_date">Start Dates</label>
                            <input type="datetime-local" class="form-control" id="start_date_edit" name="start_date">
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="datetime-local" class="form-control" id="end_date_edit" name="end_date">
                        </div>
                        <div class="form-group">
                            <label for="instruction">Instruction</label>
                            <textarea class="form-control" id="instruction_edit" name="instruction"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="time_limit">Batas Waktu Pengerjaan (Menit)</label>
                            <input type="number" class="form-control" id="time_limit_edit" name="time_limit">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description_edit" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="can_access">Can Access</label>
                            <select class="form-control" id="can_access_edit" name="can_access">
                                <option value="y">Yes</option>
                                <option value="n">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="public_access">Public Access</label>
                            <select class="form-control" id="public_access_edit" name="public_access">
                                <option value="y">Yes</option>
                                <option value="n">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="show_result_on_end">Show Result on End</label>
                            <select class="form-control" id="show_result_on_end_edit" name="show_result_on_end">
                                <option value="y">Yes</option>
                                <option value="n">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="allow_review">Allow Review</label>
                            <select class="form-control" id="allow_review_edit" name="allow_review">
                                <option value="y">Yes</option>
                                <option value="n">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="show_score_on_review">Show Score on Review</label>
                            <select class="form-control" id="show_score_on_review_edit" name="show_score_on_review">
                                <option value="y">Yes</option>
                                <option value="n">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="allow_multiple">Allow Multiple (Attempt)</label>
                            <select class="form-control" id="allow_multiple_edit" name="allow_multiple">
                                <option value="y">Yes</option>
                                <option value="n">No</option>
                            </select>
                        </div>

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveEditBtn">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Exam</h4>
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
                    <a href="#">Buat Sesi Exam</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Sesi Ujian Pada Quiz {{$exam->title}}</div>
                    </div>
                    <div class="card-body">
                        <!-- Button trigger modal -->

                        <button type="button" class="btn btn-primary mb-5" data-toggle="modal"
                                data-target="#addSessionModal">
                            Tambah Sesi Ujian Baru
                        </button>

                        <button id="refreshButton" class="btn btn-primary d-none" data-exam-id="{{ $exam->id }}">Refresh
                            Table
                        </button>


                        <!-- Modal for Delete Confirmation -->
                        <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
                             aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Delete item with ID: <span id="deleteItemId"></span></p>
                                        <p>Instruction: <span id="deleteItemInstruction"></span></p>
                                        <p>Are you sure you want to delete this item?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel
                                        </button>
                                        <button id="btnDestroyItem" data-dismiss="modal" type="button"
                                                class="btn btn-danger">Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="addSessionModal" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Tambah Sesi Ujian Baru</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="addSessionForm">
                                            @csrf
                                            <input type="hidden" name="exam_id" value="{{$exam->id}}">

                                            <div class="row">
                                                <!-- First Column -->

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>Judul Sesi</label>
                                                        <input placeholder="Judul" type="text" class="form-control"
                                                               name="title">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="start_date" data-toggle="tooltip"
                                                               data-placement="top" title="Start Date Description">Start
                                                            Date</label>
                                                        <input type="datetime-local" class="form-control"
                                                               id="start_date" name="start_date">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="end_date" data-toggle="tooltip" data-placement="top"
                                                               title="End Date Description">End Date</label>
                                                        <input type="datetime-local" class="form-control" id="end_date"
                                                               name="end_date">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="instruction" data-toggle="tooltip"
                                                               data-placement="top" title="Instruction Description">Instruction</label>
                                                        <textarea class="form-control" id="instruction"
                                                                  name="instruction"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="time_limit_second" data-toggle="tooltip"
                                                               data-placement="top" title="Batas Waktu (Menit)">Batas
                                                            Waktu Pengerjaan (Menit)</label>
                                                        <input type="number" class="form-control" id="instruction"
                                                               name="time_limit"></input>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="description" data-toggle="tooltip"
                                                               data-placement="top" title="Description Description">Description</label>
                                                        <textarea class="form-control" id="description"
                                                                  name="description"></textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="selectStudent">Tambah Peserta (Manual)</label>
                                                        <select name="students[]" id="students" style="width: 75%"
                                                                class="form-control" multiple>
                                                            @foreach ($students as $student)
                                                                <option value="{{ $student->id }}">{{ $student->name }}
                                                                    - {{ $student->email }}</option>
                                                            @endforeach  
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Second Column -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Can Access</label>
                                                        <select class="form-control" name="can_access"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Can Access Description">
                                                            <option value="y">Yes</option>
                                                            <option value="n">No</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Public Access</label>
                                                        <select class="form-control" name="public_access"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Public Access Description">
                                                            <option value="y">Yes</option>
                                                            <option value="n">No</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Show Result on End</label><br>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                   name="show_result_on_end" id="show_result_yes"
                                                                   value="y">
                                                            <label class="form-check-label" for="show_result_yes"
                                                                   data-toggle="tooltip" data-placement="top"
                                                                   title="Show Result on End Description">Yes</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                   name="show_result_on_end" id="show_result_no"
                                                                   value="n">
                                                            <label class="form-check-label" for="show_result_no"
                                                                   data-toggle="tooltip" data-placement="top"
                                                                   title="Show Result on End Description">No</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="allow_review" data-toggle="tooltip"
                                                               data-placement="top" title="Allow Review Description">Allow
                                                            Review</label>
                                                        <select class="form-control" name="allow_review">
                                                            <option value="y">Yes</option>
                                                            <option value="n">No</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="show_score_on_review" data-toggle="tooltip"
                                                               data-placement="top"
                                                               title="Show Score on Review Description">Show Score on
                                                            Review</label>
                                                        <select class="form-control" name="show_score_on_review">
                                                            <option value="y">Yes</option>
                                                            <option value="n">No</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Allow Multiple (Attempt)</label>
                                                        <select class="form-control" name="allow_multiple"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Allow Multiple (Attempt) Description">
                                                            <option value="y">Yes</option>
                                                            <option value="n">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                            </button>
                                            <button type="submit" class="btn btn-primary" id="saveChangesBtn">Save
                                                changes
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="basic-datatables" class="table table-bordered mt-3">
                                <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Instruction</th>
                                    <th>Description</th>
                                    <th>Can Access</th>
                                    <th>Public Access</th>
                                    <th>Show Result on End</th>
                                    <th>Allow Review</th>
                                    <th>Show Score on Review</th>
                                    <th>Allow Multiple</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection




