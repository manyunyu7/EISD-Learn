@extends('main.template')

@section('head-section')
    <!-- Datatables CSS -->
    <link rel="stylesheet" href="{{ asset('atlantis/examples/assets/css/plugin/datatables/datatables.min.css') }}">
@endsection

@section('main')
    <div class="page-inner" style="background-color: white !important">

        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header row no-gutters py-4">
                <div class="col-12 text-center text-sm-left mb-0">
                    <span class="text-uppercase page-subtitle">User Management</span>
                    <h3 class="page-title">Manage Users</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card border-0 shadow rounded">
                        @if (session()->has('success'))
                            <div class="alert alert-primary alert-dismissible fade show mx-2 my-2" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>{{ session('success') }}</strong>
                            </div>
                        @elseif(session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show mx-2 my-2" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>{{ session('error') }}</strong>
                            </div>
                        @endif

                        <div class="card-body">
                            <a href="{{ url('users/create') }}">
                                <button class="btn btn-primary btn-border btn-round mb-3">Create New User</button>
                            </a>
                            <table id="basic-datatables" class="table table-bordered table-responsive">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Positions</th>
                                        <th>Department</th>
                                        <th>Locations</th>
                                        <th>Is Testing</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
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
                                            <td>
                                                @if($user->is_testing === 'y')
                                                    <span class="badge bg-success">Yes</span>
                                                @elseif($user->is_testing === 'n')
                                                    <span class="badge bg-danger">No</span>
                                                @else
                                                    <span class="badge bg-secondary">Not Specified</span>
                                                @endif
                                            </td>                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('users.edit', $user->id) }}"
                                                        class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure?')" title="Delete">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
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
@endsection

@section('script')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Datatables JS -->
    <script src="{{ asset('atlantis/examples/assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <!-- Toastr JS -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#basic-datatables').DataTable({
                "pageLength": 10,
                "responsive": true
            });

            // Display toastr messages
            @if (session()->has('success'))
                toastr.success('{{ session('success') }}', 'Success!');
            @elseif (session()->has('error'))
                toastr.error('{{ session('error') }}', 'Error!');
            @endif
        });
    </script>
@endsection
