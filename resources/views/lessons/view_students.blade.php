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
@endsection

@section('main')
<div class="container">
    
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
                <button class="btn btn-primary">Add</button>
            </div>
        </div>
      </div>
  {{-- DROPDOWN FILTER --}}
  <div class="col-md-12 " >
    <div class="col-md-4 mt-3 mb-5">
        <div class="col-md-12 mt-3 mb-5" >
            <p>Sort by:</p>
            <form method="POST" action='{{ url("class/class-list/students/$lessonId") }}' enctype="multipart/form-data">
                @csrf
                <input name="lessonId" hidden type="text" value="{{ $lessonId }}">
                <select name="sortBy" class="form-select form-control "  id="sortSelect">
                    <option 
                        @if ($sortBy == 'asc') selected @endif
                    value="asc" {{ old('sortBy') == 'asc' ? 'selected' : '' }}>A to Z</option>
                    <option @if ($sortBy == 'desc') selected @endif
                    value="desc" {{ old('sortBy') == 'desc' ? 'selected' : '' }}>Z to A</option>
                </select>
                <button  type="submit" class="btn btn-primary mt-3 pull-right">
                    Search
                </button>
            </form>
        </div>
    </div>
  </div>
</div>  



<div class="row mt--2 border-primary col-md-12">
  <!-- Yellow Container -->
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
                        {{-- <th scope="col" style="min-width: 50px; max-width: 50px;">No</th> --}}
                        <th scope="col" ></th>
                        <th scope="col" >Name</th>
                        <th scope="col" class="text-center" style="width: 30%">Division</th>
                        <th class="text-center">Button</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($studentsInLesson as $key => $student)
                    <tr>
                        {{-- <th scope="row">{{ $studentsInLesson->firstItem()+ $key }}</th> --}}
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
                            <button onclick="return confirm('Are you sure?')" class="btn" style="background-color: #FC1E01; border-radius: 15px; width:100%; height: 40px; position: relative; padding: 0;">
                                <img src="{{ url('/Icons/Delete.svg') }}" style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); height: 20px;">
                            </button>
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


        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const studentsInLesson = @json($studentsInLesson);
        
                // Function to display data for a specific page
                function displayData(pageNumber, pageSize) {
                    const startIndex = (pageNumber - 1) * pageSize;
                    const endIndex = startIndex + pageSize;
        
                    // Add logic for sorting and filtering based on user input
                    const sortedAndFilteredData = applySortingAndFiltering(studentsInLesson);
        
                    const currentPageData = sortedAndFilteredData.slice(startIndex, endIndex);
        
                    // Clear previous data
                    document.getElementById('data-container').innerHTML = '';
        
                    // Display current page data
                    currentPageData.forEach((student, index) => {
                        const row = `<tr>
                                        <th scope="row">${startIndex + index + 1}</th>
                                        <th scope="row">
                                            <div class="avatar-sm">
                                                <img 
                                                    src="${student.profile_url}" 
                                                    alt="..." 
                                                    class="avatar-img rounded-circle" 
                                                    onerror="this.onerror=null; this.src='${url('/default/default_profile.png')}'; this.alt='Alternative Image';"
                                                >
                                            </div>
                                        </th>
                                        <td style="width: 500px">${student.name}</td>
                                        <td>${student.department}</td>
                                    </tr>`;
                        document.getElementById('data-container').innerHTML += row;
                    });
                }
        
                // Function to apply sorting and filtering
                function applySortingAndFiltering(data) {
                    // Get user-selected sorting criteria
                    const sortCriteria = document.getElementById('sortBtn').textContent.trim();
        
                    // Get user-input for filtering
                    const filterInput = document.getElementById('filterInput').value.toLowerCase();
        
                    // Add logic for sorting
                    // Example: A to Z
                    if (sortCriteria === 'A to Z') {
                        data.sort((a, b) => a.name.localeCompare(b.name));
                    } else if (sortCriteria === 'Z to A') {
                        data.sort((a, b) => b.name.localeCompare(a.name));
                    }
        
                    // Add logic for filtering
                    // Example: Filter by name
                    const filteredData = data.filter(student => student.name.toLowerCase().includes(filterInput));
        
                    return filteredData;
                }
        
                // ... (kode JavaScript pagination dan sort sebelumnya) ...
        
                // Initial display (default pageSize is 10)
                displayData(1, 10);
                generatePaginationLinks(10);
            });
        </script>
        


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
</div>
@endsection
