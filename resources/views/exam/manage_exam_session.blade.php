@extends('main.template')

@section('head-section')
    <!-- Datatables -->

    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>
@endsection

@section('script')
    <script>
        $(document).on('click', '.button', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            swal({
                    title: "Are you sure!",
                    type: "error",
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes!",
                    showCancelButton: true,
                },
                function () {
                    $.ajax({
                        type: "POST",
                        url: "{{url('/destroy')}}",
                        data: {id: id},
                        success: function (data) {
                            //
                        }
                    });
                });
        });

    </script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Datatables -->
    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#basic-datatables').DataTable({});
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

@endsection


@section('main')

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
                    <a href="#">{{$exam->title}}</a>
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


                        <script>
                            // Function to fetch data and refresh the table
                            var examId = {{ $exam->id }};

                            function refreshTable() {
                                const endpointUrl = `/exam/refresh-session-table?id=${examId}`;
                                showLoaderOverlay();

                                fetch(endpointUrl)
                                    .then((response) => response.json())
                                    .then((data) => {
                                        hideLoaderOverlay();
                                        const tableBody = document.querySelector('tbody');
                                        tableBody.innerHTML = '';

                                        data.forEach((item) => {
                                            const row = createTableRow(item);
                                            tableBody.appendChild(row);
                                        });

                                        addDeleteButtonClickListeners();
                                    })
                                    .catch((error) => {
                                        hideLoaderOverlay();
                                        console.error('Error fetching data:', error);
                                    });
                            }

                            // Function to create a table row based on item data
                            function createTableRow(item) {

                                const baseUrl =  window.location.origin; // Replace with your actual base URL
                                const examId = 123; // Replace this with your actual examId value

                                const seeSessionUrl = `${baseUrl}/exam/session/${item.id}/view`;

                                const row = document.createElement('tr');
                                row.innerHTML = `
                                <td>${item.start_date}</td>
                                <td>${item.end_date}</td>
                                <td>${item.instruction}</td>
                                <td>${item.description}</td>
                                <td>${createBadge(item.can_access)}</td>
                                <td>${createBadge(item.public_access)}</td>
                                <td>${createBadge(item.show_result_on_end)}</td>
                                <td>${createBadge(item.allow_review)}</td>
                                <td>${createBadge(item.show_score_on_review)}</td>
                                <td>${createBadge(item.allow_multiple)}</td>
                                <td class="text-center">
                                    <div class="form-button-action">
                                        <a href="${seeSessionUrl}" class="btn btn-link btn-primary btn-lg" title="Lihat Kelas">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <button class="btn btn-link btn-danger btn-lg delete-item" title="Hapus" data-toggle="modal" data-target="#deleteConfirmationModal" data-item-id="${item.id}" data-item-instruction="${item.instruction}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <a href="${seeSessionUrl}" class="btn btn-link btn-primary btn-lg" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </div>
                                </td>`;
                                return row;
                            }

                            // Function to create a badge based on a value
                            function createBadge(value) {
                                const badgeClass = value === 'y' ? 'badge-success' : 'badge-danger';
                                return `<span class="badge ${badgeClass}">${value === 'y' ? 'Yes' : 'No'}</span>`;
                            }

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
                                        document.getElementById('deleteItemButton').setAttribute('data-item-id', itemId);
                                        document.getElementById('deleteItemButton').setAttribute('data-item-instruction', itemInstruction);
                                    });
                                });
                            }


                            // Initial table load
                            refreshTable();
                        </script>

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
                                        <button id="btnDestroyItem" type="button" class="btn btn-danger">Delete
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
                        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                        <script>
                            // JavaScript to handle form submission and show loading indicator
                            $(document).ready(function () {
                                $('#students').select2({
                                    placeholder: 'Select students',
                                    allowClear: true,
                                    width: 'resolve' // need to override the changed default
                                });
                            });

                            document.addEventListener("DOMContentLoaded", function () {
                                const addSessionForm = document.getElementById("addSessionForm");
                                const saveChangesBtn = document.getElementById("saveChangesBtn");


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
                                                    text: 'Question saved successfully',
                                                });
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
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Success',
                                                    text: 'Question saved successfully',
                                                });
                                            }, 1000);
                                            // Close the modal (optional)
                                            $('#addSessionModal').modal('hide');
                                            refreshTable()
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

                        <div class="table-responsive">
                            <table id="basic-datatables"
                                   class="table table-bordered mt-3 @if (count($dayta) < 1) d-none @endif">
                                <thead>
                                <tr>
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
                                @forelse ($dayta as $data)
                                    <tr>
                                        <td>{{ $data['start_date'] }}</td>
                                        <td>{{ $data['end_date'] }}</td>
                                        <td>{{ $data['instruction'] }}</td>
                                        <td>{{ $data['description'] }}</td>
                                        <td>
                                            @if ($data['can_access'] === 'y')
                                                <span class="badge badge-success">Yes</span>
                                            @else
                                                <span class="badge badge-danger">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($data['public_access'] === 'y')
                                                <span class="badge badge-success">Yes</span>
                                            @else
                                                <span class="badge badge-danger">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($data['show_result_on_end'] === 'y')
                                                <span class="badge badge-success">Yes</span>
                                            @else
                                                <span class="badge badge-danger">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($data['allow_review'] === 'y')
                                                <span class="badge badge-success">Yes</span>
                                            @else
                                                <span class="badge badge-danger">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($data['show_score_on_review'] === 'y')
                                                <span class="badge badge-success">Yes</span>
                                            @else
                                                <span class="badge badge-danger">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($data['allow_multiple'] === 'y')
                                                <span class="badge badge-success">Yes</span>
                                            @else
                                                <span class="badge badge-danger">No</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $examId = $data['id'];
                                            @endphp

                                            <div class="form-button-action">
                                                <button type="button" data-toggle="tooltip" title=""
                                                        class="btn btn-link btn-primary btn-lg"
                                                        data-original-title="Edit Task">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title=""
                                                        class="btn btn-link btn-danger" data-original-title="Remove">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <button type="button" data-toggle="tooltip" title=""
                                                        class="btn btn-link btn-danger" data-original-title="Remove">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <div class="alert alert-danger">
                                        Anda Belum Memiliki Kelas
                                    </div>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection




