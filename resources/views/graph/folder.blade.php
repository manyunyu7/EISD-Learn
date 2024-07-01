<!DOCTYPE html>
<html>

<head>
    <title>Folders</title>
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1 class="my-4">Folders</h1>

        <div class="row">
            <div class="col-md-6">
                <ul class="list-group">
                    @foreach($folders['value'] as $folder)
                    <li class="list-group-item">
                        <a href="{{ route('files', ['siteId' => request()->siteId, 'driveId' => request()->driveId, 'folderId' => $folder['id']]) }}">
                            {{ $folder['name'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Folder Details</h5>
                        <p class="card-text">Additional information about folders can go here.</p>
                        <a href="#" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <footer class="text-center">
            <p>&copy; 2024 Your Company. All rights reserved.</p>
        </footer>
    </div>

    <!-- Optional: Include Bootstrap JS if needed -->
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
</body>

</html>