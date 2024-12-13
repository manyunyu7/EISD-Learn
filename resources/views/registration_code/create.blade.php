@extends('main.template')

@section('head-section')
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
                <li class="breadcrumb-item"><a href="{{ url('registration-code-management') }}">Registration Codes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add New Registration Code</li>
            </ol>
        </nav>

        <form action="{{ route('registration_code.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="container-fluid mt-3">
                <div class="main-content-container container-fluid px-4">
                    <!-- Page Header -->
                    <div class="page-header row no-gutters mb-4">
                        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                            <span class="text-uppercase page-subtitle">Registration Code</span>
                            <h3 class="page-title">Add New Registration Code</h3>
                        </div>
                    </div>
                    <!-- End Page Header -->

                    <!-- Form Fields -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <!-- Combined Form Section -->
                            <div class="card card-small mb-3">
                                <div class="card-body">
                                    <div class="form-row">
                                        <!-- Registration Code -->
                                        <div class="form-group">
                                            <label class="font-weight-bold">Registration Code</label>
                                            <input type="text" name="registration_code" class="form-control"
                                                value="{{ old('registration_code') }}"
                                                placeholder="Enter Registration Code">
                                            @error('registration_code')
                                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <!-- Notes -->
                                        <div class="form-group" style="flex: 2;">
                                            <label class="font-weight-bold">Notes</label>
                                            <textarea name="notes" id="notes" class="form-control" placeholder="Enter notes">{{ old('notes') }}</textarea>
                                            @error('notes')
                                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="row ml-1 mr-1">
                                        <!-- Department -->
                                        <div class="form-group col-12 col-lg-6">
                                            <label class="font-weight-bold">Department</label>
                                            <select id="department_id" name="department_id"
                                                class="form-control form-select-lg select2">
                                                <option value="">Select Department</option>
                                            </select>
                                            <!-- Example error message -->
                                            <div class="alert alert-danger mt-2" style="display: none;">Error message here
                                            </div>
                                        </div>

                                        <!-- Position -->
                                        <div class="form-group col-12 col-lg-6">
                                            <label class="font-weight-bold">Position</label>
                                            <select id="position_id" name="position_id"
                                                class="form-control form-select-lg select2">
                                                <option value="">Select Position</option>
                                            </select>
                                            <!-- Example error message -->
                                            <div class="alert alert-danger mt-2" style="display: none;">Error message here
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row ml-1 mr-1">
                                        <!-- Location -->
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="location">Business Unit</label>
                                            <select id="location" name="location"
                                                class="form-control form-select-lg select2">
                                                @forelse ($locations as $locationItem)
                                                    <option value="{{ $locationItem->id }}">{{ $locationItem->name }}
                                                    </option>
                                                @empty
                                                    <option value="" disabled>No locations available</option>
                                                @endforelse
                                            </select>
                                            @error('location')
                                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <!-- Active Status -->
                                        <div class="form-group">
                                            <label class="font-weight-bold">Active Status</label>
                                            <select name="is_active" class="form-control">
                                                <option value="y">Active</option>
                                                <option value="n">Inactive</option>
                                            </select>
                                            @error('is_active')
                                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Submit & Reset Buttons -->
                                    <div class="form-row">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-md btn-primary">Save</button>
                                            <button type="reset" class="btn btn-md btn-warning">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
