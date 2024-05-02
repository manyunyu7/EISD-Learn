@extends('main.template')


@section('head-section')
    @include('main.home._styling_home_student')

@endsection


@section('script')
    {{-- @include('main.home.script_student') --}}
    <script>
        const DISPLAY = true;
        const BORDER = true;
        const CHART_AREA = true;
        const TICKS = true;
        const ctx = document.getElementById('myChart').getContext('2d');
        new Chart(ctx, {
          type: 'line',
          data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [
              {
                label: 'Blue Line',
                data: [12, 19, 3, 5, 2, 3, 12, 19, 3, 5, 2, 3],
                borderColor: 'blue',
                borderWidth: 2,
                fill: false
              },
              {
                label: 'Red Line',
                data: [5, 9, 8, 2, 6, 7, 5, 9, 8, 2, 6, 7],
                borderColor: 'red',
                borderWidth: 2,
                fill: false
              }
            ]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      </script>
       {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-U9zBET2NSPdld3JMGN9s3Qa/s6zrmMzNMI7d7bPKL6KA6aSX4N2p1Nex/aD1xOfq" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script>
        // FETCHING API AUTO SEARCH
        document.addEventListener('DOMContentLoaded', function () {
            // Add event listener to department dropdown
            var departmentDropdown = document.getElementById('department_id');
            departmentDropdown.addEventListener('change', function () {
                var departmentId = this.value;
                // Make a Fetch request to fetch students based on department ID
                fetch('/find-student-by-department', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ name_of_department: departmentId })
                })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(function (students) {
                    // Clear existing options
                    var studentDropdown = document.getElementById('student_id');
                    studentDropdown.innerHTML = '<option value="">Select a Student</option>';
                    // Populate student dropdown with fetched students
                    students.forEach(function (student) {
                        var option = document.createElement('option');
                        option.textContent = student.name;
                        option.value = student.id;
                        studentDropdown.appendChild(option);
                    });
                })
                .catch(function (error) {
                    console.error('There was a problem with the fetch operation:', error);
                });
            });
        });

        // SUMBIT FORM SORT BY
        document.addEventListener('DOMContentLoaded', function () {
            // Add event listener to department dropdown
            var sortByDropdown = document.getElementById('sortSelect');
            const sortForm = document.getElementById('sortForm');

            sortSelect.addEventListener('change', function() {
                sortForm.submit();
            });
        });


    </script>

    <script>
        // JavaScript to handle form submission and show loading indicator
        $(document).ready(function () {
            $('#student_id').select2({
                placeholder: 'Select students',
                allowClear: true,
                maximumSelectionLength: 1,
                width: 'resolve' // need to override the changed default
            });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

@section('main')
<br><br>
<div class="col-md-12" >
  <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href={{url('/home')}}>Home</a></li>
      <li class="breadcrumb-item"><a href={{ url('/lesson/manage_v2') }}>Class</a></li>
      <li class="breadcrumb-item active" aria-current="page">Students</li>
    </ol>
  </nav>
</div>

<div class="row mt--2 border-primary col-md-12">
    <div class="col-md-12 " >
        <div class="col-md-4 mt-3 mb-2">
            <div class="col-md-12 mt-2 mb-2" >
                <button class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#exampleModal"
                        data-bs-whatever="@mdo">
                    Add
                </button>
            </div>
            <!-- Modal -->
            <form method="POST" action="{{ url('/add-new-student/'. $lessonId) }}">
                {{-- cek Token CSRF --}}
                @csrf
                <div class="modal fade" id="exampleModal"
                     tabindex="-1" aria-labelledby="exampleModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title" id="exampleModalLabel">
                                    <b>Manage User</b>
                                </h1>
                                <button type="button" class="close"
                                        data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body center" style="justify-content: center">
                                <div class="mb-3">
                                    <!-- Hidden Input -->
                                    <input type="hidden" id="hiddenField" name="lessonID" value='{{ $lessonId }}'>

                                    <label for="" class="mb-2">Department<span style="color: red">*</span></label>
                                    <div class="mb-3">
                                        <div class="input-group mb-3">
                                            <select required class="form-control" name="name_of_department" id="department_id">
                                                <option value="">Select an Option</option> <!-- Opsional, jika Anda ingin memberikan opsi default -->
                                                @foreach($uniqueDepartments as $item)
                                                    <option value="{{ $item }}" {{ old('department_id') == $item ? 'selected' : '' }}>{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="mb-2">Student<span style="color: red">*</span></label>
                                    <div class="input-group mb-3">
                                        <select class="form-control" name="student_id" id="student_id" style="width: 100%;"  multiple required></select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger"
                                        data-bs-dismiss="modal">Cancel
                                </button>
                                <button type="submit" class="btn "
                                        style="background-color: #208DBB">
                                        <span style="color: white">Submit</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- DROPDOWN FILTER --}}
    <div class="col-md-12">
        <div class="col-md-4 mt-3 mb-5" >
            <div class="col-md-12 mt-3 mb-5">
                <p>Sort by:</p>
                <form id="sortForm" method="GET" action='{{ url("/class/students/$lessonId") }}' enctype="multipart/form-data">
                    <input name="lessonId" hidden type="text" value="{{ $lessonId }}">
                    <select name="sortBy" class="form-select form-control" id="sortSelect">
                        <option value="asc" {{ $sortBy == 'asc' ? 'selected' : '' }}>A to Z</option>
                        <option value="desc" {{ $sortBy == 'desc' ? 'selected' : '' }}>Z to A</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="row mt--2 border-primary col-md-12">
  <div class="col-md-12 " >
    <div class="col-md-12" >
        <div class="col-md-12 mt-3 mb-5">
            <div class="mb-3"  style="display: flex; align-items: center;">
            <h2 style="margin: 0; margin-right: 10px;">
                <b>Pelajar</b>
            </h2>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" ></th>
                        <th scope="col" >Name</th>
                        <th scope="col" class="text-center" style="width: 30%">Division</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="studentsTable">
                @forelse($studentsInLesson as $key => $student)
                    <tr>
                        <th scope="row" >
                            <div class="avatar-sm">
                                <img
                                    src="{{ Storage::url('public/profile/') . $student->profile_url }}"
                                    alt="..."
                                    class="avatar-img rounded-circle"
                                    onerror="this.onerror=null; this.src='{{ url('/default/default_profile.png') }}'; this.alt='Alternative Image';"
                                >
                            </div>
                        </th>
                        <td style="width: 500px; overflow: hidden; white-space: nowrap;">
                            {{ $student->name }}
                        </td>
                        <td style="overflow: hidden; white-space: nowrap;">
                            {{ $student->department }}
                        </td>
                        <td>
                            <form  method="POST" action="{{ route('student.delete', ['id' => $student->id, 'lessonId' => $lessonId]) }}">
                                @csrf
                                @method('DELETE')

                                <button onclick="return confirm('Are you sure?')"
                                        class="btn"
                                        style="background-color: #FC1E01;
                                                border-radius: 15px;
                                                width:45px;
                                                height: 40px;
                                                position: relative;
                                                padding: 0;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;">
                                            <img src="{{ url('/icons/Delete.svg') }}" style="max-width: 100%; max-height: 100%;">
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <div class="alert alert-danger">
                        Kelas Ini Belum Memiliki Peserta
                    </div>
                  @endforelse
                </tbody>
            </table>


        </div>
        <div>
            <p class="pull-end">
                {{ $studentsInLesson->links() }}
            </p>
            <p class="pull-left">
                Showing
                {{ $studentsInLesson->firstItem() }}
                to
                {{ $studentsInLesson->lastItem() }}
                of
                {{ $studentsInLesson->total() }}
                entries
            </p>
        </div>



        @if (session()->has('success'))
            <script>
                toastr.success('{{ session('success') }}', '{{ Session::get('success') }}');
            </script>
        @elseif(session()->has('error'))
            <script>
                toastr.error('{{ session('error') }}', '{{ Session::get('error') }}');
            </script>
        @endif
    </div>
</div>
@endsection
