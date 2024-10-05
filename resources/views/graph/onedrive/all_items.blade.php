<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OneDrive Items</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">OneDrive Items</h1>

    <!-- Breadcrumb to navigate -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('onedrive.list', ['userId' => $userId, 'folderId' => 'root']) }}">Root</a></li>
            <!-- Add more breadcrumb items dynamically as you navigate deeper into folders -->
        </ol>
    </nav>

    <!-- Flash Messages -->
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

    <!-- Items Table -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>
                            @if(isset($item['folder']))
                                <!-- It's a folder, so create a link to view the folder's contents -->
                                <a href="{{ route('onedrive.list', ['userId' => $userId, 'folderId' => $item['id']]) }}">
                                    📁 {{ $item['name'] }}
                                </a>
                            @else
                                <!-- It's a file -->
                                📄 {{ $item['name'] }}
                            @endif
                        </td>
                        <td>{{ isset($item['folder']) ? 'Folder' : 'File' }}</td>
                        <td>{{ isset($item['size']) ? $item['size'] : 'N/A' }}</td>
                        <td>
                            @if(!isset($item['folder']))
                                <!-- Actions for files -->
                                <a href="{{ route('onedrive.download', ['fileId' => $item['id']]) }}" class="btn btn-primary btn-sm">Download</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
