@extends('main.template')

@section('main')
<div class="container mt-4"">
    <br><br>
    <div class="card">
        <div class="card-header">Import Data User via Excel</div>
        <div class="card-body">
            {{-- AWAL: Kode untuk menampilkan alert --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Jika Anda juga ingin menangani pesan error dari redirect (misalnya dari validasi atau error proses) --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Menampilkan error validasi standar dari Laravel --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error Validasi:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            {{-- AKHIR: Kode untuk menampilkan alert --}}


            <form action="{{ route('users.import.excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="excel_file" class="form-label">Pilih File Excel</label>
                    <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls">
                    <input type="hidden" name="submit_type" value="preview">
                </div>
                <button type="submit" class="btn btn-primary">Upload & Preview</button>
            </form>
        </div>
    </div>
</div>
@endsection
