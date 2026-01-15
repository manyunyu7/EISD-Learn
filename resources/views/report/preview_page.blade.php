@extends('main.template')

@section('head-section')
    <style>
        .page-inner {
            padding: 30px !important;
            background-color: #fff;
            min-height: 100vh;
        }

        /* Breadcrumb Divider */
        .breadcrumb { 
            background: #fff; 
            border: 1px solid #ebedf2;
            border-radius: 10px;
            padding: 15px 25px; 
            margin-bottom: 40px; 
            font-size: 14px;
        }
        .breadcrumb-item + .breadcrumb-item::before { content: ">"; color: #333; padding: 0 10px; }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: #000;
            margin-bottom: 20px;
        }

        /* Tombol Export Biru sesuai gambar */
        .btn-export-main {
            background-color: #5b8fb9;
            color: white;
            border: none;
            padding: 10px 35px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .timestamp {
            color: #333;
            font-size: 14px;
            margin-bottom: 10px;
        }

        /* Border Hitam Tebal di atas dan bawah tabel sesuai gambar */
        .table-container {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 40px 0;
            margin-bottom: 30px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-table th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: 700;
            text-align: left;
            padding: 12px 10px;
            font-size: 13px;
            border: 1px solid #dee2e6;
        }

        .report-table td {
            padding: 12px 10px;
            border: 1px solid #dee2e6;
            font-size: 13px;
            color: #333;
        }

        /* Styling Ringkasan Total di Bawah */
        .summary-box {
            display: flex;
            gap: 20px;
            justify-content: flex-start;
        }

        .summary-item {
            display: flex;
            border: 1px solid #ebedf2;
            border-radius: 5px;
            overflow: hidden;
            min-width: 150px;
        }

        .summary-label {
            background-color: #f8f9fa;
            padding: 10px 20px;
            font-weight: 500;
            border-right: 1px solid #ebedf2;
            flex: 1;
            text-align: center;
        }

        .summary-value {
            padding: 10px 25px;
            background-color: #fff;
            flex: 1;
            text-align: center;
            font-weight: 700;
        }
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

    <div class="dropdown">
        <button class="btn-export-main dropdown-toggle" type="button" data-toggle="dropdown">
            Export
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="#"><i class="fas fa-file-excel mr-2"></i> Export Excel</a>
            <a class="dropdown-item" href="#"><i class="fas fa-file-pdf mr-2"></i> Export PDF</a>
        </div>
    </div>

    <div class="timestamp">
        {{ date('d/m/y H:i') }}
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pelaksanaan</th>
                        <th>Nama</th>
                        <th>Nomor Karyawan</th>
                        <th>Jenis Kelamin</th>
                        <th>Posisi / Job Title</th>
                        <th>Departement</th>
                        <th>Jabatan</th>
                        <th>Unit Bisnis</th>
                        <th>Judul Training</th>
                        <th>Pelaksana/Vendor</th>
                        <th>Lokasi Pelatihan</th>
                        <th>Durasi (Menit)</th>
                        <th>Budget (Total Diajukan)</th>
                        <th>Budget (Total Realisasi)</th>
                        <th>Keterangan Ketidakhadiran</th>
                        <th>Target Peserta</th>
                        <th>Aktual Peserta</th>
                        <th>Persentase Kehadiran</th>
                        <th>Progress</th>
                        <th>Jenis Pelatihan</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalHadir = 0; @endphp
                    @forelse($reports as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $row->start_date }}</td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->nip }}</td>
                            <td>{{ $row->gender }}</td>
                            <td>{{ $row->position }}</td>
                            <td>{{ $row->department }}</td>
                            <td>{{ $row->jabatan }}</td>
                            <td>{{ $row->business_unit }}</td>
                            <td>{{ $row->course_title }}</td>
                            <td>{{ $row->vendor }}</td>
                            <td>{{ $row->location }}</td>
                            <td>{{ $row->duration }}</td>
                            <td>{{ $row->proposed_budget }}</td>
                            <td>{{ $row->actual_budget }}</td>
                            <td>{{ $row->keterangan_hadir }}</td>
                            <td>{{ $row->target_audience }}</td>
                            <td>{{ $row->actual_audience }}</td>
                            <td>{{ $row->presentase_kehadiran }}</td>
                            <td>undefined</td>
                            <td>{{ $row->training_type }}</td>
                        </tr>
                        @php $totalHadir++; @endphp
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum memiliki materi / data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="summary-box">
        <div class="summary-item">
            <div class="summary-label">Total</div>
            <div class="summary-value">{{ count($reports) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Hadir</div>
            <div class="summary-value">{{ $totalHadir }}</div>
        </div>
    </div>

    <div class="mt-5">
        <button class="btn btn-link text-muted" onclick="window.history.back()">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Filter
        </button>
    </div>
</div>
@endsection