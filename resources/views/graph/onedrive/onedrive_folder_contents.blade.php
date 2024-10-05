<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folder Contents</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Optional CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .item {
            padding: 15px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 5px;
            background: #fafafa;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .item-name {
            flex-grow: 1;
        }
        .item-link {
            text-decoration: none;
            color: #0078d4;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Folder Contents</h1>

        @if(isset($items) && count($items) > 0)
            @foreach($items as $item)
                <div class="item">
                    <span class="item-name">{{ $item['name'] }}</span>
                    <a href="{{ $item['webUrl'] }}" class="item-link" target="_blank">Open</a>
                </div>
            @endforeach
        @else
            <p>No items found in this folder.</p>
        @endif
        <a href="{{ route('onedrive.folders') }}" class="item-link">Back to Folders</a>
    </div>
</body>
</html>
