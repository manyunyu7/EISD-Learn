<!DOCTYPE html>
<html lang="en">

<head>
    <title>Videos from YouTube Channel </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style type="text/css">
        .container {
            padding: 15px;
        }

        .youtube-video h2 {
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <h1>Example: Get details for a single video</h1>

            <h2>Title</h2>
            {{ $video->title }}
            <h2>Thumbnail</h2>
            <img src='{{ $video->thumbnails->high->url }}' style='width:150px' alt='Thumbnail for the video {{ $video->title }}'>

            <h2>Description</h2>
            <textarea style='width:700px; height:200px'>{{ $video->description }}</textarea>
        </div>
    </div>
</body>

</html>