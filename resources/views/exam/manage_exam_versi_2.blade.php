@extends('main.template')

@section('head-section')
    <!-- Datatables -->

    <script src="{{ asset('atlantis/examples') }}/assets/js/plugin/datatables/datatables.min.js"></script>
@endsection

@section('script')
    <script>
        function copyLink(button) {
            // Get the link from the data-link attribute
            const link = button.getAttribute('data-link');

            // Create a temporary input element to hold the link
            const tempInput = document.createElement('input');
            tempInput.value = link;
            document.body.appendChild(tempInput);

            // Select the text inside the temporary input element
            tempInput.select();
            tempInput.setSelectionRange(0, 99999); // For mobile devices

            // Copy the selected text to the clipboard
            document.execCommand('copy');

            // Remove the temporary input element
            document.body.removeChild(tempInput);

            // Show a SweetAlert notification
            Swal.fire({
                title: 'Link Copied!',
                text: 'The link has been copied to your clipboard.',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500 // Auto-close after 1.5 seconds
            });
        }
    </script>


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
                        url: "{{ url('/destroy') }}",
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
    <script src="{{ asset('atlantis/examples') }}/assets/js/plugin/datatables/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#basic-datatables').DataTable({});

            $('#multi-filter-select').DataTable({
                "pageLength": 5,
                initComplete: function() {
                    this.api().columns().every(function() {
                        var column = this;
                        var select = $(
                                '<select class="form-control"><option value=""></option></select>'
                            )
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
                            select.append('<option value="' + d + '">' + d +
                                '</option>')
                        });
                    });
                }
            });

            // Add Row
            $('#add-row').DataTable({
                "pageLength": 5,
            });

            var action =
                '<td> <div class="form-button-action"> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

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
        @if (session()->has('success'))
            toastr.success('{{ session('success') }}', 'BERHASIL!');
        @elseif (session()->has('error'))
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
                    <li class="breadcrumb-item"><a href={{ url('/home') }}>Home</a></li>
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
                                <th>
                                    <h3><b>Judul</b></h3>
                                </th>
                                <th>
                                    <h3><b>Manage</b></h3>
                                </th>
                                <th>
                                    <h3><b>Status</b></h3>
                                </th>
                            </thead>
                            <tbody>
                                @forelse ($dayta as $data)
                                    <tr>
                                        <td style="overflow: hidden; white-space: nowrap;">
                                            {{ $data->title }}
                                        </td>

                                        <td style="text-align: center;">
                                            <div class="d-flex justify-content-center">
                                                <!-- BTN DOWNLOAD -->
                                                <button class="btn mr-2"
                                                    style="background-color: #4BC9FF;
                                                           border-radius: 15px;
                                                           width:45px;
                                                           height: 40px;
                                                           position: relative;
                                                           padding: 0;
                                                           display: flex;
                                                           align-items: center;
                                                           justify-content: center;"
                                                    onclick="redirectToSection_download('{{ url('/exam/download-exam/' . $data->id) }}')"
                                                    data-toggle="tooltip" title="Download Exam">
                                                    <img src="{{ url('/icons/Download.svg') }}"
                                                        style="max-width: 100%; max-height: 100%;">
                                                </button>

                                                <!-- BTN COPY LINK -->
                                                <button class="btn mr-2" onclick="copyLink(this)"
                                                    data-link="{{ url('/public-exam/' . $data->id) }}"
                                                    style="background-color: #6DCBA8;
                                                           border-radius: 15px;
                                                           width:45px;
                                                           height: 40px;
                                                           position: relative;
                                                           padding: 0;
                                                           display: flex;
                                                           align-items: center;
                                                           justify-content: center;"
                                                    data-toggle="tooltip" title="Copy Link">
                                                    <img src="{{ url('/icons/Link.svg') }}"
                                                        style="max-width: 100%; max-height: 100%;">
                                                </button>

                                                <!-- BTN EDIT EXAM -->
                                                <button class="btn mr-2"
                                                    style="background-color: #208DBB;
                                                           border-radius: 15px;
                                                           width:45px;
                                                           height: 40px;
                                                           position: relative;
                                                           padding: 0;
                                                           display: flex;
                                                           align-items: center;
                                                           justify-content: center;"
                                                    onclick="redirectToSection_edit('{{ url('/exam/manage-exam-v2/' . $data->id . '/edit-question') }}')"
                                                    data-toggle="tooltip" title="Edit Pertanyaan dan Jawaban">
                                                    <img src="{{ url('icons/Edit.svg') }}"
                                                        style="max-width: 100%; max-height: 100%;">
                                                </button>

                                                <!-- BTN DELETE -->
                                                <form id="deleteForm_{{ $data->id }}"
                                                    action="{{ route('exam.delete', $data->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn delete-btn" data-id="{{ $data->id }}"
                                                        {{ ($data->status === 'Ongoing' or $data->status === 'Finish' or $data->is_examUsed === 'Scored') ? 'disabled' : '' }}
                                                        style="background-color: #FC1E01;
                                                               border-radius: 15px;
                                                               width:45px;
                                                               height: 40px;
                                                               position: relative;
                                                               padding: 0;
                                                               display: flex;
                                                               align-items: center;
                                                               justify-content: center;"
                                                        data-toggle="tooltip" title="Delete Exam">
                                                        <img src="{{ url('/icons/Delete.svg') }}"
                                                            style="max-width: 100%; max-height: 100%;">
                                                    </button>
                                                </form>
                                            </div>
                                        </td>


                                        <td style="text-align: center;">
                                            <h4><b>{{ $data->status }}</b></h4>
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

                    function redirectToSection_edit_exam(url) {
                        window.location.href = url;
                    }

                    function redirectToSection_download(url) {
                        window.location.href = url;
                    }
                </script>
            </div>
        </div>

    </div>
@endsection
