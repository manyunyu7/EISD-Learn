@extends('main.template')


@section('head-section')
    @include('main.home._styling_home_student')

@endsection


@section('script')
    {{-- @include('main.home.script_student') --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
       
@endsection

@section('main')
<br><br>
    <div class="col-md-12" >
      <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href={{url('/home')}}>Home</a></li>
          <li class="breadcrumb-item"><a href={{ url('/class-list') }}>Class List</a></li>
          <li class="breadcrumb-item active" aria-current="page">Detail Class</li>
        </ol>
      </nav>
    </div>

    <div class="col-md-12" >
        <div class="row mt--2 border-primary col-md-10">
            {{-- DROPDOWN FILTER --}}
            <div class="row page-inner col-md-8">
                <div class="col-sm-3 col-md-5 col-lg-2 mb-3" >
                    <p>Sort by:</p>
                    <div class="btn-group">
                        <button type="button" class="btn btnSort-custom" style="padding-right: 150px; width: 200px" id="sortBtn"><span>Latest</span></button>
                        <button type="button" class="btn btnSort-custom dropdown-toggle dropdown-toggle-split" id="sortDropdownToggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
                            <span class="visually-hidden"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="sortDropdownToggle" style="width: 100%;" id="sortDropdown">
                            <li><a class="dropdown-item text-left" href="#" onclick="changeSortText('Latest')">Latest</a></li>
                            <li><a class="dropdown-item text-left" href="#" onclick="changeSortText('Recommend')">Recommend</a></li>
                            <li><a class="dropdown-item text-left" href="#" onclick="changeSortText('Most Student')">Most Student</a></li>
                        </ul>
                    </div>
                
                    <script>
                        function changeSortText(selectedTextSort) {
                            document.getElementById('sortBtn').innerHTML = '<span>' + selectedTextSort + '</span>';
                        }
                    </script>
                </div>

                <div class="col-sm-6 col-md-5 col-lg-8" >
                    
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
                        <b>Students</b>
                    </h2>
                </div>
                
                <div class="table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" style="min-width: 50px; max-width: 50px;">No</th>
                                <th scope="col" ></th>
                                <th scope="col" >Students Name</th>
                                <th scope="col" style="width:100%">Department</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($studentsInLesson as $student)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
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
                                <td class="avatar-container" style="width: 400px">
                                    {{ $student->name }}
                                </td>
                                <td>{{ $student->department }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                
                
            </div>

              <!-- JavaScript for dynamic badge colors -->
              <script>
                var badges = document.querySelectorAll('.dynamic-badge');
            
                badges.forEach(function (badge) {
                    var selectedCategory = badge.textContent;
                    var badgeColor, textColor;
            
                    switch (selectedCategory) {
                        case 'Management Trainee':
                            badgeColor = '#f7c8ca';
                            textColor = '#D02025';
                            break;
                        case 'General':
                            badgeColor = 'blue';
                            break;
                        case 'Design':
                            badgeColor = 'green';
                            break;
                        case 'Finance & Accounting':
                            badgeColor = 'purple';
                            break;
                        case 'Human Resource and Development':
                            badgeColor = 'orange';
                            break;
                        case '3D Modelling':
                            badgeColor = 'pink';
                            break;
                        case 'Digital Management':
                            badgeColor = '#EBEBFF';
                            textColor = '#342F98';
                            break;
                        case 'Marketing and Business':
                            badgeColor = 'yellow';
                            break;
                        case 'Food and Beverage':
                            badgeColor = 'brown';
                            break;
                        case 'Management':
                            badgeColor = 'teal';
                            break;
                        case 'Social and Politics':
                            badgeColor = 'indigo';
                            break;
                        case 'Office':
                            badgeColor = 'maroon';
                            break;
                        case 'Outdoor Activity':
                            badgeColor = 'lime';
                            break;
                        case 'Junior High School':
                            badgeColor = 'navy';
                            break;
                        case 'Senior High School':
                            badgeColor = 'olive';
                            break;
            
                        default:
                            badgeColor = 'gray';
                    }
            
                    badge.style.backgroundColor = badgeColor;
                    badge.style.color = textColor; // Set text color to white
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


{{-- <div class="page-inner mt--4" >
  <div class="row mt--2 border-primary col-md-11" style="background-color: yellow">
      <div class="col-md-12">
        <h1><b>{{ $data->course_title }}</b></h1>
        <p>
          <span class="badge dynamic-badge" style=" border-radius: 0; font-size: 16px; font-weight: bold">{{ $data->course_category }}</span>
        </p>

        <img style="width: 12%; height: auto;" src="{{ url('/HomeIcons/Toga_MDLNTraining.svg') }}">
        <p>Modernland Training</p>
      </div>
      <script>
        var badges = document.querySelectorAll('.dynamic-badge');
    
        badges.forEach(function (badge) {
            var selectedCategory = badge.textContent;
            var badgeColor, textColor;
    
            switch (selectedCategory) {
                case 'Management Trainee':
                    badgeColor = '#f7c8ca';
                    textColor = '#D02025';
                    break;
                case 'General':
                    badgeColor = 'blue';
                    break;
                case 'Design':
                    badgeColor = 'green';
                    break;
                case 'Finance & Accounting':
                    badgeColor = 'purple';
                    break;
                case 'Human Resource and Development':
                    badgeColor = 'orange';
                    break;
                case '3D Modelling':
                    badgeColor = 'pink';
                    break;
                case 'Digital Management':
                    badgeColor = '#EBEBFF';
                    textColor = '#342F98';
                    break;
                case 'Marketing and Business':
                    badgeColor = 'yellow';
                    break;
                case 'Food and Beverage':
                    badgeColor = 'brown';
                    break;
                case 'Management':
                    badgeColor = 'teal';
                    break;
                case 'Social and Politics':
                    badgeColor = 'indigo';
                    break;
                case 'Office':
                    badgeColor = 'maroon';
                    break;
                case 'Outdoor Activity':
                    badgeColor = 'lime';
                    break;
                case 'Junior High School':
                    badgeColor = 'navy';
                    break;
                case 'Senior High School':
                    badgeColor = 'olive';
                    break;
    
                default:
                    badgeColor = 'gray';
            }
    
            badge.style.backgroundColor = badgeColor;
            badge.style.color = textColor; // Set text color to white
        });
      </script>
      @if (session()->has('success'))
          <script>
              toastr.success('{{ session('
                  success ') }}',
                  ' {{ Session::get('success') }}');

          </script>
      @elseif(session()-> has('error'))
          <script>
              toastr.error('{{ session('
                  error ') }}', ' {{ Session::get('error') }}');
          </script>

      @endif
  </div>
</div> --}}