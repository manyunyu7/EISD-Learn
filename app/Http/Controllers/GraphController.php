<?php

namespace App\Http\Controllers;

use App\Helper\GraphHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GraphController extends Controller
{
    private $baseUrl = 'https://graph.microsoft.com/v1.0';


     // Method to list all users
     public function listUsers()
     {
         $accessToken = GraphHelper::getAccessToken();
         $url = "{$this->baseUrl}/users";

         try {
             $response = Http::withToken($accessToken)->get($url);

             if ($response->successful()) {
                 $users = $response->json()['value'];
                 return view('graph.users', compact('users'));
             } else {
                 return back()->withErrors(['error' => 'Failed to fetch users: ' . $response->status()]);
             }
         } catch (\Exception $e) {
             return back()->withErrors(['error' => 'Failed to fetch users: ' . $e->getMessage()]);
         }
     }

    public function listOneDriveFolders()
    {
        $accessToken = GraphHelper::getAccessToken();
        $url = "{$this->baseUrl}/me/drive/root/children";

        try {
            $response = Http::withToken($accessToken)->get($url);

            if ($response->successful()) {
                $folders = $response->json()['value'];
                return view('graph.onedrive_folders', compact('folders'));
            } else {
                return response()->json(
                    [
                        'error' => 'Failed to fetch folders: ' . $response->status()
                    ]
                );
                return back()->withErrors(['error' => 'Failed to fetch folders: ' . $response->status()]);
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    'error' => 'Failed to fetch folders: ' . $e->getMessage()
                ]
            );
            return back()->withErrors(['error' => 'Failed to fetch folders: ' . $e->getMessage()]);
        }
    }


    public function listSites()
    {
        $accessToken = GraphHelper::getAccessToken();
        $url = "{$this->baseUrl}/sites";

        try {
            $response = Http::withToken($accessToken)->get($url);

            if ($response->successful()) {
                $sites = $response->json()['value'];
                return view('graph.sites', compact('sites'));
            } else {
                return back()->withErrors(['error' => 'Failed to fetch sites: ' . $response->status()]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to fetch sites: ' . $e->getMessage()]);
        }
    }

    public function showSharePoint($siteId)
    {
        $accessToken = GraphHelper::getAccessToken();
        $url = "{$this->baseUrl}/sites/{$siteId}";

        $response = Http::withToken($accessToken)->get($url);
        $site = $response->json();

        return view('graph.sharepoint', compact('site'));
    }

    public function showDrives($siteId)
    {
        $accessToken = GraphHelper::getAccessToken();
        $url = "{$this->baseUrl}/sites/{$siteId}/drives";

        $response = Http::withToken($accessToken)->get($url);
        $drives = $response->json();

        return view('graph.drives', compact('drives'));
    }

    public function readFolders($siteId, $driveId)
    {
        $accessToken = GraphHelper::getAccessToken();
        $url = "{$this->baseUrl}/sites/{$siteId}/drives/{$driveId}/root/children";

        $response = Http::withToken($accessToken)->get($url);
        $folders = $response->json();

        return view('graph.folder', compact('folders'));
    }

    public function readFiles($siteId, $driveId, $folderId)
    {
        $accessToken = GraphHelper::getAccessToken();
        $url = "{$this->baseUrl}/sites/{$siteId}/drives/{$driveId}/items/{$folderId}/children";

        $response = Http::withToken($accessToken)->get($url);
        $files = $response->json();

        return view('graph.files', compact('files'));
    }

    public function downloadFile($driveId, $fileId)
    {
        $accessToken = GraphHelper::getAccessToken();
        $url = "https://graph.microsoft.com/v1.0/drives/{$driveId}/items/{$fileId}/content";

        $response = Http::withToken($accessToken)->get($url);

        if ($response->successful()) {
            $contentType = $response->header('Content-Type');
            $contentDisposition = $response->header('Content-Disposition');
            $fileName = $this->extractFileName($contentDisposition) ?? 'downloaded_file';

            return response($response->body())
                ->header('Content-Type', $contentType)
                ->header('Content-Disposition', "attachment; filename=\"{$fileName}\"");
        }

        return back()->withErrors(['message' => 'File download failed.']);
    }

    private function extractFileName($contentDisposition)
    {
        if (preg_match('/filename="(.+?)"/', $contentDisposition, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function create(Request $request)
    {
        // Validate the request
        $request->validate([
            'folderName' => 'required|string|max:255',
        ]);

        // Assuming you have a service to handle the folder creation
        $folderName = $request->input('folderName');
        $siteId = $request->siteId;
        $driveId = $request->driveId;

        // Make API call or logic to create the folder
        $response = FolderService::createFolder($siteId, $driveId, $folderName);

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Folder created successfully.');
        } else {
            return redirect()->back()->withErrors('Failed to create folder.');
        }
    }

    public function uploadFile(Request $request, $siteId, $driveId, $folderId)
    {
        $accessToken = GraphHelper::getAccessToken();
        $file = $request->file('file');
        $url = "{$this->baseUrl}/sites/{$siteId}/drives/{$driveId}/items/{$folderId}/children/{$file->getClientOriginalName()}/content";

        $response = Http::withToken($accessToken)
            ->attach('file', file_get_contents($file), $file->getClientOriginalName())
            ->put($url);

        return redirect()->back()->with('status', 'File uploaded successfully!');
    }

    // Method to delete a file
    public function deleteFile(Request $request, $fileId)
    {
        $accessToken = GraphHelper::getAccessToken();

        // Example: Drive and Folder IDs where files are located
        $driveId = $request->input('driveId');
        $folderId = $request->input('folderId');

        // Build the endpoint URL for the delete request
        $url = "https://graph.microsoft.com/v1.0/drives/$driveId/items/$fileId";

        try {
            // Make the DELETE request using Laravel's HTTP client
            $response = Http::withToken($accessToken)->delete($url);

            // Check if the request was successful
            if ($response->successful()) {
                // File deleted successfully
                return back()->with('status', 'File deleted successfully.');
            } else {
                // Handle errors or unsuccessful response
                return back()->withErrors(['error' => 'Failed to delete file: ' . $response->status()]);
            }
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            return back()->withErrors(['error' => 'Failed to delete file: ' . $e->getMessage()]);
        }
    }
}
