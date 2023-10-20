<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class MobileUploaderController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the uploaded files
//        $request->validate([
//            'files.*' => 'required|file|mimes:jpeg,png,pdf,doc,docx',
//        ]);

        // Get the uploaded files
        $files = $request->file('files');

        // Move each file to the public/IsengIsengAja directory
        $uploadedFiles = [];
        foreach ($files as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/IsengIsengAja', $filename);
            $uploadedFiles[] = $filename;
        }

        // Prepare data for Guzzle
        $data = [
            'site_id' => '7b357c45-ec72-4c14-8695-3b0b207bf13d',
            'sub_location_site_id' => '5073c341-ad63-47d2-b0ad-e3a3c9521174',
            'type_id' => '49d83fa9-d7d5-4ff5-9755-7bd3de364d9e',
            'description' => '168',
        ];

        // Send files using Guzzle
        $client = new Client();

        try {
            $response = $client->post('https://api-ithub.modernland.co.id/api/v1/helpdesk', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer U2FsdGVkX1/tuR/8hFUwcNH5mw0LFO7QLIMntNUxIAenpzYCbcO+IhmxmFk5SlDv+5JXSGdL7TAe3IqkILliC/w0RheTb4OmZL+rOSxd0QiNBkn8JFmIEHqoeiiJ2vprMr3d59NllB2Nw/RD+ZkJpETkZ3S30oyni749rnxQNQrSPhfH9LRSQiaPFJTRiPhb0EvkfZz/gTLSxOMvdtHC6qAmA3NVGHS/NFaaFd/BWC024RyfrpLW1FZGVNc4xuYFGNBW0/vOVZQs79hvIvOWgp7097+CQjjzxOGOBAOt0SuZcUdwsrAVWjf4yYtS3qkD',
                ],
                'multipart' => array_merge(
                    $this->buildMultipartData($data),
                    $this->buildFileData($uploadedFiles)
                ),
            ]);

            $response = $response->getBody()->getContents();

            // You can handle the response here

            return response()->json(['message' => 'Files uploaded successfully', 'files' => $uploadedFiles]);
        } catch (\Exception $e) {
            // Handle errors here
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function buildMultipartData(array $data)
    {
        $multipartData = [];
        foreach ($data as $key => $value) {
            $multipartData[] = ['name' => $key, 'contents' => $value];
        }
        return $multipartData;
    }

    private function buildFileData(array $files)
    {
        $fileData = [];
        foreach ($files as $uploadedFile) {
            $fileData[] = [
                'name' => 'files',
                'contents' => file_get_contents(storage_path("app/public/IsengIsengAja/{$uploadedFile}")),
                'filename' => $uploadedFile,
            ];
        }
        return $fileData;
    }
}
