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

        $url = "";

        if($request->dev=="true"){
            $url = "https://github.modernland.co.id/api/v1/helpdesk";
        }else{
            $url = "https://api-ithub.modernland.co.id/api/v1/helpdesk";
        }

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
            'site_id' => $request->site_id,
            'sub_location_site_id' => $request->sub_location_site_id,
            'type_id' => $request->type_id,
            'description' => $request->description,
        ];
        // Send files using Guzzle
        $client = new Client();
        $bearer = $request->bearer;
        try {
            $response = $client->post($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer $bearer",
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

    public function closeTicket(Request $request)
    {
        // Validate the uploaded files
//        $request->validate([
//            'files.*' => 'required|file|mimes:jpeg,png,pdf,doc,docx',
//        ]);

        $url = "";
        $ticketId = $request->ticketId;
        if($request->dev=="true"){
            $url = "https://github.modernland.co.id/api/v1/helpdesk/request-close-ticket/$ticketId";
        }else{
            $url = "https://api-ithub.modernland.co.id/api/v1/helpdesk/request-close-ticket/$ticketId";
        }

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
            'message' => $request->message,
        ];
        // Send files using Guzzle
        $client = new Client();
        $bearer = $request->bearer;
        try {
            $response = $client->post($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer $bearer",
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
