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
                    <h3 class="page-title">More Information</h3>
                    <h4>Name : {{ $user->name }}</h4>
                    <h4>ID : {{ $user->id }}</h4>
                </div>
                <div class="col-12 text-center text-sm-left mb-0">
                    <p>Bergabung sejak {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y H:i:s') }}</p>
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
                            <table id="basic-datatables" class="table table-bordered">
                                <thead class="text-center">
                                    <tr>
                                        <th>ID Class</th>
                                        <th>Class Name</th>
                                        <th>Join Date</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($progress_class as $userInfo)
                                        <tr>
                                            <td>{{ $userInfo['lesson_id'] }}</td>
                                            <td>{{ $userInfo['lesson_title'] }}</td>
                                            <td>{{ $userInfo['join_date'] }}</td>
                                            <td>{{ $userInfo['progress'] }}</td>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
