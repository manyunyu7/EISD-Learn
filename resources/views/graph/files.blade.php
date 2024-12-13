<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Files</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f9fc;
            padding: 20px;
        }

        .file-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin: 10px;
            transition: 0.3s;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 200px;
            overflow: hidden;
        }

        .file-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .file-icon {
            font-size: 50px;
            color: #007bff;
            margin-bottom: 10px;
        }

        .file-name {
            display: block;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
            max-width: 100%;
        }

        .search-bar {
            margin-bottom: 20px;
        }

        .upload-form {
            margin-top: 20px;
        }

        .no-files {
            text-align: center;
            color: #888;
            margin-top: 20px;
        }

        .custom-modal .modal-content {
            background-color: #f8f9fa;
            border-radius: 10px;
        }

        .modal-title {
            color: #343a40;
        }

        .loading-spinner {
            display: none;
            margin-top: 10px;
        }

        /* Grid layout */
        .file-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        /* List layout */
        .file-list.list-view {
            flex-direction: column;
            align-items: flex-start;
        }

        .file-list.list-view .file-card {
            width: 100%; /* Full width in list view */
            margin-bottom: 10px;
        }

        .view-options {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mb-4">File Management</h1>

        <!-- View Options -->
        <div class="view-options">
            <button class="btn btn-light" id="gridViewBtn"><i class="fas fa-th"></i> Grid View</button>
            <button class="btn btn-light" id="listViewBtn"><i class="fas fa-list"></i> List View</button>
            <button class="btn btn-light" id="compactViewBtn"><i class="fas fa-th-list"></i> Compact View</button>
        </div>

        <!-- Search Bar -->
        <div class="search-bar input-group mb-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Search for files...">
            <select class="custom-select" id="filterType">
                <option value="">All Types</option>
                <option value="document">Documents</option>
                <option value="image">Images</option>
                <option value="pdf">PDFs</option>
                <option value="spreadsheet">Spreadsheets</option>
            </select>
        </div>

        <!-- File List -->
        <div class="file-list" id="fileList">
            @foreach($files['value'] as $file)
                <div class="file-card" data-name="{{ $file['name'] }}" data-type="{{ strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) }}">
                    <i class="file-icon fas fa-file"></i>
                    <a href="#" class="download-link file-name" data-file-id="{{ $file['id'] }}" data-drive-id="{{ request()->driveId }}">{{ $file['name'] }}</a>
                    <form action="{{ route('delete-file-graph', ['fileId' => $file['id']]) }}" method="post" class="delete-form mt-2">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="driveId" value="{{ request()->driveId }}">
                        <input type="hidden" name="folderId" value="{{ request()->folderId }}">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            @endforeach
            <div id="noFilesMessage" class="no-files d-none">No files found</div>
        </div>

        <!-- Upload Form -->
        <div class="upload-form">
            <form id="uploadForm" action="{{ route('upload', ['siteId' => request()->siteId, 'driveId' => request()->driveId, 'folderId' => request()->folderId]) }}" method="post" enctype="multipart/form-data" class="form-inline">
                @csrf
                <div class="form-group mb-2">
                    <input type="file" name="file" class="form-control-file" required>
                </div>
                <button type="submit" class="btn btn-primary mb-2 ml-2">Upload</button>
                <div class="loading-spinner spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </form>
        </div>

        <!-- Status Message -->
        @if(session('status'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('status') }}
            </div>
        @endif
    </div>

    <!-- Modal for Upload Confirmation -->
    <div class="modal fade" id="uploadConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="uploadConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadConfirmationModalLabel">Confirm Upload</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to upload this file?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmUpload">Upload</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Download Progress -->
    <div class="modal fade custom-modal" id="downloadModal" tabindex="-1" role="dialog" aria-labelledby="downloadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h5 class="modal-title">Preparing your download...</h5>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p id="progressText" class="mt-2">0% complete</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            // Default to grid view
            setView('grid');

            // Show upload confirmation dialog
            $('#uploadForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                $('#uploadConfirmationModal').modal('show');
            });

            // Confirm upload and show loading spinner
            $('#confirmUpload').on('click', function() {
                $('.loading-spinner').show(); // Show loading spinner
                $('#uploadForm')[0].submit(); // Submit the form
            });

            // Filter files based on search input
            $('#searchInput').on('keyup', function() {
                var searchQuery = $(this).val().toLowerCase();
                filterFiles(searchQuery, $('#filterType').val());
            });

            // Filter files based on type filter
            $('#filterType').on('change', function() {
                var filterType = $(this).val();
                filterFiles($('#searchInput').val().toLowerCase(), filterType);
            });

            // Function to filter files
            function filterFiles(searchQuery, filterType) {
                let noFiles = true;
                $('.file-card').each(function() {
                    var fileName = $(this).data('name').toLowerCase();
                    var fileType = $(this).data('type');

                    if (fileName.includes(searchQuery) && (filterType === '' || filterType === fileType)) {
                        $(this).show();
                        noFiles = false;
                    } else {
                        $(this).hide();
                    }
                });
                $('#noFilesMessage').toggle(noFiles);
            }

            // Change view to grid
            $('#gridViewBtn').on('click', function() {
                setView('grid');
            });

            // Change view to list
            $('#listViewBtn').on('click', function() {
                setView('list');
            });

            // Change view to compact
            $('#compactViewBtn').on('click', function() {
                setView('compact');
            });

            // Set view function
            function setView(view) {
                if (view === 'grid') {
                    $('#fileList').removeClass('list-view').addClass('grid-view');
                    $('.file-card').css('width', '200px'); // Set width for grid
                } else if (view === 'list') {
                    $('#fileList').removeClass('grid-view').addClass('list-view');
                    $('.file-card').css('width', '100%'); // Full width for list view
                } else if (view === 'compact') {
                    $('#fileList').removeClass('list-view').addClass('grid-view');
                    $('.file-card').css('width', '150px'); // Smaller width for compact view
                }
            }

            // Download progress logic here
            $('.download-link').on('click', function(e) {
                e.preventDefault();
                $('#downloadModal').modal('show');
                var fileId = $(this).data('file-id');
                var driveId = $(this).data('drive-id');
                var xhr = new XMLHttpRequest();
                xhr.open('GET', '{{ url("/download-file") }}/' + driveId + '/' + fileId, true);
                xhr.responseType = 'blob';
                xhr.onprogress = function(event) {
                    if (event.lengthComputable) {
                        var percentComplete = Math.round((event.loaded / event.total) * 100);
                        $('.progress-bar').css('width', percentComplete + '%');
                        $('#progressText').text(percentComplete + '% complete');
                    }
                };
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var disposition = xhr.getResponseHeader('Content-Disposition');
                        var fileName = "downloaded_file";
                        if (disposition && disposition.indexOf('attachment') !== -1) {
                            var matches = /filename="([^"]*)"/.exec(disposition);
                            if (matches != null && matches[1]) fileName = matches[1];
                        }
                        var blob = new Blob([xhr.response], { type: xhr.getResponseHeader('Content-Type') });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = fileName;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        $('#downloadModal').modal('hide');
                    } else {
                        $('#downloadModal').modal('hide');
                        alert('File download failed!');
                    }
                };
                xhr.send();
            });
        });
    </script>
</body>

</html>
