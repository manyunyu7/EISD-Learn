@extends('main.template')

@section('head-section')
<!-- Datatables -->


<style>
    .switch-container.switch-absensi-container {
        position: relative;
    }

    .switch.switch-absensi {
        position: absolute;
        cursor: pointer;
        width: 50px;
        height: 25px;
        border-radius: 25px;
        background-color: #ccc;
        transition: background-color 0.3s;
    }

    .switch.switch-absensi::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background-color: white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s;
    }

    input#switch-absensi[type="checkbox"] {
        display: none;
    }

    input#switch-absensi[type="checkbox"]:checked+.switch.switch-absensi {
        background-color: #4caf50;
    }

    input#switch-absensi[type="checkbox"]:checked+.switch.switch-absensi::after {
        transform: translateX(25px);
    }

    .switch-text.switch-absensi-text {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        left: 70px;
        /* Adjust as needed */
    }
</style>
<script src="{{asset('atlantis/examples')}}/assets/js/plugin/datatables/datatables.min.js"></script>
@endsection

@section('script')
<script>
    $(document).on('click', '.button', function(e) {
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
                    data: {
                        id: id
                    },
                    success: function(data) {
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
        $('#basic-datatables').DataTable({});

        $('#multi-filter-select').DataTable({
            "pageLength": 5,
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var select = $('<select class="form-control"><option value=""></option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
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



@endsection

@section('main')

<div class="page-inner" style="background-color: white !important">
    <div class="page-header">
        <h4 class="page-title">Absensi</h4>
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
                <a href="#">{{$course->course_title}}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{$section->section_title}}</a>
            </li>
        </ul>
    </div>
    <div class="row">

        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h2>QR Code</h2>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 text-center">
                            {!! QrCode::size(450)->generate($qrFormula) !!}
                        </div>
                        <div class="col-12 mt-4 mb-5 text-center">
                            <!-- Add button to regenerate QR code -->
                            <!-- Check if $enableAbsensi is 'y', null, or true -->
                            <div style="margin-left: -50px">
                                <input type="checkbox" id="switch-absensi" {{ $enableAbsensi === 'y' || $enableAbsensi === null || $enableAbsensi === true ? 'checked' : '' }}>
                                <label for="switch-absensi" class="switch switch-absensi"></label>
                                <div class="d-none">
                                    <span id="switch-text" class="switch-text switch-absensi-text d-inline-block align-middle text-center" style="margin-top: 10px; margin-left: 10px; ">
                                        <!-- Display 'Aktif' if $enableAbsensi is 'y', null, or true -->
                                        {{ $enableAbsensi === 'y' || $enableAbsensi === null || $enableAbsensi === true ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </div>
                            </div>

                        </div>
                        <div class="col-12 text-center mt-4">
                            <button onclick="window.location.reload();" class="btn btn-primary">Regenerate QR</button>
                        </div>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


                    <script>
                        const absensiSwitch = document.getElementById('switch-absensi');
                        const switchText = document.getElementById('switch-text');

                        absensiSwitch.addEventListener('change', function() {
                            const sectionId = `{{$sectionId}}`; // Replace with actual section ID
                            const enabled = this.checked; // true if checked, false if unchecked
                            const switchTextContent = enabled ? 'Aktif' : 'Tidak Aktif'; // Set switch text based on state

                            // Update switch text immediately
                            switchText.textContent = switchTextContent;

                            fetch('/update-absensi', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Assuming you're using CSRF protection
                                    },
                                    body: JSON.stringify({
                                        section_id: sectionId,
                                        enabled: enabled,
                                    }),
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Network response was not ok');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    console.log(data); // Handle success response
                                    // Update switch text based on response
                                    switchText.textContent = data.enabled ? 'Aktif' : 'Tidak Aktif';
                                    // Show SweetAlert
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: 'Absensi Berhasil Diupdate',
                                    });
                                })
                                .catch(error => {
                                    console.error('There was an error!', error);
                                    // Show SweetAlert for error
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Absensi Gagal Diupdate',
                                    });
                                    // Revert switch state and text if there was an error
                                    this.checked = !enabled;
                                });
                        });
                    </script>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Daftar Absensi</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="table table-bordered  @if (count($students) < 1) d-none @endif">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Waktu Absensi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->department }}</td>
                                    <td>{{ $data->created_at }}</td>
                                </tr>
                                @empty
                                <div class="alert alert-danger">
                                    Belum ada siswa yang absensi
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
