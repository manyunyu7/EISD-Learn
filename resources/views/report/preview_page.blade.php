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
            width: auto; 
            min-width: 100%;
            border-collapse: collapse;
        }

        /* Kunci utama: mencegah teks turun ke bawah (wrap) */
        .report-table th, 
        .report-table td {
            white-space: nowrap; 
            padding: 8px 12px; /* Beri sedikit ruang agar tidak terlalu rapat */
            text-align: left;   /* Atau sesuaikan dengan kebutuhan */
        }

        /* Khusus untuk kontainer agar bisa di-scroll secara horizontal */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
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

@section('script')
    <!-- DataTables JS -->
    {{-- <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.bootstrap4.css">
    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap4.js"></script>
    
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function () {
            var table = $('#basic-datatables').DataTable({
                "lengthChange": false, 
                "pageLength": 20,
                
                // Mengatur posisi elemen menggunakan layout
                layout: {
                    topStart: 'search', // Memindahkan Search Bar ke pojok kiri atas
                    topEnd: null,       // Menghilangkan elemen di pojok kanan atas (kosong)
                    bottomStart: 'info',
                    bottomEnd: 'paging'
                },

                // Jika Anda masih menggunakan opsi 'dom' (opsional, pilih salah satu)
                // dom: '<"row"<"col-sm-12 col-md-6"f><"col-sm-12 col-md-6">>t<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',

                buttons: [
                    { extend: 'excelHtml5', title: 'Export Excel' },
                    { extend: 'pdfHtml5', title: 'Export PDF', orientation: 'landscape', pageSize: 'A2', },
                    { extend: 'csvHtml5', title: 'Export CSV' }
                ],
                "language": {
                    "search": "Search:",
                    "paginate": {
                        "previous": "<",
                        "next": ">"
                    }
                }
            });

            // Menghubungkan tombol dropdown Anda ke fungsi export DataTables
            $('#btn-excel').on('click', function() {
                table.button('.buttons-excel').trigger();
            });

            $('#btn-pdf').on('click', function() {
                table.button('.buttons-pdf').trigger();
            });

            $('#btn-csv').on('click', function() {
                table.button('.buttons-csv').trigger();
            });
        });
    </script>

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
            <a class="dropdown-item" href="javascript:void(0)" id="btn-excel">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </a>
            <a class="dropdown-item" href="javascript:void(0)" id="btn-pdf">
                <i class="fas fa-file-pdf mr-2"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="timestamp">
        {{ date('d/m/y H:i') }}
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table id="basic-datatables" class="report-table">
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
                    @php
                        // Total Peserta (Semua baris yang tampil)
                        $reports = collect($reports);

                        // Total Hadir (Filter berdasarkan kolom keterangan_hadir)
                        $totalPeserta = $reports->count();
                        // Menggunakan trim() untuk menghindari whitespace tak terlihat
                        $totalHadir = $reports->where('keterangan_hadir', 'Hadir')->count();

                        // Total Tidak Hadir
                        $totalTidakHadir = $totalPeserta - $totalHadir;

                        // --- LOGIKA GROUPING UNTUK KELAS ---
    
                        // 3. Ambil data unik berdasarkan Judul Training (Grouping)
                        $uniqueClasses = $reports->unique('course_title');
                        // Grouping berdasarkan Judul Training (Nama Kelas)
                        $groupedClasses = $reports->groupBy('course_title');

                        // 4. Hitung Online/Offline dari hasil grouping tersebut
                        $totalKelasOnline = $uniqueClasses->filter(function($item) {
                            return stripos($item->training_type, 'Online') !== false;
                        })->count();

                        $totalKelasOffline = $uniqueClasses->filter(function($item) {
                            return stripos($item->training_type, 'Offline') !== false;
                        })->count();
                    @endphp
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
                            <td>Rp. {{ number_format($row->proposed_budget, 0, ',', '.') }}</td>
                            <td>Rp. {{ number_format($row->actual_budget, 0, ',', '.') }}</td>
                            <td>{{ $row->keterangan_hadir }}</td>
                            <td>{{ $row->target_audience }}</td>
                            <td>{{ $row->actual_audience }}</td>
                            <td>{{ (int)$row->presentase_kehadiran }}%</td>
                            <td>{{ (int)$row->progress }}%</td>
                            <td>{{ $row->training_type }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="21" class="text-center">Belum memiliki materi / data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- <div class="summary-box">
        <div class="summary-item">
            <div class="summary-label">Total</div>
            <div class="summary-value">{{ count($reports) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Hadir</div>
            <div class="summary-value">{{ $totalHadir }}</div>
        </div>
    </div> --}}

    <div class="summary-container" style="margin-top: 20px; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
        <div style="background: #f8f9fa; padding: 12px 15px; border-bottom: 1px solid #ddd; font-weight: bold;">
            Ringkasan Kehadiran Per Kelas
        </div>
        
        <div class="summary-list">
            @foreach($groupedClasses as $namaKelas => $dataGrup)
                @php
                    // Menghitung jumlah baris yang statusnya 'Hadir' dalam grup ini
                    $totalHadirPerKelas = $dataGrup->where('keterangan_hadir', 'Hadir')->count();
                    $totalPesertaPerKelas = $dataGrup->count();
                @endphp
                
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 15px; border-bottom: 1px solid #eee; background: #fff;">
                    <div style="flex: 2;">
                        <span style="display: block; font-size: 0.9rem; color: #666; text-transform: uppercase;">Nama Kelas</span>
                        <strong style="font-size: 1rem; color: #333;">{{ $namaKelas }}</strong>
                    </div>
                    
                    <div style="flex: 1; text-align: right;">
                        <span style="display: block; font-size: 0.85rem; color: #666;">Total Peserta Hadir</span>
                        <span style="font-size: 1.2rem; font-weight: bold; color: #28a745;">
                            {{ $totalHadirPerKelas }} <small style="color: #999; font-size: 0.8rem;">/ {{ $totalPesertaPerKelas }}</small>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-5">
        <button class="btn btn-link text-muted" onclick="window.history.back()">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Filter
        </button>
    </div>
</div>
@endsection