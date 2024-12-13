@extends('main.template')

@section('head-section')
    <script src="{{ asset('library/ckeditor/ckeditor.js') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Your styles here */
        #previewCover {
            object-fit: cover;
            height: 200px;
            width: 100%;
        }
    </style>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: "Select an option",
                allowClear: true // Allows clearing the selection
            });

            // Initialize CKEditor
            CKEDITOR.replace('notes', {
                filebrowserImageBrowseUrl: '/filemanager?type=Images',
                filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
                filebrowserBrowseUrl: '/filemanager?type=Files',
                filebrowserUploadUrl: '/filemanager/upload?type=Files&_token='
            });

            // Re-fetch departments and positions if necessary
            fetchDepartments();
            fetchPositions();
        });

        // Fetch positions and departments functions as needed
        function fetchPositions() {
            // Your fetch logic for positions
        }

        function fetchDepartments() {
            // Your fetch logic for departments
        }
    </script>
@endsection

@section('main')
    <div class="page-inner" style="background-color: white !important">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('registration-code-management') }}">Registration Codes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Registration Code</li>
            </ol>
        </nav>

        <form action="{{ route('registration_code.update', $data->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Important for the update method -->
            <div class="container-fluid mt-3">
                <div class="main-content-container container-fluid px-4">
                    <div class="page-header row no-gutters mb-4">
                        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                            <span class="text-uppercase page-subtitle">Registration Code</span>
                            <h3 class="page-title">Edit Registration Code</h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card card-small mb-3">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Registration Code</label>
                                            <input type="text" name="registration_code" class="form-control"
                                                value="{{ old('registration_code', $data->registration_code) }}"
                                                placeholder="Enter Registration Code">
                                            @error('registration_code')
                                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group" style="flex: 2;">
                                            <label class="font-weight-bold">Notes</label>
                                            <textarea name="notes" id="notes" class="form-control" placeholder="Enter notes">{{ old('notes', $data->notes) }}</textarea>
                                            @error('notes')
                                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row ml-1 mr-1">
                                        <div class="form-group col-12 col-lg-6">
                                            <label class="font-weight-bold">Department</label>
                                            <select id="department_id" name="department_id"
                                                class="form-control form-select-lg select2">
                                                <option value="">Select Department</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->id }}" {{ (old('department_id', $data->department_id) == $department->id) ? 'selected' : '' }}>
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('department_id')
                                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-12 col-lg-6">
                                            <label class="font-weight-bold">Position</label>
                                            <select id="position_id" name="position_id"
                                                class="form-control form-select-lg select2">
                                                <option value="">Select Position</option>
                                                @foreach($positions as $position)
                                                    <option value="{{ $position->id }}" {{ (old('position_id', $data->position_id) == $position->id) ? 'selected' : '' }}>
                                                        {{ $position->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('position_id')
                                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row ml-1 mr-1">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="location">Business Unit</label>
                                            <select id="location" name="location"
                                                class="form-control form-select-lg select2">
                                                @foreach ($locations as $locationItem)
                                                    <option value="{{ $locationItem->id }}" {{ (old('location', json_decode($data->location, true)[0]['site_id'] ?? null) == $locationItem->id) ? 'selected' : '' }}>
                                                        {{ $locationItem->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('location')
                                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Active Status</label>
                                            <select name="is_active" class="form-control">
                                                <option value="y" {{ (old('is_active', $data->is_active) == 'y') ? 'selected' : '' }}>Active</option>
                                                <option value="n" {{ (old('is_active', $data->is_active) == 'n') ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('is_active')
                                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-md btn-primary">Update</button>
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
