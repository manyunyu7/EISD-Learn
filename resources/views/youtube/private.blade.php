<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Private YouTube Video</title>
</head>

<body>
    <h1>Private YouTube Video Details</h1>

    <div>
        <h2>{{ $videoDetails['title'] }}</h2>
        <p>{{ $videoDetails['description'] }}</p>
        <p>Published At: {{ $videoDetails['publishedAt'] }}</p>
        <p>Channel Title: {{ $videoDetails['channelTitle'] }}</p>
        <p>Channel ID: {{ $videoDetails['channelId'] }}</p>

        <!-- Display thumbnails -->
        <h3>Thumbnails:</h3>
        <img src="{{ $videoDetails['thumbnails']['default']['url'] }}" alt="Default Thumbnail">
        <img src="{{ $videoDetails['thumbnails']['medium']['url'] }}" alt="Medium Thumbnail">
        <img src="{{ $videoDetails['thumbnails']['high']['url'] }}" alt="High Thumbnail">
        <!-- You can include other thumbnails as needed -->

        <!-- Add any additional information you want to display about the video -->

        <!-- Embed the video using the YouTube iframe API -->
        <div id="player"></div>

        <script>
            var player;

            function onYouTubeIframeAPIReady() {
                player = new YT.Player('player', {
                    height: '360',
                    width: '640',
                    videoId: '{{ $videoId }}', // Replace with the video ID
                    playerVars: {
                        'playsinline': 1,
                        'origin': window.location.origin, // Set the origin to match the current domain
                        'authuser': 0 // Set authuser to 0 to ensure the viewer is not signed in
                    }
                });
            }
        </script>
        <script src="https://www.youtube.com/iframe_api"></script>
    </div>
</body>

</html>