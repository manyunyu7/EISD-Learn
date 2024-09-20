<!-- resources/views/graph/onedrive_folders.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>OneDrive Folders</h1>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <ul class="list-group">
        @foreach ($folders as $folder)
            <li class="list-group-item">
                <strong>{{ $folder['name'] }}</strong> - ID: {{ $folder['id'] }}
            </li>
        @endforeach
    </ul>
</div>
@endsection
