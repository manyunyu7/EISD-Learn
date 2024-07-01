<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Files</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Optional: Custom styles */
        body {
            padding: 20px;
        }

        .file-list {
            margin-bottom: 20px;
        }

        .upload-form {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Files</h1>

        <!-- File List -->
        <div class="file-list">
            <ul class="list-group">
                @foreach($files['value'] as $file)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ $file['webUrl'] }}" target="_blank">{{ $file['name'] }}</a>
                    <form action="{{ route('delete-file-graph', ['fileId' => $file['id']]) }}" method="post" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="driveId" value="{{ request()->driveId }}">
                        <input type="hidden" name="folderId" value="{{ request()->folderId }}">
                        <button type="submit" class="btn btn-danger btn-sm ml-2">Delete</button>
                    </form>
                </li>
                @endforeach
            </ul>
        </div>

        <!-- Upload Form -->
        <div class="upload-form">
            <form action="{{ route('upload', ['siteId' => request()->siteId, 'driveId' => request()->driveId, 'folderId' => request()->folderId]) }}" method="post" enctype="multipart/form-data" class="form-inline">
                @csrf
                <div class="form-group mb-2">
                    <input type="file" name="file" class="form-control-file">
                </div>
                <button type="submit" class="btn btn-primary mb-2 ml-2">Upload</button>
            </form>
        </div>

        <!-- Status Message -->
        @if(session('status'))
        <div class="alert alert-success mt-3" role="alert">
            {{ session('status') }}
        </div>
        @endif
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>