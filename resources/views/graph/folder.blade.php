<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folders</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6dd5ed 0%, #2193b0 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            margin: 0;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            max-width: 900px;
            width: 100%;
            color: #fff;
        }

        h1 {
            font-weight: 300;
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.5rem;
        }

        .folder-list {
            max-height: 300px;
            overflow-y: auto;
            padding-right: 15px;
        }

        .list-group-item {
            background-color: rgba(255, 255, 255, 0.2);
            border: none;
            color: #fff;
            margin-bottom: 10px;
            transition: background-color 0.2s;
        }

        .list-group-item:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .list-group-item a {
            color: #fff;
            text-decoration: none;
        }

        footer {
            margin-top: 30px;
            color: #fff;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Folders</h1>

        <div class="row">
            <div class="col-md-6">
                <ul class="list-group folder-list">
                    @foreach($folders['value'] as $folder)
                    <li class="list-group-item">
                        <a href="{{ route('files', ['siteId' => request()->siteId, 'driveId' => request()->driveId, 'folderId' => $folder['id']]) }}">
                            <i class="fas fa-folder"></i> {{ $folder['name'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createFolderModal">
                        <i class="fas fa-plus"></i> Create New Folder
                    </button>
                </div>
            </div>
        </div>

        <!-- Create Folder Modal -->
        <div class="modal fade" id="createFolderModal" tabindex="-1" aria-labelledby="createFolderModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createFolderModalLabel">Create New Folder</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('folders.create') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="folderName">Folder Name</label>
                                <input type="text" class="form-control" id="folderName" name="folderName" required>
                            </div>
                            <!-- Include hidden fields to pass siteId, driveId, and folderId -->
                            <input type="hidden" name="siteId" value="{{ request()->siteId }}">
                            <input type="hidden" name="driveId" value="{{ request()->driveId }}">
                            <input type="hidden" name="folderId" value="{{ request()->folderId }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Create Folder</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <footer class="text-center">
            <p>&copy; 2024 Your Company. All rights reserved.</p>
        </footer>
    </div>

    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
