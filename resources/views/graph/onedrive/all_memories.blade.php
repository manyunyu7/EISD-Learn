<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OneDrive Folders</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h1 class="mb-4">OneDrive Folders</h1>

        @if (count($items) == 0)
            <p>No items found.</p>
        @else
            <table id="foldersTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Size (KB)</th>
                        <th>Size (MB)</th>
                        <th>Size (GB)</th>
                        <th>Link</th>
                        <th>Created By</th>
                        <th>Created On</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr>
                        <td>
                            {{ isset($item['name']) ? $item['name'] : 'N/A' }}
                        </td>
                        <td>
                            {{ isset($item['size']) ? number_format($item['size'], 2) : 'N/A' }}
                        </td>
                        <td>
                            {{ isset($item['size']) ? number_format($item['size'] / 1024, 2) : 'N/A' }}
                        </td>
                        <td>
                            {{ isset($item['size']) ? number_format($item['size'] / (1024 * 1024), 4) : 'N/A' }}
                        </td>
                        <td>
                            @if (isset($item['parentReference']['driveId']) && isset($item['id']))
                                <a href="{{ url('365/all-memories') . '?' . http_build_query(['driveId' => $item['parentReference']['driveId'], 'itemId' => $item['id']]) }}" class="btn btn-sm btn-info">View</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            {{ isset($item['createdBy']['user']['displayName']) ? $item['createdBy']['user']['displayName'] : 'N/A' }}
                            ({{ isset($item['createdBy']['user']['email']) ? $item['createdBy']['user']['email'] : 'N/A' }})
                        </td>
                        <td>
                            {{ isset($item['createdDateTime']) ? \Carbon\Carbon::parse($item['createdDateTime'])->format('Y-m-d H:i:s') : 'N/A' }}
                        </td>
                        <td>
                            @if (isset($item['file']['mimeType']))
                                File (MIME: {{ $item['file']['mimeType'] }})
                            @elseif (isset($item['folder']))
                                Folder
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if (isset($item['@microsoft.graph.downloadUrl']))
                                <a href="{{ $item['@microsoft.graph.downloadUrl'] }}" target="_blank" class="btn btn-sm btn-success">Download</a>
                                <div class="image-preview">
                                    <!-- Try to load the image using the URL, with error handling -->
                                    <img src="{{ $item['@microsoft.graph.downloadUrl'] }}" onerror="this.style.display='none';" alt="Preview" style="max-width: 100px; max-height: 100px; margin-top: 10px;" />
                                </div>
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- <div class="mt-3">
                @if ($prevPageUrl)
                    <a href="{{ $prevPageUrl }}" class="btn btn-secondary">Previous</a>
                @endif
                @if ($nextPageUrl)
                    <a href="{{ $nextPageUrl }}" class="btn btn-primary">Next</a>
                @endif
            </div> --}}
        @endif
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#foldersTable').DataTable();
        });
    </script>

</body>

</html>
