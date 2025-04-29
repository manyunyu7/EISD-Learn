@extends('main.template')

@section('head-section')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Custom CSS for DataTables elements */


        /* Style for the search box container */
        .dt-search {
            display: flex; /* Arrange elements in a row */
            align-items: center; /* Vertically align label and input */
            margin-bottom: 10px; /* Add some space below the search box */
        }

        /* Style for the search label */
        .dt-search label {
            margin-right: 5px; /* Add spacing between label and input */
            font-weight: bold; /* Make label text bolder (optional) */
        }

        /* Style for the search input field */
        .dt-search input {
            border: 1px solid #ccc; /* Add a border */
            border-radius: 3px; /* Add rounded corners */
            padding: 5px 10px; /* Add padding for better user experience */
            font-size: 14px; /* Set font size */
        }

        /* Style for the search input field on hover */
        .dt-search input:hover {
            border-color: #999; /* Change border color on hover (optional) */
        }

        /* Style for Excel download button */
        .dt-button {
            /* Add your custom styles here */
            display: inline-block;
            padding: 8px 16px;
            border: 1px solid #007bff; /* Change color as needed */
            color: #007bff; /* Change color as needed */
            text-align: center;
            margin-bottom: 20px;
            text-decoration: none;
            font-size: 15px;
            border-radius: 20px; /* Adjust as needed */
            transition: all 0.3s ease;
            background-color: transparent;
        }

        .dt-button.buttons-excel.buttons-html5:hover {
            background-color: #007bff; /* Change color as needed */
            color: #fff; /* Change color as needed */
        }

        /* Style for pagination buttons */
        .dt-paging.paging_full_numbers .dt-paging-button {
            /* Add your custom styles here */
            width: 30px;
            height: 30px;
            border-radius: 50%; /* Ensures rounded circles */
            background-color: #fff; /* Change color as needed */
            margin: 0 2px; /* Adjust spacing as needed */
            justify-content: center;
            align-items: center;
            cursor: pointer;
            border: 1px solid #007bff; /* Change color as needed */
            color: #007bff; /* Change color as needed */
            font-weight: bold;
        }

        .dt-paging{
            margin: 20px;
        }


        .dt-paging.paging_full_numbers .dt-paging-button.current {
            background-color: #007bff; /* Change color as needed */
            color: #fff; /* Change color as needed */
        }

        .dt-paging.paging_full_numbers .dt-paging-button:hover {
            background-color: #007bff; /* Change color as needed */
            color: #fff; /* Change color as needed */
        }
    </style>
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

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#basic-datatables').DataTable({
                dom: '<"top"Bfrtip>', // Add buttons to the top and bottom, with buttons at the top
                buttons: [
                    'excel','pdf','csv' // Button for Excel export
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]], // Define options for number of rows per page
                pagingType: 'full_numbers' // Include pagination numbers
            });
        });
    </script>

    {{-- Toastr --}}
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        //message with toastr
        @if(session()-> has('success'))
        toastr.success('{{ session('success') }}', 'BERHASIL!');
        @elseif(session()-> has('error'))
        toastr.error('{{ session('error') }}', 'GAGAL!');
        @endif
    </script>

    <script>
        $(document).ready(function() {
            $('#department').select2({
                placeholder: "-- Pilih Departemen --",
                allowClear: true
            });
        });
    </script>


@endsection

@section('main')


    <div class="page-inner"  style="background-color: white !important">
        <div class="col-md-12 mt-2">
            {{-- BREADCRUMB --}}
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href={{url('/users')}}>User Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Export Database User</li>
                </ol>
            </nav>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Data User E-Learning Modernland</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <form method="GET" action="{{ route('users.export.excel') }}">
                                    <label for="department">Pilih Departemen:</label>
                                    {{-- <select name="department" id="department" onchange="this.form.submit()">
                                        <option value="">-- Semua Departemen --</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->name }}
                                            </option>
                                        @endforeach
                                    </select> --}}
                                    <select name="department" id="department" class="form-control select2" onchange="this.form.submit()">
                                        <option value="">-- Semua Departemen --</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                </form>
                                
                                
                                <table id="basic-datatables" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Realta Code</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Positions</th>
                                        <th>Department</th>
                                        <th>Locations</th>
                                        <th>Created At</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->realta_code }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->role }}</td>
                                                <td>{{ $user->position_name }}</td>
                                                <td>{{ $user->department_name }}</td>
                                                <td>
                                                    @foreach ($user->location_names as $location)
                                                        {{ $location }}<br>
                                                    @endforeach
                                                </td>
                                                <td>{{ $user->created_at }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
