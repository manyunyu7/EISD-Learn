@extends('main.template')

@section('head-section')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('library/ckeditor/ckeditor.js') }}"></script>
    <style>
        #previewCover {
            object-fit: cover;
            height: 200px;
            width: 100%;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
        }

        .form-row .form-group {
            flex: 1;
            margin-right: 10px;
        }

        .form-row .form-group:last-child {
            margin-right: 0;
            /* Remove margin for the last element */
        }
    </style>
@endsection

@section('script')
    <script>
        $('#tagsinput').tagsinput({
            tagClass: 'badge-info'
        });
    </script>
    <script>
        CKEDITOR.replace('notes', {
            filebrowserImageBrowseUrl: '/filemanager?type=Images',
            filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: '/filemanager?type=Files',
            filebrowserUploadUrl: '/filemanager/upload?type=Files&_token='
        });
    </script>

    

    <script>
        // Function to fetch positions based on selected type
        function fetchPositions() {
            fetch('/fetch-positions', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(function(positions) {
                    var positionDropdown = document.getElementById('position_id');
                    // Clear existing options
                    positionDropdown.innerHTML = '<option value="" disabled>Pilih Posisi</option>';
                    // Populate dropdown with fetched positions
                    positions.forEach(function(position) {
                        var option = document.createElement('option');
                        option.textContent = position.name;
                        option.value = position.id;
                        positionDropdown.appendChild(option);
                    });

                    $('.select2').select2({
                        placeholder: "Select an option",
                        allowClear: true // Optional: Allows clearing the selection
                    });

                    // Set the default value to be empty
                    $('.select2').val('').trigger('change'); // Triggers the change event to update Select2


                })
                .catch(function(error) {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }
        // Add event listener to trigger fetchPositions() when the DOM content is loaded
        document.addEventListener('DOMContentLoaded', function() {
            fetchDepartments();
            fetchPositions(); // Call fetchPositions() when the DOM content is loaded
        });

        // Function to fetch departments based on selected type
        function fetchDepartments() {
            fetch('/fetch-departments', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(function(departments) {
                    var departmentDropdown = document.getElementById('department_id');
                    // Clear existing options
                    departmentDropdown.innerHTML = '';
                    // Populate dropdown with fetched departments
                    departments.forEach(function(department) {
                        var option = document.createElement('option');
                        option.textContent = department.name;
                        option.value = department.id;
                        departmentDropdown.appendChild(option);
                    });
                    // Initialize Select2 if needed
                    $('.select2').select2({
                        placeholder: "Select an option",
                        allowClear: true // Optional: Allows clearing the selection
                    });
                    // Set the default value to be empty
                    $('.select2').val('').trigger('change'); // Triggers the change event to update Select2

                })
                .catch(function(error) {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }

        // Add event listener to radio buttons
        document.querySelectorAll('input[name="tipe"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                toggleDepartmentDropdown();
            });
        });
    </script>
@endsection

@section('main')
    <div class="page-inner" style="background-color: white !important">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('home') }}">Home</a></li>
                {{-- <li class="breadcrumb-item"><a href="{{ url('registration-code-management') }}">Training Request</a></li> --}}
                <li class="breadcrumb-item active" aria-current="page">Add New Training Request</li>
            </ol>
        </nav>

        <div class="container-fluid">
            <h1><b>Add New Training Request</b></h1>
        </div>

        <div class="container-fluid">
            <form id="addSessionForm" action="{{ url('training-request/create') }}" method="POST"
                enctype="multipart/form-data" onsubmit="showLoadingDialog(event)">
                @csrf
                <input hidden name="user_id" type="text" value="{{ Auth::user()->id }}">
                <div class="col">
                    <div class="row">
                        {{-- Divisi --}}
                        <div class="col-6 mb-3">
                            <label for="" class="mb-2">Divisi<span style="color: red">*</span></label>
                            <div class="input-group mb-3">
                                <input required name="divisi" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" value="{{ old('pass_class') }}">
                            </div>
                        </div>
    
                        {{-- Judul Pelatihan --}}
                        <div class="col-6 mb-3">
                            <label for="" class="mb-2">Tanggal Pengajuan<span style="color: red">*</span></label>
                            <div class="input-group mb-3">
                                <input readonly name="date_request" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" value="{{ now()->toDateString() }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt--4">
                        {{-- Judul Pelatihan --}}
                        <div class="col-12 mb-3">
                            <label for="" class="mb-2">Judul Pelatihan<span style="color: red">*</span></label>
                            <div class="input-group mb-3">
                                <input required name="training_title" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" value="{{ old('title') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mt--4">
                        <div class="col-12">
                            <label for="" class="mb-2">Waktu Pelatihan<span style="color: red">*</span></label>
                        </div>
                        {{-- Divisi --}}
                        <div class="col-6 mt--2">
                            <div class="form-group form-inline">
                                <label for="inlineinput" class="col-md-3 col-form-label">Start</label>
                                <div class="col-md-9 p-0">
                                    <input name="start_date" type="date" class="form-control input-full" id="inlineinput" placeholder="Enter Input" fdprocessedid="r77nba">
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mt--2">
                            <div class="form-group form-inline">
                                <label for="inlineinput" class="col-md-3 col-form-label">End</label>
                                <div class="col-md-9 p-0">
                                    <input name="end_date" type="date" class="form-control input-full" id="inlineinput" placeholder="Enter Input" fdprocessedid="r77nba">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt--1">
                        {{-- Tempat --}}
                        <div class="col-12 mb-3">
                            <label for="" class="mb-2">Tempat<span style="color: red">*</span></label>
                            <div class="input-group mb-3">
                                <input name="place" required type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" value="{{ old('title') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mt--4">
                        {{-- Biaya --}}
                        <div class="col-12 mb-3">
                            <label for="" class="mb-2">Biaya<span style="color: red">*</span></label>
                            <div class="input-group mb-3">
                                <input name="budget_fee" required type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" value="{{ old('title') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mt--4">
                        {{-- Trainer/Pelatih --}}
                        <div class="col-12 mb-3">
                            <label for="" class="mb-2">Trainer/Pelatih<span style="color: red">*</span></label>
                            <div class="input-group mb-3">
                                <input name="organizer" required type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" value="{{ old('title') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mt--4">
                        {{-- Peserta --}}
                        <div class="col-12 mb-3">
                            <label for="" class="mb-2">Peserta<span style="color: red">*</span></label>
                            <div class="input-group mb-3">
                                <input required name="participant" type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" value="{{ old('title') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        {{-- BUTTONS --}}
                        <div class="col-12 mb-3" style="display: flex; justify-content: flex-end;">
                            <div style="flex-grow: 1;"></div>
                            <div style="width: 200px;">
                                <div class="input-group mb-3">
                                    <button type="button" class="btn btn-danger" style="width: 45%; margin-right: 5px;">Cancel</button>
                                    <button type="submit" id="saveEditBtn" class="btn btn-success" style="width: 45%; margin-left: 5px;">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                title: 'We’re working on it!',
                text: 'Thanks for your patience.',
                icon: 'info',
                confirmButtonText: 'Got it'
            });
        });
    </script>
    
@endsection
