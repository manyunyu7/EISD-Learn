<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Google\Client;
use Google\Service\YouTube;

class YoutubeVideoController extends Controller
{

    /**
     * Fetch private video.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showPrivate(Request $request)
    {
        // Construct the request URL
        $videoId = 'm2h5xGgZF24';
        $url = 'https://www.googleapis.com/youtube/v3/videos';
        $params = [
            'id' => $videoId,
            'part' => 'snippet'
        ];

        // Set the Authorization header with the access token
        $accessToken = 'ya29.a0AXooCgteOHxiVKeKY6KbGRdgJDuzaitTZJEiX347WhMqFjxiO_4B_gopiBX7gzA3UReOP_DXSbVJX-3tbzL42nzp1T652AJEvTEYK2GYmAtuQ8fmga9XrEDClMbWIARU5RvpEW6zs8XAu9ZBPqowkm_p3OIab5Ry3Y2GaCgYKAR8SARASFQHGX2Mi_IV0n5IOUPxHWMdVlLIcYA0171'; // Replace 'YOUR_ACCESS_TOKEN' with your actual access token
        $headers = [
            'Authorization' => 'Bearer ' . $accessToken
        ];

        // Make the request to fetch the private video details
        $response = Http::withHeaders($headers)->get($url, $params);

        // Process the response
        if ($response->successful()) {
            $videoDetails = $response->json()['items'][0]['snippet'];
            // Pass the video details and access token to the view
            return view('youtube.private', ['videoDetails' => $videoDetails, 'accessToken' => $accessToken, 'videoId' => $videoId]);
        } else {
            // Handle the error
            return response()->json(['error' => 'Failed to fetch video details'], $response->status());
        }
    }

    public function indexYoutube(Request $request)
    {
        #config
        $apiKey = env('YOUTUBE_API_KEY');

        # Initialize Youtube API Client
        $client = new Client();
        $client->setDeveloperKey($apiKey);
        $service = new Youtube($client);


        # Example query just to make sure we can connect to the API
        $response = $service->videos->listVideos('snippet', ['id' => 'm2h5xGgZF24']);


        # Narrow down to the single video within the response
        $video = $response->items[0]->snippet;
        #Output the response to confirm its work!

        return $video;
        return view('youtube.single')->with(compact('video'));
        dump($response);
    }

    public function getChannelInfo()
    {
        $API_key = env('YOUTUBE_API_KEY');
        $accessToken = 'ya29.a0AXooCgteOHxiVKeKY6KbGRdgJDuzaitTZJEiX347WhMqFjxiO_4B_gopiBX7gzA3UReOP_DXSbVJX-3tbzL42nzp1T652AJEvTEYK2GYmAtuQ8fmga9XrEDClMbWIARU5RvpEW6zs8XAu9ZBPqowkm_p3OIab5Ry3Y2GaCgYKAR8SARASFQHGX2Mi_IV0n5IOUPxHWMdVlLIcYA0171'; // Replace 'YOUR_ACCESS_TOKEN' with your actual access token
        $url = 'https://youtube.googleapis.com/youtube/v3/channels?part=snippet%2CcontentDetails%2Cstatistics&mine=true&key=' . $API_key;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Accept' => 'application/json',
        ])->get($url);

        return $response->json();
    }

    public function index()
    {
        $API_key = env('YOUTUBE_API_KEY');
        $channelID = "UCktAyUaGsZ4lJL1qHDkzRRw";
        $maxResults = 10;

        $response = Http::get("https://www.googleapis.com/youtube/v3/search", [
            'order' => 'date',
            'part' => 'snippet',
            'channelId' => $channelID,
            'maxResults' => $maxResults,
            'key' => $API_key,
        ]);

        $videoList = $response->json();

        return view('youtube.index', compact('videoList'));
    }
}
