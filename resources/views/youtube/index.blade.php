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
            @foreach($videoList['items'] as $item)
            @if(isset($item['id']['videoId']))
            <div id="{{ $item['id']['videoId'] }}" class="col-lg-3 col-sm-6 col-xs-6 youtube-video">
                <iframe width="280" height="150" src="https://www.youtube.com/embed/{{ $item['id']['videoId'] }}" frameborder="0" allowfullscreen></iframe>
                <h2>{{ $item['snippet']['title'] }}</h2>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</body>

</html>