<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drives</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Optional: Custom CSS for additional styling */
        body {
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Drives in SharePoint Site</h1>
        <ul class="list-group">
            @foreach($drives['value'] as $drive)
            <li class="list-group-item">
                <a href="{{ route('folders', ['siteId' => request()->siteId, 'driveId' => $drive['id']]) }}">{{ $drive['name'] }}</a>
            </li>
            @endforeach
        </ul>
    </div>

    <!-- Bootstrap JS (optional for certain components) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>