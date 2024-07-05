<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SharePoint Sites</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Optional: Custom styles */
        body {
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>SharePoint Sites</h1>

        <!-- Site List -->
        <ul class="list-group">
            @foreach($sites as $site)
            <li class="list-group-item">
                <a href="{{ route('drives', ['siteId' => $site['id']]) }}">{{ $site['name'] }}</a>
            </li>
            @endforeach
        </ul>

        <!-- Error Handling -->
        @if($errors->any())
        <div class="alert alert-danger mt-3" role="alert">
            {{ $errors->first() }}
        </div>
        @endif
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>