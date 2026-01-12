@extends('main.template')

@section('head-section')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .page-inner { padding: 30px !important; background-color: #fff; }
        .page-title { font-size: 32px; font-weight: 700; color: #2D3436; margin-bottom: 40px; }
    
        /* Form Layout */
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; font-weight: 600; color: #636E72; margin-bottom: 10px; font-size: 15px; }
    
        /* Input & Select Styling */
        .form-control-custom {
            height: 50px !important;
            border-radius: 8px !important;
            border: 1px solid #DFE6E9 !important;
            padding: 10px 45px 10px 15px !important; /* Ruang kanan untuk icon */
            font-size: 15px !important;
            width: 100%;
            background-color: transparent !important; /* Wajib transparan agar icon terlihat */
            position: relative;
            z-index: 2; /* Di atas icon agar tetap bisa di-klik */
        }
    
        /* Khusus untuk Date Picker asli browser agar tetap bisa dipicu */
        input[type="date"]::-webkit-calendar-picker-indicator {
            background: transparent;
            bottom: 0;
            color: transparent;
            cursor: pointer;
            height: auto;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            width: auto;
        }
    
        /* Wrapper Icon */
        .input-with-icon, .select-wrapper { 
            position: relative; 
            display: block; 
            background-color: #fff; /* Latar belakang dipindah ke wrapper */
            border-radius: 8px;
        }
    
        /* Ikon Kalender & Arrow */
        .input-with-icon i, .select-wrapper i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #B2BEC3;
            font-size: 18px;
            z-index: 1; /* Di bawah input/select */
            pointer-events: none;
        }
    
        /* Reset Select Appearance */
        .select-wrapper select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            cursor: pointer;
        }
    
        /* Radio Group */
        .radio-group { display: flex; gap: 40px; margin-top: 10px; }
        .custom-radio { display: flex; align-items: center; cursor: pointer; }
        .custom-radio input { width: 18px; height: 18px; margin-right: 10px; accent-color: #5b8fb9; }
    
        /* Buttons */
        .footer-actions { margin-top: 60px; display: flex; justify-content: flex-end; gap: 15px; }
        .btn-report { padding: 12px 45px; border-radius: 10px; font-weight: 600; border: none; cursor: pointer; }
        .btn-cancel { background-color: #e5533d; color: #fff; }
        .btn-save { background-color: #5b8fb9; color: #fff; }
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

        <form id="reportForm" action="{{ url('/report/preview') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Start Date <span class="text-danger">*</span></label>
                    <div class="input-with-icon">
                        <input type="date" value="2023-08-11" name="start_date" class="form-control-custom" required>
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>

                <div class="col-md-6 form-group">
                    <label>End Date <span class="text-danger">*</span></label>
                    <div class="input-with-icon">
                        <input type="date" value="2026-01-05" name="end_date" class="form-control-custom" required>
                        <i class="fas fa-calendar-alt"></i>
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
                <button type="button" id="btnPreview" class="btn-report btn-save">Save</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 jika masih diperlukan
            $('.select2-basic').select2({
                theme: "bootstrap",
                width: '100%'
            });

            $('#btnPreview').on('click', function() {
                // Ambil semua data form
                const formData = {
                    start_date: $('input[name="start_date"]').val(),
                    end_date: $('input[name="end_date"]').val(),
                    jabatan_id: $('select[name="jabatan_id"]').val(),
                    bu_id: $('select[name="bu_id"]').val(),
                    training_type: $('input[name="training_type"]:checked').val()
                };

                // Tampilkan di console
                console.log("=== Generate Report Input Data ===");
                console.table(formData); // Menggunakan table agar lebih rapi di console

                // Validasi sederhana sebelum submit
                if(!formData.start_date || !formData.end_date) {
                    Swal.fire('Error', 'Start Date dan End Date wajib diisi!', 'error');
                    return;
                }

                // Jika ingin lanjut ke Controller (Preview Page)
                // Hapus baris di bawah ini jika hanya ingin ngetes di console saja
                $('#reportForm').submit();
            });
        });
    </script>
@endsection