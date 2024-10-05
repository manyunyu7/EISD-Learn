<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OneDrive Folders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        .folder {
            padding: 15px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 5px;
            background: #fafafa;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .folder i {
            margin-right: 10px;
            color: #0078d4; /* OneDrive color */
        }
        .folder-name {
            flex-grow: 1;
        }
        .folder-link {
            text-decoration: none;
            color: #0078d4;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My OneDrive Folders</h1>

        @if(isset($folders) && count($folders) > 0)
            @foreach($folders as $folder)
                <div class="folder">
                    <i class="fas fa-folder"></i>
                    <span class="folder-name">{{ $folder['name'] }}</span>
                    <!-- Check if driveId and itemId are set -->
                    @if(isset($folder['driveId']) && isset($folder['id']))
                        <!-- Corrected URL structure -->
                        <a href="{{request()->root()}}/365/all-memories?driveId={{ $folder['driveId'] }}&itemId={{ $folder['id'] }}" class="folder-link" target="_blank">Open</a>
                    @else
                        <span class="folder-link" style="color: grey;">No link available</span>
                    @endif
                </div>
            @endforeach
        @else
            <p>No folders found.</p>
        @endif
    </div>
</body>
</html>
