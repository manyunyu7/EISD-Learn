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
                function() {
                    $.ajax({
                        type: "POST",
                        url: "{{url('/destroy')}}",
                        data: {id:id},
                        success: function (data) {
                            //
                        }
                    });
                });
        });

    </script>
    {{-- Toastr --}}
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Datatables -->
    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#basic-datatables').DataTable({
            });

            $('#multi-filter-select').DataTable( {
                "pageLength": 5,
                initComplete: function () {
                    this.api().columns().every( function () {
                        var column = this;
                        var select = $('<select class="form-control"><option value=""></option></select>')
                            .appendTo( $(column.footer()).empty() )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );

                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    } );
                }
            });

            // Add Row
            $('#add-row').DataTable({
                "pageLength": 5,
            });

            var action = '<td> <div class="form-button-action"> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

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
        @if(session()-> has('success'))
        toastr.success('{{ session('success') }}', 'BERHASIL!');
        @elseif(session()-> has('error'))
        toastr.error('{{ session('error') }}', 'GAGAL!');
        @endif
    </script>


@endsection

@section('main')

    <div class="page-inner"  style="background-color: white !important">
        <div class="page-header">
            <h4 class="page-title">Registration Code</h4>
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
                    <a href="#">Registration Code</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Registration Code Yang Pernah Dibuat</div>
                    </div>
                    <div class="card-body">
                        <a href="{{ url('registration-code-management/create') }}">
                            <button class="btn btn-primary btn-border btn-round mb-3">Tambah Kode Baru
                            </button>
                        </a>
                        <div class="table-responsive">
                            <table id="basic-datatables" class="table table-bordered  @if (count($datas) < 1) d-none @endif">
                                <thead>
                                <tr>
                                    <th scope="col">Kode</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th>Jabatan</th>
                                    <th>Department</th>
                                    <th scope="col">Business Unit</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($datas as $data)
                                    <tr>
                                        <td>{{ $data->registration_code }}</td>
                                        <td>
                                            @if($data->is_active === 'y')
                                                <span class="badge badge-success">Active</span>
                                            @elseif($data->is_active === 'n')
                                                <span class="badge badge-danger">Inactive</span>
                                            @else
                                                <span class="badge badge-warning">Unknown</span>
                                            @endif
                                        </td>
                                        <td>{!! $data->notes !!}</td>
                                        <td>{{ $data->position_name }}</td>
                                        <td>{{ $data->department_name }}</td>
                                        <td>
                                            @foreach ($data->location_names as $location)
                                                {{ $location }}<br>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{ route('registration_code.edit', $data->id) }}" class="btn btn-sm btn-primary">EDIT</a>
                                        </td>
                                    </tr>
                                @empty
                                    <div class="alert alert-danger">
                                        Belum Ada Kode Registrasi
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
