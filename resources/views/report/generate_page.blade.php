@extends('main.template')

@section('head-section')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .page-inner {
            padding: 30px !important;
            background-color: #fff;
        }

        /* Breadcrumb Divider sesuai gambar */
        .breadcrumb { 
            background: transparent; 
            padding: 0; 
            margin-bottom: 40px; 
            font-size: 14px;
        }
        .breadcrumb-item + .breadcrumb-item::before { content: ">"; color: #333; padding: 0 10px; }
        .breadcrumb a { color: #333; text-decoration: none; }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: #2D3436;
            margin-bottom: 40px;
        }

        /* Form Layout */
        .form-group { margin-bottom: 25px; }
        .form-group label {
            display: block;
            font-weight: 600;
            color: #636E72;
            margin-bottom: 10px;
            font-size: 15px;
        }
        .text-danger { color: #e5533d !important; margin-left: 4px; }

        /* Input Date & Select Styling */
        .form-control-custom {
            height: 50px !important;
            border-radius: 8px !important;
            border: 1px solid #DFE6E9 !important;
            padding: 10px 15px !important;
            font-size: 15px !important;
            width: 100%;
        }

        /* Wrapper Icon di kanan input */
        .input-with-icon { position: relative; display: block; }
        .input-with-icon i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #B2BEC3;
            font-size: 18px;
            pointer-events: none;
        }

        /* Select arrow custom */
        .select-wrapper { position: relative; }
        .select-wrapper select {
            appearance: none;
            -webkit-appearance: none;
            padding-right: 40px !important;
        }
        .select-wrapper i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #2D3436;
            pointer-events: none;
        }

        /* Radio Group */
        .radio-group { display: flex; gap: 40px; margin-top: 10px; }
        .custom-radio { display: flex; align-items: center; cursor: pointer; }
        .custom-radio input {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            cursor: pointer;
            accent-color: #5b8fb9;
        }
        .custom-radio label { margin-bottom: 0; color: #636E72; font-weight: 500; cursor: pointer; }

        /* Footer Action Buttons */
        .footer-actions { 
            margin-top: 60px; 
            display: flex; 
            justify-content: flex-end; 
            gap: 15px; 
        }
        .btn-report {
            padding: 12px 45px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            border: none;
            transition: 0.3s;
            cursor: pointer;
        }
        .btn-cancel { background-color: #e5533d; color: #fff; }
        .btn-cancel:hover { background-color: #cf4b37; }
        .btn-save { background-color: #5b8fb9; color: #fff; }
        .btn-save:hover { background-color: #4a7a9e; }
    </style>
@endsection

@section('main')
<div class="page-inner">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Report</li>
        </ol>
    </nav>

    <h1 class="page-title">Generate Report</h1>

    <form action="{{ url('/report/preview') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 form-group">
                <label>Start Date <span class="text-danger">*</span></label>
                <div class="input-with-icon">
                    <input type="date" name="start_date" class="form-control-custom" required>
                    <i class="far fa-calendar-alt"></i>
                </div>
            </div>

            <div class="col-md-6 form-group">
                <label>End Date <span class="text-danger">*</span></label>
                <div class="input-with-icon">
                    <input type="date" name="end_date" class="form-control-custom" required>
                    <i class="far fa-calendar-alt"></i>
                </div>
            </div>

            <div class="col-md-6 form-group">
                <label>Jabatan</label>
                <div class="select-wrapper">
                    <select name="jabatan_id" class="form-control-custom">
                        <option value="">Pilih Jabatan</option>
                        @foreach($positions as $position)
                            <option value="{{ $position->id }}">{{ $position->name }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>

            <div class="col-md-6 form-group">
                <label>Business Unit</label>
                <div class="select-wrapper">
                    <select name="bu_id" class="form-control-custom">
                        <option value="">ALL</option>
                        @foreach($businessUnits as $bu)
                            <option value="{{ $bu->id }}">{{ $bu->name }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>

            <div class="col-md-12 form-group">
                <label>Jenis Training</label>
                <div class="radio-group">
                    <div class="custom-radio">
                        <input type="radio" name="training_type" id="all" value="All" checked>
                        <label for="all">All</label>
                    </div>
                    <div class="custom-radio">
                        <input type="radio" name="training_type" id="online" value="Online">
                        <label for="online">Online</label>
                    </div>
                    <div class="custom-radio">
                        <input type="radio" name="training_type" id="offline" value="Offline">
                        <label for="offline">Offline</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-actions">
            <button type="button" class="btn-report btn-cancel" onclick="window.location.href='{{ url('/home') }}'">Cancel</button>
            <button type="submit" class="btn-report btn-save">Save</button>
        </div>
    </form>
</div>
@endsection