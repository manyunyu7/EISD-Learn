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
    {{-- Toastr --}}
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Datatables -->
    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#basic-datatables').DataTable({});

            $('#multi-filter-select').DataTable({
                "pageLength": 5,
                initComplete: function () {
                    this.api().columns().every(function () {
                        var column = this;
                        var select = $('<select class="form-control"><option value=""></option></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                }
            });

            // Add Row
            $('#add-row').DataTable({
                "pageLength": 5,
            });

            var action = '<td> <div class="form-button-action"> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

            $('#addRowButton').click(function () {
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


    <div class="page-inner bg-white">
        <div class="col-md-12 mt-2">
            {{-- BREADCRUMB --}}
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href={{url('/home')}}>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Exam</li>
                </ol>
            </nav>
        </div>


        <div class="col-12">
            <div class="page-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-4 col-sm-6 col-md-3 col-lg-3 px-4">
                            <!-- Atur ukuran kolom sesuai kebutuhan Anda -->
                            <button type="button" class="btn btn-custom" style="width: 75%;"
                                    onclick="redirectToSection('{{ url('/exam/manage-exam-v2/create-exam') }}')">
                                <span>Add</span>
                            </button>
                        </div>
                    </div>
                </div>

                <script>
                    function redirectToSection(url) {
                        window.location.href = url;
                    }
                </script>
            </div>
        </div>
        <div class="container-fluid">

            <div class="row">
                <h1 class="mb-3 col-12"><b>Quiz/Pre Test/Post Test/Evaluation</b></h1>

                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead style="background-color: #ebebeb; text-align: center;">
                            <th><h3><b>Judul</b></h3></th>
                            <th><h3><b>Manage</b></h3></th>
                            </thead>
                            <tbody>
                            @forelse ($dayta as $data)
                                <tr>
                                    <td style="overflow: hidden; white-space: nowrap;">
                                        {{ $data->title }}
                                    </td>

                                    <td style="text-align: center;">
                                        <div class="d-flex justify-content-center">
                                            <!-- Tombol-tombol di dalam baris yang responsif -->
                                            <button class="btn mr-2" style="background-color: #4BC9FF;
                                                                    border-radius: 15px;
                                                                    width:45px;
                                                                    height: 40px;
                                                                    position: relative;
                                                                    padding: 0;
                                                                    display: flex;
                                                                    align-items: center;
                                                                    justify-content: center;">
                                                <img src="{{ url('/Icons/Download.svg') }}"
                                                     style="max-width: 100%; max-height: 100%;">
                                            </button>
                                            <button class="btn mr-2" style="background-color: #6DCBA8;
                                                                    border-radius: 15px;
                                                                    width:45px;
                                                                    height: 40px;
                                                                    position: relative;
                                                                    padding: 0;
                                                                    display: flex;
                                                                    align-items: center;
                                                                    justify-content: center;">
                                                <img src="{{ url('/Icons/Link.svg') }}"
                                                     style="max-width: 100%; max-height: 100%;">
                                            </button>
                                            <button class="btn mr-2" style="background-color: #208DBB;
                                                                    border-radius: 15px;
                                                                    width:45px;
                                                                    height: 40px;
                                                                    position: relative;
                                                                    padding: 0;
                                                                    display: flex;
                                                                    align-items: center;
                                                                    justify-content: center;"
                                                    onclick="redirectToSection_edit('{{ url('/exam/manage-exam-v2/' . $data->id . '/load-exam') }}')">
                                                <img src="{{ url('/Icons/Edit.svg') }}"
                                                     style="max-width: 100%; max-height: 100%;">
                                            </button>
                                            <form id="deleteForm_{{ $data->id }}" action="{{ route('exam.delete', $data->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn delete-btn"
                                                        style="background-color: #FC1E01;
                                                               border-radius: 15px;
                                                               width:45px;
                                                               height: 40px;
                                                               position: relative;
                                                               padding: 0;
                                                               display: flex;
                                                               align-items: center;
                                                               justify-content: center;"
                                                        data-id="{{ $data->id }}">
                                                    <img src="{{ url('/Icons/Delete.svg') }}"
                                                         style="max-width: 100%; max-height: 100%;">
                                                </button>
                                            </form>
                                            
                                            <!-- SweetAlert Library -->
                                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                                            <script>
                                                // Setiap tombol hapus memiliki kelas .delete-btn
                                                document.querySelectorAll('.delete-btn').forEach(item => {
                                                    item.addEventListener('click', function(event) {
                                                        event.preventDefault(); // Prevent the default form submission
                                                        
                                                        const sectionId = this.getAttribute('data-id');
                                            
                                                        Swal.fire({
                                                            title: 'Are you sure?',
                                                            text: "You won't be able to revert this!",
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#3085d6',
                                                            cancelButtonColor: '#d33',
                                                            confirmButtonText: 'Yes, delete it!'
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                // Submit the form programmatically
                                                                document.getElementById('deleteForm_' + sectionId).submit();
                                                            }
                                                        });
                                                    });
                                                });
                                            </script>
                                            
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" style="text-align: center;">No data available</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <script>
                    function redirectToSection_edit(url) {
                        window.location.href = url;
                    }
                </script>
            </div>
        </div>

    </div>

@endsection




