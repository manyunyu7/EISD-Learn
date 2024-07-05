<!-- sharepoint.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SharePoint Site Details</title>
</head>

<body>
    <h1>SharePoint Site Details</h1>
    <h2>{{ $site['name'] }}</h2>
    <p>Web URL: <a href="{{ $site['webUrl'] }}" target="_blank">{{ $site['webUrl'] }}</a></p>
    <p>Description: {{ $site['description'] }}</p>

    <h2>Drives in this SharePoint Site</h2>
    <ul>
        @foreach($drives['value'] as $drive)
        <li><a href="{{ route('drives', ['siteId' => $site['id'], 'driveId' => $drive['id']]) }}">{{ $drive['name'] }}</a></li>
        @endforeach
    </ul>
</body>

</html>