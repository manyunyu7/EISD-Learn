@extends('main.template')

@section('head-section')
    <!-- Datatables -->

    <script src="{{ asset('atlantis/examples') }}/assets/js/plugin/datatables/datatables.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
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
        // Function to fetch positions based on selected type
        function fetchPositions() {
            fetch('/fetch-positions', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(function(positions) {
                    var positionDropdown = document.getElementById('position_id');
                    // Clear existing options
                    positionDropdown.innerHTML = '<option value="" disabled>Pilih Posisi</option>';
                    // Populate dropdown with fetched positions
                    positions.forEach(function(position) {
                        var option = document.createElement('option');
                        option.textContent = position.name;
                        option.value = position.id;
                        positionDropdown.appendChild(option);
                    });
                    // Initialize Select2 if needed
                    $('.js-example-basic-multiple').select2(); // Uncomment this line if using Select2
                })
                .catch(function(error) {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }
        // Add event listener to trigger fetchPositions() when the DOM content is loaded
        document.addEventListener('DOMContentLoaded', function() {
            fetchPositions(); // Call fetchPositions() when the DOM content is loaded
        });

        // Function to show/hide department dropdown based on radio button selection
        function toggleDepartmentDropdown() {
            var departmentDropdown = document.getElementById('department_id');
            var radioGeneral = document.getElementById('general');
            var radioSpecific = document.getElementById('specific');

            if (radioGeneral.checked) {
                departmentDropdown.disabled = true;
                departmentDropdown.innerHTML = ''; // Clear existing options
            } else if (radioSpecific.checked) {
                departmentDropdown.disabled = false;
                // Fetch departments
                fetchDepartments();
            }
        }

        // Function to fetch departments based on selected type
        function fetchDepartments() {
            fetch('/fetch-departments', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(function(departments) {
                    var departmentDropdown = document.getElementById('department_id');
                    // Clear existing options
                    departmentDropdown.innerHTML = '';
                    // Populate dropdown with fetched departments
                    departments.forEach(function(department) {
                        var option = document.createElement('option');
                        option.textContent = department.name;
                        option.value = department.id;
                        departmentDropdown.appendChild(option);
                    });
                    // Initialize Select2 if needed
                    $('.js-example-basic-multiple').select2(); // Uncomment this line if using Select2
                })
                .catch(function(error) {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }

        // Add event listener to radio buttons
        document.querySelectorAll('input[name="tipe"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                toggleDepartmentDropdown();
            });
        });
    </script>

    {{-- SETTING PREVIEW INPUT IMAGES --}}
    <script>
        window.onload = function() {
            // jQuery and everything else is loaded
            var el = document.getElementById('input-image');
            el.onchange = function() {
                var fileReader = new FileReader();
                fileReader.readAsDataURL(document.getElementById("input-image").files[0])
                fileReader.onload = function(oFREvent) {
                    document.getElementById("imgPreview").src = oFREvent.target.result;
                };
            }

            $(document).ready(function() {
                $.myfunction = function() {
                    $("#previewName").text($("#inputTitle").val());
                    var title = $.trim($("#inputTitle").val())
                    if (title == "") {
                        $("#previewName").text("Judul")
                    }
                };

                $("#inputTitle").keyup(function() {
                    $.myfunction();
                });

            });
        }
    </script>


    <script>
        //message with toastr
        @if (session()->has('success'))
            toastr.success('{{ session('success') }}', 'BERHASIL!');
        @elseif (session()->has('error'))
            toastr.error('{{ session('error') }}', 'GAGAL!');
        @endif

        // Validation errors with toastr
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
        @endif
    </script>

    <script>
        // JavaScript to handle form submission and show loading indicator
        $(document).ready(function() {
            $('#department_id').select2({
                placeholder: 'Select students',
                allowClear: true,
                maximumSelectionLength: 3,
                width: 'resolve' // need to override the changed default
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection


@section('main')
    <div class="page-inner">

        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href={{ url('/home') }}>Home</a></li>
                <li class="breadcrumb-item"><a href={{ url('/lesson/manage_v2') }}>Class</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Class</li>
            </ol>
        </nav>

        <div class="container-fluid">
            <h1><b>Tambah Kelas Baru</b></h1>
        </div>

        <div class="container-fluid load-soal">
            <form id="addSessionForm" action="{{ url('/lesson/create_class') }}" method="POST"
                enctype="multipart/form-data" onsubmit="">
                @csrf
                {{-- <input hidden name="exam_id" type="text" value="{{ $examId }}"> --}}
                <div class="row">
                    <div class="col-md-8">
                        {{-- Password Kelas --}}
                        <div class="mb-3">
                            <label for="" class="mb-2">Password Kelas<span style="color: red">*</span></label>
                            <div class="input-group mb-3">
                                <input required name="pass_class" type="text" class="form-control"
                                    aria-label="Recipient's username" aria-describedby="basic-addon2"
                                    value="{{ old('pass_class') }}">
                            </div>
                        </div>

                        {{-- Judul Kelas --}}
                        <div class="mb-3">
                            <label for="" class="mb-2">Judul Kelas<span style="color: red">*</span></label>
                            <div class="input-group mb-3">
                                <input required name="title" type="text" class="form-control"
                                    aria-label="Recipient's username" aria-describedby="basic-addon2"
                                    value="{{ old('title') }}">
                            </div>
                        </div>

                        {{-- Kategori --}}
                        <div class="mb-3">
                            <label for="" class="mb-2">Kategori<span style="color: red">*</span></label>
                            <div class="input-group mb-3">
                                <select class="form-control" name="category_id" id="">
                                    <option value="" disabled>Pilih Kategori</option>
                                    @forelse($categories as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('category_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        {{-- Tipe --}}
                        <div class="mb-3">
                            <label for="" class="mb-2">Tipe<span style="color: red">*</span></label>
                            <div class="input-group mb-3">
                                <input type="radio" id="general" name="tipe" value="General"
                                    style="margin-right: 10px;" onclick="showGeneralInfo()"
                                    {{ old('tipe') == 'General' ? 'checked' : '' }}>
                                <label for="general" class="mr-3">General</label>

                                <input type="radio" id="specific" name="tipe" value="Specific"
                                    style="margin-right: 10px;" onclick="hideGeneralInfo()"
                                    {{ old('tipe') == 'Specific' ? 'checked' : '' }}>
                                <label for="specific">Specific</label>
                            </div>
                            <small id="generalInfo" style="color: red; display: inline;">Tipe General dapat diakses oleh
                                semua department</small>
                        </div>

                        <script>
                            function showGeneralInfo() {
                                document.getElementById("generalInfo").style.display = "inline";
                            }

                            function hideGeneralInfo() {
                                document.getElementById("generalInfo").style.display = "none";
                            }
                        </script>


                        {{-- Departemen --}}
                        <div class="mb-3">
                            <label for="" class="mb-2">Departemen<span style="color: red"></span></label>
                            <div class="input-group mb-3">
                                <select id="department_id" name="department_id[]"
                                    class="form-control form-select-lg js-example-basic-multiple" multiple
                                    disabled></select>
                            </div>
                        </div>



                        {{-- Posisi --}}
                        <div class="mb-3">
                            <label for="" class="mb-2">Posisi<span style="color: red"></span></label>
                            <div class="input-group mb-3">
                                <select id="position_id" name="position_id[]"
                                    class="form-control form-select-lg js-example-basic-multiple" multiple></select>
                            </div>
                        </div>

                        {{-- Target Employee --}}
                        {{-- <div class="mb-3">
                            <label for="" class="mb-2">Member -  Non Member<span style="color: red">*</span></label>
                            <div class="input-group">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input name="member" class="form-check-input" type="checkbox" value="Member">
                                        <span class="form-check-sign">Member</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input name="non_member" target_employee class="form-check-input" type="checkbox" value="Non Member">
                                        <span class="form-check-sign">Non Member</span>
                                    </label>
                                </div>
                            </div>
                        </div> --}}

                        {{-- Deskripsi Kelas --}}
                        <div class="mb-3">
                            <label for="" class="mb-2">Deskripsi Kelas</label>
                            <textarea id="editor" class="form-control" name="content">{{ old('content') }}</textarea>
                            <div id="error-message" style="color: red; display: none; font-size: 0.9em;"></div>
                            <script>
                                ClassicEditor
                                    .create(document.querySelector('#editor'))
                                    .catch(error => {
                                        console.error(error);
                                    });
                            </script>
                            @error('content')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- New Kelas --}}
                        <div class="mb-3">
                            <label for="" class="mb-2">New Kelas<span style="color: red">*</span></label>
                            <div class="input-group mb-3">
                                <input readonly type="text" value="Tidak Aktif" name="new_class" id="btn-new-clas"
                                    class="btn btn-danger" style="width: 100%">
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var btn_new_class = document.getElementById('btn-new-clas');
                                var isActive_NC = false;

                                // New Class Setup
                                btn_new_class.addEventListener('click', function() {
                                    // Tidak Aktif
                                    if (isActive_NC) {
                                        btn_new_class.classList.remove('btn-success');
                                        btn_new_class.classList.add('btn-danger');
                                        btn_new_class.textContent = 'Tidak Aktif';
                                        btn_new_class.value = 'Tidak Aktif';
                                        isActive_NC = false;
                                    }
                                    // Aktif
                                    else {
                                        btn_new_class.classList.remove('btn-danger');
                                        btn_new_class.classList.add('btn-success');
                                        btn_new_class.textContent = 'Aktif';
                                        btn_new_class.value = 'Aktif';
                                        isActive_NC = true;
                                    }
                                });
                            });
                        </script>
                    </div>

                    <div class="col-md-4">
                        {{-- Cover Class --}}
                        <div class="card mt-5">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="card" style="width: 100%; max-width: 1080px;">
                                        <img id="imgPreview" src="{{ Storage::url('public/class/cover/') }}"
                                            onerror="this.onerror=null; this.src='{{ url('/default/ratio_default.png') }}'; this.alt='Alternative Image';"
                                            class="rounded"
                                            style="max-width:3840px; max-height: 2160px; object-fit: contain;"
                                            alt="...">
                                    </div>

                                    <div class="input-group mb-3">
                                        <input required type="file" name="image" class="form-control"
                                            id="input-image" accept="image/*" onchange="validateImage(this)">
                                    </div>
                                    <small width="100%">Image size should be under 1 MB and image ratio needs to be
                                        16:9</small>
                                </div>
                            </div>
                        </div>

                        <script>
                            function validateForm() {
                                const editorContent = document.querySelector('#editor').value.trim();
                                const errorMessage = document.getElementById('error-message');

                                if (editorContent === '') {
                                    errorMessage.textContent = 'Deskripsi Kelas tidak boleh kosong.';
                                    errorMessage.style.display = 'block';
                                    return false; // Prevent form submission
                                } else {
                                    errorMessage.style.display = 'none';
                                    return true; // Allow form submission
                                }
                            }

                            function validateImage(input) {
                                if (input.files && input.files[0]) {
                                    var reader = new FileReader();
                                    reader.onload = function(e) {
                                        var img = new Image();
                                        img.src = e.target.result;
                                        img.onload = function() {
                                            var width = this.width;
                                            var height = this.height;
                                            var ratio = width / height;
                                            if (Math.abs(ratio - 16 / 9) > 0.01) { // Check if ratio is approximately 16:9
                                                alert("Image ratio must be 16:9");
                                                input.value = ""; // Clear the input file
                                            } else {
                                                // Display preview of the image
                                                document.getElementById('imgPreview').src = e.target.result;
                                            }
                                        };
                                    };
                                    reader.readAsDataURL(input.files[0]);
                                }
                            }
                        </script>

                    </div>
                </div>




                {{-- BUTTONS --}}
                <div class="mb-3" style="display: flex; justify-content: flex-end;">
                    <div style="flex-grow: 1;"></div>
                    <div style="width: 200px;">
                        <div class="input-group mb-3">
                            <button type="button" class="btn btn-danger"
                                style="width: 45%; margin-right: 5px;">Cancel</button>
                            <button type="submit" id="saveEditBtn" class="btn btn-success"
                                style="width: 45%; margin-left: 5px;">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
