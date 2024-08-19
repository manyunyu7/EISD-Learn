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

    <div class="page-inner" style="background-color: white">

        <div class="container-fluid row">

            <div class="col-md-12">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href={{url('/home')}}>Home</a></li>
                        <li class="breadcrumb-item"><a href={{ url('/lesson/manage_v2') }}>Class</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Class</li>
                    </ol>
                </nav>
            </div>

            <div class="row col-md-12 ">

                <div class="col-md-12">

                    <div class="col-md-12">


                    </div>

                    <div class="col-12" style="display: flex; justify-content: space-between; width: 100vw!important;">
                        <div><h1><b>{{ $data->course_title }}</b></h1></div>
                        <div style="padding: 10px; display: flex; align-items: center;">
                            <a href="{{ $preview_url }}">
                                <button type="button" class="btn"
                                        style="padding: 10px;
                            background-color: #208DBB;
                            color: white;
                            border-radius: 10px !important;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            width: auto;
                            max-width: 200px;">
                                <span
                                    style="font-weight: bold;
                                            font-size: 18px;">
                                    Preview Class
                                </span>
                                </button>
                            </a>
                        </div>
                    </div>


                    <div class="col-12" style="display: flex; justify-content: space-between; width: 100vw!important;">
                        <div><span class="badge dynamic-badge"
                                   style="border-radius: 0; font-size: 16px; font-weight: bold;">{{ $data->course_category }}</span>
                        </div>
                        <div style="padding: 10px; display: flex; align-items: center;">
                            <img style="max-width: 24px; max-height: 24px; margin-right: 12px;"
                                 src="{{ url('/home_icons/Toga_MDLNTraining.svg') }}" alt="Clock Icon">
                            <p style="font-size: 14px; margin: 0;">Modernland Training</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="row mt-2 col-md-12">

            <div class="col-md-12">

                <div class="row mb-5">

                    <div class="col-md-12 mt-3">
                        <div style="text-align: center;">
                            <a href="javascript:void();" data-switch="0">
                                <img
                                    style="height: auto!important; max-height: 80vh; object-fit: cover; width: 100vw!important;
                                    max-width: 70vw!important; display: inline-block; border-radius: 20px;"
                                    class="card-img-top"
                                    onerror="this.onerror=null; this.src='{{ url('/default/default_courses.jpeg') }}'; this.alt='Alternative Image';"
                                    src="{{ env('AWS_BASE_URL') . $data->course_cover_image }}" alt="La Noyee">
                            </a>
                        </div>


                        <h2 class="mt-3"><b>Deskripsi</b></h2>
                        <p style="font-size: 16px;">{!! $data->course_description !!}</p>

                        <div style="display: flex; align-items: center;">
                            <h2 class="mt--2">
                                <b>Silabus</b>
                            </h2>


                            <div class="mb-3" style="margin-left: auto; margin-right: 5px;">
                                <div style="padding: 10px; display: flex; align-items: center;">
                                    <img style="max-width: 24px; max-height: 24px; margin-right: 12px;"
                                         src="{{ url('/icons/Folder.svg') }}" alt="Folder Icon">
                                    <p style="font-size: 1rem; margin: 0;">{{ $jumlahSection }} Sections</p>
                                </div>
                            </div>

                            <div class="mb-3" style="margin-left: 5px;">
                                <div style="padding: 10px; display: flex; align-items: center;">
                                    <img style="max-width: 24px; max-height: 24px; margin-right: 12px;" src="{{ url('/icons/Clock.svg') }}" alt="Clock Icon">
                                    <p style="font-size: 1rem; margin: 0; margin-right: 6px">{{ $jumlahDuration }}m</p>
                                </div>
                            </div>
                        </div>


                        @forelse ($dayta as $data)
                            <div style="border-collapse: collapse; width: 100%;">
                                <div style="border: 1px solid #ccc; padding: 10px; display: flex; align-items: center;">
                                    <p style="margin: 0; margin-right: 10px; font-size: 16px">
                                        {{ $data->section_title }}
                                    </p>
                                    @if($data->time_limit_minute != null)
                                    <img style="max-width: 24px; max-height: 24px; margin-right: 12px; margin-left: auto;" src="{{ url('/icons/Clock.svg') }}" alt="Clock Icon">
                                    <p style="font-size: 14px; margin: 0; margin-top: -6px; margin-right: 8px; margin-top:1px">{{ $data->time_limit_minute }}m</p>
                                @endif
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-danger">
                                Kelas Ini Belum Memiliki Materi
                            </div>
                        @endforelse
                    </div>
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
