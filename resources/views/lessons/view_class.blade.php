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
    

    <div class="row mt--2 border-primary col-md-12">
      <!-- Yellow Container -->
      <div class="col-md-10 " >
          <div class="col-md-12">
            <h1><b>{{ $data->course_title }}</b></h1>
            <div class="row mb-5">
                <div class="border-primary" style="width:100%; display: flex; align-items: center; flex-wrap: wrap;">

                    <!-- Kategori -->
                    <p class="col-md-6" style="margin: 0; margin-right: 10px;">
                      <span class="badge dynamic-badge" style="border-radius: 0; font-size: 16px; font-weight: bold;">{{ $data->course_category }}</span>
                    </p>
                  
                    <!-- Mentor Label -->
                    <div id="mentorLabel" class="mt--2 col-md-6" style="width: 100%; display: flex; align-items: center; 
                      @media (max-width: 767px) { order: 2; }">
                      
                      <div style="padding: 10px; display: flex; align-items: center;">
                        <img style="max-width: 24px; max-height: 24px; margin-right: 12px; margin-left: auto; margin-top: 12px;" 
                          src="{{ url('/HomeIcons/Toga_MDLNTraining.svg') }}" alt="Clock Icon">
                        <p style="font-size: 14px; margin: 0; margin-top: -6px; margin-right: 8px; margin-top: 12px;">Modernland Training</p>
                    </div>
                    </div>
                </div>
                  
                  
                  
                <div class="col-md-12 mt-3">
                  <a href="javascript:void();" data-switch="0">
                    <img class="card-img-top" onerror="this.onerror=null; this.src='{{ url('/default/default_courses.jpeg') }}'; this.alt='Alternative Image';"
                            src="{{ Storage::url('public/class/cover/') . $data->course_cover_image }}"
                            alt="La Noyee">
                  </a>


                  <h2 class="mt-3"><b>Deskripsi</b></h2>
                  <p style="font-size: 16px;">{!! $data->course_description !!}</p>

                  <div  style="display: flex; align-items: center;">
                    <h2 style="margin: 0; margin-right: 10px;">
                        <b>Silabus</b>
                    </h2>
                    

                    <div class="mb-3" style="width: 630%;">
                        <div style="padding: 10px; display: flex; align-items: center;">
                            <img style="max-width: 24px; max-height: 24px; margin-right: 12px; margin-left: auto;margin-top:12px" src="{{ url('/Icons/Folder.svg') }}" alt="Clock Icon">
                            <p style="font-size: 14px; margin: 0; margin-top: -6px; margin-right: 8px; margin-top:12px">{{ $jumlahSection }} Sections</p>
                        </div>
                    </div>
                    <div class="mb-3" style="width: 100%;">
                        <div style="padding: 10px; display: flex; align-items: center;">
                            <img style="max-width: 24px; max-height: 24px; margin-right: 12px; margin-left: auto;margin-top:12px" src="{{ url('/Icons/Clock.svg') }}" alt="Clock Icon">
                            <p style="font-size: 14px; margin: 0; margin-top: -6px; margin-right: 8px; margin-top:12px">30m</p>
                        </div>
                    </div>
                  
                </div>
                  
                  {{-- margin-left: 858px; --}}
                  @forelse ($dayta as $data)
                  <div style="border-collapse: collapse; width: 100%;">
                    <div style="border: 1px solid #ccc; padding: 10px; display: flex; align-items: center;">
                      <p style="margin: 0; margin-right: 10px; font-size: 16px">
                        {{ $data->section_title }}
                      </p>
                      <img style="max-width: 24px; max-height: 24px; margin-right: 12px; margin-left: auto;" src="{{ url('/Icons/Clock.svg') }}" alt="Clock Icon">
                      <p style="font-size: 14px; margin: 0; margin-top: -6px; margin-right: 8px; margin-top:1px">30m</p>
                    </div>
                  </div>
                  @empty
                    <div class="alert alert-danger">
                        Kelas Ini Belum Memiliki Materi
                    </div>
                  @endforelse
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

      <!-- Second Container -->
      <div class="col-md-2"> <!-- Adjust the background color as needed -->
        <button type="button" 
                class="btn" 
                style="width: 80%; 
                margin: 10px;
                background-color: #208DBB;
                color: white;
                border-radius: 10px !important;">
            <span 
                style="font-weight: bold;
                font-size: 18px;">
                    Join Class
            </span>
        </button>
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