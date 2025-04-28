@extends('main.template')

@section('main')
<div class="container mt-4">
    <br><br>
    <div class="card">
        <div class="card-header">Preview Data Excel</div>
        <div class="card-body">
            <form action="{{ route('users.import.excel') }}" method="POST">
                @csrf
                <input type="hidden" name="submit_type" value="process">
                <input type="hidden" name="excel_data" value="{{ base64_encode(serialize($results)) }}">
                <button type="submit" class="btn btn-primary">Proses Integrasi Sekarang</button>
            </form>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        {{-- <th>ID Learning</th> --}}
                        <th>Nama [Data Learning]</th>
                        <th>Nama [Data HR]</th>
                        <th>Is Match?</th>
                        <th>Kode Realta</th>
                        <th>Adjustment Target</th>

                        <!-- tambahkan kolom sesuai kebutuhan -->
                    </tr>
                </thead>
                <tbody>
                    @forelse ($results as $result)
                        <tr>
                            <td>{{ $result['excel_name'] }}</td>
                            <td>{{ $result['valid_name'] }}</td>
                            <td>{{ $result['match'] }}</td>
                            <td>{{ $result['realta_code'] }}</td>
                            <td>{{ $result['adj_target'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No matching data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
