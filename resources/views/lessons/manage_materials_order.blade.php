@extends('main.template')

@section('head-section')
    <!-- Datatables -->

    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>
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

    <script src="{{asset('atlantis/examples')}}/assets/js/plugin/dropzone/dropzone.min.js"></script>

@endsection


@section('main')
<br><br>
    <div class="col-md-12" >
        {{-- BREADCRUMB --}}
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href={{url('/home')}}>Home</a></li>
                <li class="breadcrumb-item"><a href={{url('/lesson/manage_v2')}}>Class</a></li>
                <li class="breadcrumb-item"><a href={{url('/lesson/manage-materials/'.$lesson_id)}}>Add Class</a></li>
                <li class="breadcrumb-item active" aria-current="page">Rearrange Materials</li>
            </ol>
        </nav>
    </div>

    <div class="container page-inner">
        <div class="page-header">
            <h1><strong>REARRANGE</strong></h1>
        </div>

        <button id="save-order-button" type="button" class="btn btn-primary btn-border btn-round">
            Simpan Urutan
        </button>

        <table class="table" id="sortable-table">
            <thead style="background-color: #ebebeb;">
                <tr class="text-center">
                    <th><h3><b>Urutan</b></h3></th>
                    <th><h3><b>Materi</b></h3></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dayta as $item)
                <tr data-id="{{ $item->section_id }}">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->section_title }}</td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.13.0/Sortable.min.js"></script>
    <script>
        var table = document.getElementById('sortable-table');
        var orders = []; // Array to store order information

        new Sortable(table.getElementsByTagName('tbody')[0], {
            animation: 150,
            onUpdate: function (evt) {
                var tbody = evt.from;
                var items = tbody.children;
                var newOrder = Array.from(items).map(function(item) {
                    return item.getAttribute('data-id');
                });

                // Clear the orders array before updating
                orders = [];

                // Loop through the items to collect order information
                Array.from(items).forEach(function(item, index) {
                    var id = item.getAttribute('data-id');
                    var newPosition = index + 1;

                    // Store the order information in the orders array
                    var order = { id: id, newPosition: newPosition };
                    orders.push(order);
                });

                // Kirim data urutan baru ke server menggunakan AJAX
                // Pastikan Anda mengirimkan data id dan newPosition ke server
                // Contoh menggunakan fetch API:
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/update-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken, // Jangan lupa ganti dengan cara Anda memperoleh CSRF token
                    },
                    body: JSON.stringify({
                        orders: orders,
                        newOrder: newOrder, // Include newOrder array in the request
                        lesson: {{ $lesson_id }}
                    })
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Gagal memperbarui urutan');
                        }
                        // Handle respons dari server jika diperlukan
                    })
                    .catch(error => {
                        console.error('Terjadi kesalahan:', error);
                    });
            }
        });    </script>

@endsection




