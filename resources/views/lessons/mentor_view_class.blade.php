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
    <div class="page-inner" style="background-color: white !important">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/lesson/manage_v2') }}">Class</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Class</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="mb-0"><b>{{ $data->course_title }}</b></h1>
                    <span class="badge dynamic-badge mt-2" style="font-size: 14px;">{{ $data->course_category }}</span>
                </div>
                <a href="{{ $preview_url }}" class="btn btn-primary btn-round" style="background-color: #208DBB; border: none; padding: 10px 25px;">
                    <b>Preview Class</b>
                </a>
            </div>

            <div class="text-center mb-5">
                <img src="{{ env('AWS_BASE_URL') . $data->course_cover_image }}" 
                     onerror="this.onerror=null; this.src='{{ url('/default/default_courses.jpeg') }}';"
                     class="img-fluid rounded-lg" 
                     style="width: 100%; max-height: 500px; object-fit: cover; border-radius: 15px;">
            </div>

            <div class="row">
                <div class="col-md-12">
                    
                    <div class="mb-5">
                        <h2 class="mb-3"><b>Deskripsi</b></h2>
                        <div style="font-size: 16px; color: #555; line-height: 1.6;">
                            {!! $data->course_description !!}
                        </div>
                    </div>

                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-end mb-3">
                            <h2 class="mb-0"><b>Learning Path</b></h2>
                            <span class="text-muted"><i class="fas fa-folder-open mr-1"></i> {{ $jumlahSection }} Sections</span>
                        </div>
                        <div class="list-group">
                            @forelse ($dayta as $item)
                                <div class="list-group-item d-flex justify-content-between align-items-center" style="border-left: 4px solid #208DBB;">
                                    <span style="font-size: 16px;">{{ $item->section_title }}</span>
                                    @if($item->time_limit_minute)
                                        <small class="text-muted"><i class="far fa-clock mr-1"></i> {{ $item->time_limit_minute }}m</small>
                                    @endif
                                </div>
                            @empty
                                <div class="alert alert-light border">Belum memiliki materi.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="mb-5">
                        <h2 class="mb-3"><b>Class Information</b></h2>
                        <div class="card border shadow-sm" style="border-radius: 12px;">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start mb-4">
                                            <div class="mr-3 mt-1">
                                                <i class="far fa-calendar-alt" style="font-size: 24px; color: #555;"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0" style="font-size: 16px; font-weight: bold; color: #333;">Tanggal Pelaksanaan</p>
                                                <p class="mb-0 text-muted" style="font-size: 15px;">
                                                    {{ \Carbon\Carbon::parse($data->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($data->end_date)->format('d/m/Y') }}
                                                </p>
                                            </div>
                                        </div>
                    
                                        <div class="d-flex align-items-start mb-4">
                                            <div class="mr-3 mt-1">
                                                <i class="fas fa-map-marker-alt" style="font-size: 24px; color: #555;"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0" style="font-size: 16px; font-weight: bold; color: #333;">Lokasi Pelatihan</p>
                                                <p class="mb-0 text-muted" style="font-size: 15px;">
                                                    {{ $data->training_type }} {{ $data->location != '-' ? '('.$data->location.')' : '' }}
                                                </p>
                                            </div>
                                        </div>
                    
                                        <div class="d-flex align-items-start">
                                            <div class="mr-3 mt-1">
                                                <i class="far fa-clock" style="font-size: 24px; color: #555;"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0" style="font-size: 16px; font-weight: bold; color: #333;">Durasi Pelatihan</p>
                                                <p class="mb-0 text-muted" style="font-size: 15px;">{{ $data->duration }}</p>
                                            </div>
                                        </div>
                                    </div>
                    
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start mb-4">
                                            <div class="mr-3 mt-1">
                                                <i class="fas fa-file-invoice-dollar" style="font-size: 24px; color: #555;"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0" style="font-size: 16px; font-weight: bold; color: #333;">Budget Diajukan</p>
                                                <p class="mb-0 text-muted" style="font-size: 15px;">Rp {{ number_format($data->proposed_budget, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                    
                                        <div class="d-flex align-items-start mb-4">
                                            <div class="mr-3 mt-1">
                                                <i class="fas fa-coins" style="font-size: 24px; color: #555;"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0" style="font-size: 16px; font-weight: bold; color: #333;">Budget Realisasi</p>
                                                <p class="mb-0 text-muted" style="font-size: 15px;">
                                                    {{ $data->actual_budget > 0 ? 'Rp '.number_format($data->actual_budget, 0, ',', '.') : 'Rp -' }}
                                                </p>
                                            </div>
                                        </div>
                    
                                        <div class="d-flex align-items-start">
                                            <div class="mr-3 mt-1">
                                                <i class="fas fa-users" style="font-size: 24px; color: #555;"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0" style="font-size: 16px; font-weight: bold; color: #333;">Target Peserta</p>
                                                <p class="mb-0 text-muted" style="font-size: 15px;">{{ $data->target_audience }} Peserta</p>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
