@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mt-4">Upload New File</h1>
    <form action="{{ route('filemanager.s3.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="file">File:</label>
            <input type="file" class="form-control-file" name="file" id="file" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <input type="text" class="form-control" name="description" id="description">
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>
@endsection
