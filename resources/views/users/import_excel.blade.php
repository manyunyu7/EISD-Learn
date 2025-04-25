@extends('main.template')

@section('main')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">Import Data User via Excel</div>
        <div class="card-body">
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
