@extends('main.template')

@section('head-section')
    <!-- Datatables -->

    <script src="{{ asset('atlantis/examples') }}/assets/js/plugin/datatables/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

                <!-- Filter and Search Section -->
                <div class="col-12 d-flex mb-3">
                    <select id="sortSelect" class="form-control mr-2">
                        <option value="latest">Latest</option>
                        <option value="oldest">Oldest</option>
                    </select>
                    <select id="filterSelect" class="form-control mr-2">
                        <option value="">All</option>
                        <option value="Pre Test">Pre Test</option>
                        <option value="Post Test">Post Test</option>
                        <option value="Evaluation">Evaluation</option>
                    </select>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by title">
                </div>

                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="mt-5" style="background-color: #ebebeb; text-align: center;">
                                <tr>
                                    <th>
                                        <h3><b>Judul</b></h3>
                                    </th>
                                    <th>
                                        <h3><b>Jenis Exam</b></h3>
                                    </th>
                                    <th>
                                        <h3><b>Manage</b></h3>
                                    </th>
                                    <th>
                                        <h3><b>Status</b></h3>
                                    </th>
                                    <th>
                                        <h3><b></b></h3>
                                    </th>
                                    <th>
                                        <h3><b>Tanggal Pembuatan</b></h3>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="examTableBody">
                                @forelse ($dayta as $data)
                                    <tr data-date="{{ $data->created_at }}" data-type="{{ $data->exam_type }}">
                                        <td style="overflow: hidden; white-space: nowrap;">
                                            {{ $data->title }}
                                        </td>
                                        <td style="overflow: hidden; white-space: nowrap;">
                                            {{ $data->exam_type }}
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


                                                <!-- BTN EDIT EXAM META -->
                                                @php
                                                    $isDisabled = (($data->is_examUsed === 'Exam Used')) || $data->takers_count != 0;
                                                    $buttonColor = $isDisabled ? '#DFDFDF' : '#FFE500';
                                                    $iconSrc = (($data->is_examUsed === 'Exam Used')) || $data->takers_count != 0 ? 'icons/Edit_disabled.svg' : 'icons/edit_exam_meta_icon.svg';
                                                    $tooltipTitle = (($data->is_examUsed === 'Exam Used')) || $data->takers_count != 0 ? 'Exam Ini Tidak Dapat Diedit Karena Telah Digunakan' : 'Edit jadwal, akses, dan lainnya';
                                                @endphp
                                                <button 
                                                    class="btn mr-2"
                                                    {{ $isDisabled ? 'disabled' : '' }}
                                                    style="background-color: {{ $buttonColor }};
                                                        border-radius: 15px;
                                                        width:45px;
                                                        height: 40px;
                                                        position: relative;
                                                        padding: 0;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;"
                                                    onclick="redirectToSection_edit('{{ url('/exam/' . $data->id . '/edit') }}')"
                                                    title="{{ $tooltipTitle }}"
                                                    data-toggle="tooltip">
                                                    <img src="{{ url($iconSrc) }}" style="max-width: 100%; max-height: 100%;">
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
                                                {{-- <form id="deleteForm_{{ $data->id }}"
                                                    action="{{ route('exam.delete', $data->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn delete-btn" data-id="{{ $data->id }}"
                                                        {{ $data->is_examUsed === 'Exam Used' ? 'disabled' : ''}}
                                                        style=" background-color: {{ $data->takers_count != 0 || $data->is_examUsed === 'Exam Used' ? '#DFDFDF' : '#FC1E01' }};
                                                        border-radius: 15px;
                                                        width: 45px;
                                                        height: 40px;
                                                        position: relative;
                                                        padding: 0;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;"
                                                        data-toggle="tooltip"
                                                        title="{{ $data->takers_count != 0 || $data->is_examUsed === 'Exam Used' ? 'Exam tidak dapat dihapus karena telah digunakan di Course atau sedang berlangsung' : 'Hapus Exam' }}">
                                                        <img src="{{ $data->takers_count != 0 || $data->is_examUsed === 'Exam Used' ? url('/icons/disabled_delete_button.svg') : url('/icons/Delete.svg') }}" style="max-width: 100%; max-height: 100%;">
                                                    </button>
                                                </form> --}}
                                                <form id="deleteForm_{{ $data->id }}" action="#" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn delete-btn" data-id="{{ $data->id }}"
                                                        {{ $data->is_examUsed === 'Exam Used' ? 'disabled' : ''}}
                                                        style=" background-color: {{ $data->takers_count != 0 || $data->is_examUsed === 'Exam Used' ? '#DFDFDF' : '#FC1E01' }};
                                                        border-radius: 15px;
                                                        width: 45px;
                                                        height: 40px;
                                                        position: relative;
                                                        padding: 0;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;"
                                                        data-toggle="tooltip"
                                                        title="{{ $data->takers_count != 0 || $data->is_examUsed === 'Exam Used' ? 'Exam tidak dapat dihapus karena telah digunakan di Course atau sedang berlangsung' : 'Hapus Exam' }}">
                                                        <img src="{{ $data->takers_count != 0 || $data->is_examUsed === 'Exam Used' ? url('/icons/disabled_delete_button.svg') : url('/icons/Delete.svg') }}" style="max-width: 100%; max-height: 100%;">
                                                    </button>
                                                </form>
                                                <script>
                                                    // Setiap tombol hapus memiliki kelas .delete-btn
                                                    document.querySelectorAll('.delete-btn').forEach(item => {
                                                        item.addEventListener('click', function() {
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
                                                                    // Set action form dengan menggunakan sectionId
                                                                    document.getElementById('deleteForm_' + sectionId).action = sectionId + "/delete";;
                                                                    document.getElementById('deleteForm_' + sectionId).submit();
                                                                }
                                                            });
                                                        });
                                                    });
                                                </script>
                                            </div>
                                        </td>
                                        <td style="text-align: center;">
                                            <h4><b>{{ $data->status }}</b></h4>
                                        </td>
                                        <td style="text-align: center;">
                                            <h4><b>{{ $data->is_examUsed }}</b></h4>
                                        </td>
                                        <td style="text-align: center;">
                                            <h4><b>{{ $data->created_at }}</b></h4>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center;">No data available</td>
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

                <script>
                    const sortSelect = document.getElementById('sortSelect');
                    const filterSelect = document.getElementById('filterSelect');
                    const searchInput = document.getElementById('searchInput');
                    const examTableBody = document.getElementById('examTableBody');

                    sortSelect.addEventListener('change', filterAndSort);
                    filterSelect.addEventListener('change', filterAndSort);
                    searchInput.addEventListener('keyup', filterAndSort);

                    function filterAndSort() {
                        const sortValue = sortSelect.value;
                        const filterValue = filterSelect.value.toLowerCase();
                        const searchValue = searchInput.value.toLowerCase();

                        const rows = Array.from(examTableBody.getElementsByTagName('tr'));

                        rows.forEach(row => {
                            const title = row.cells[0].textContent.toLowerCase();
                            const type = row.getAttribute('data-type').toLowerCase();
                            const date = row.getAttribute('data-date');

                            let shouldShow = true;

                            if (filterValue && filterValue !== type) {
                                shouldShow = false;
                            }

                            if (searchValue && !title.includes(searchValue)) {
                                shouldShow = false;
                            }

                            row.style.display = shouldShow ? '' : 'none';
                        });

                        rows.sort((a, b) => {
                            const dateA = new Date(a.getAttribute('data-date'));
                            const dateB = new Date(b.getAttribute('data-date'));
                            return sortValue === 'latest' ? dateB - dateA : dateA - dateB;
                        });

                        rows.forEach(row => examTableBody.appendChild(row));
                    }

                    filterAndSort(); // Initial sort/filter
                </script>
            </div>
        </div>

    </div>
@endsection
