@extends('main.template')

@section('head-section')
    <!-- Datatables -->
    <!-- DataTables CSS -->
    {{--    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.min.css">--}}
    <!-- DataTables Buttons CSS -->
    {{--    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">--}}
    {{--    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>--}}

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

@endsection

@section('main')

    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Course</h4>
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
                    <a href="#">Course</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Manage</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Daftar Kelas Milik Anda</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables"
                                   class="table table-bordered  @if (count($dayta) < 1) d-none @endif">
                                <thead>
                                <tr>
                                    <th scope="col">GAMBAR</th>
                                    <th scope="col">JUDUL</th>
                                    <th scope="col">MATERI</th>
                                    <th scope="col">SISWA</th>
                                    <th scope="col">CONTENT</th>
                                    <th scope="col">PENULIS</th>
                                    <th scope="col">KATEGORI</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($dayta as $data)
                                    <tr>
                                        <td class="text-center">
                                            <img
                                                src="{{ Storage::url('public/class/cover/').$data->course_cover_image }}"
                                                class="rounded" style="width: 150px">
                                        </td>
                                        <td>{{ $data->course_title }}</td>
                                        @php
                                            $course_id = $data->id
                                        @endphp
                                        <td><a href="{{url("/lesson/$course_id/section/")}}"
                                               class="badge badge-primary">Manage Materi</a></td>
                                        <td><a href="{{url("/lesson/$course_id/students/")}}"
                                               class="badge badge-primary">Manage Siswa</a></td>
                                        <td><a href="{{url("/lesson/$course_id")}}">
                                                <button type="button" class="btn btn-outline-primary">Lihat Kelas
                                                </button>
                                            </a></td>
                                        <td>{{ $data->mentor_name}}</td>
                                        @php
                                            $cat = $data->course_category;
                                        @endphp
                                        <td>{{  $cat }}</td>
                                        <td class="text-center">
                                            <form id="delete-post-form"
                                                  action="{{ route('lesson.destroy', $data->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Are you sure?')"
                                                        class="btn btn-sm btn-danger ">HAPUS
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <a href="{{ route('lesson.edit', $data->id) }}"
                                               class="btn btn-sm btn-primary">EDIT</a>
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
