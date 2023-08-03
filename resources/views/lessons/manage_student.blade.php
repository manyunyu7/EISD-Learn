@extends('main.template')

@section('head-section')
    <!-- Datatables -->

	<script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>
@endsection

@section('main')
<div class="container-fluid">
    <div class="container mt-5">


          <!-- Page Header -->
          <div class="page-header row no-gutters py-4">

            <div class="col-12 text-center text-sm-left mb-0">
                <span class="text-uppercase page-subtitle">Course</span>
                <h3 class="page-title">Siswa Terdaftar di {{ $lesson->course_title }}</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow rounded">
                        @if(session() -> has('success'))
                        <div class="alert alert-primary alert-dismissible fade show mx-2 my-2" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <strong>{{Session::get( 'success' )}}</strong>
                        </div>

                        @elseif(session() -> has('error'))

                        <div class="alert alert-primary alert-dismissible fade show mx-2 my-2" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <strong>{{Session::get( 'error' )}}</strong>

                        @endif



                    <div class="card-body">
                        <a href="{{ url('lesson/create') }}" >   <button class="btn btn-primary btn-border btn-round mb-3 d-none">Buat Kelas Baru</button></a>
                        <table id="basic-datatables" class="table table-bordered     @if (count($dayta) < 1)
                        d-none
                      @endif">
                            <thead>
                              <tr>
                                 <th>Photo</th>
                                 <th>Name</th>
                                 <th>Section Taken Count</th>
                                 <th>Section Remaining Count</th>
                                 <th>Completion</th>
                              </tr>
                            </thead>
                            <tbody>
                                 @foreach ($studentData as $student)
                                            <tr>
                                                   <td>
                                                      <div class="avatar avatar-online">
                            <span class="avatar-title rounded-circle border border-white bg-info">
                                <img src="{{ Storage::url('public/profile/') . $student['student_profile_url'] }}" alt="" class="avatar-img rounded-circle">
                            </span>
                        </div>
                                                    </td>
                                                <td>
                                                    {{ $student['student_name'] }}</td>
                                                <td>
                                                    {{$student['section_taken_count']}}
                                                </td>
                                                <td>
                                                    {{$student['section_remaining_count']}}
                                                </td>
                                                <td>{{ $student['completion_percentage'] }}</td>
                                            </tr>
                                        @endforeach
                            </tbody>
                          </table>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- {{ $blogs->links() }} --}}
            </div>
        </div>
    </div>
</div>

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



