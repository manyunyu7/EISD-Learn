@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mt-4">S3 File Manager</h1>
    <a href="{{ route('filemanager.s3.create') }}" class="btn btn-primary mb-3">Upload New File</a>
    <ul class="list-group">
        @foreach ($files as $file)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ Storage::disk('s3')->url($file->filename) }}">{{ $file->filename }}</a>
                <form action="{{ route('filemanager.s3.destroy', $file->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
</div>
@endsection
