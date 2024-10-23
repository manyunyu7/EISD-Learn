<?php

namespace App\Http\Controllers;

use App\Helper\GraphHelper;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GraphController extends Controller
{
    private $baseUrl = 'https://graph.microsoft.com/v1.0';


    public function searchDocuments(Request $request)
    {
        $query = $request->input('query');
        $location = $request->input('location');
        $accessToken = GraphHelper::getAccessToken();

        // Define the body for search without specific resource to search the entire drive or SharePoint
        $body = [
            'requests' => [
                [
                    'entityTypes' => [
                        "driveItem",
                        "listItem",
                        "list"
                    ], // Search in files (driveItems)
                    'query' => [
                        'queryString' => $query,
                    ],
                    'from' => 0, // Starting point
                    'size' => 500,  // Limit the number of results
                    'region' => 'JPN' // Specify your region here
                ]
            ]
        ];

        // Call Microsoft Graph API using Http Client
        $response = Http::withToken($accessToken)
            ->post('https://graph.microsoft.com/v1.0/search/query', $body);

        if ($response->successful()) {
            $results = $response->json();

            // Check if there are any results
            if (isset($results['value'])) {
                // Sort results by last modified date (newest first)
                usort($results['value'], function ($a, $b) {
                    // Assuming that the hitsContainers contain the required lastModifiedDateTime
                    $lastModifiedA = new DateTime($a['hitsContainers'][0]['hits'][0]['resource']['lastModifiedDateTime']);
                    $lastModifiedB = new DateTime($b['hitsContainers'][0]['hits'][0]['resource']['lastModifiedDateTime']);
                    return $lastModifiedB <=> $lastModifiedA; // Sort in descending order
                });
            }

            return response()->json($results);
        }

        return response()->json(['error' => 'Unable to fetch search results.', 'details' => $response->json()], 500);
    }

    public function readInbox(Request $request)
    {
        // Step 1: Retrieve the access token from the session
        $accessToken = session('microsoft_access_token');

        // Check if the access token is present
        if (!$accessToken) {
            return response()->json(['error' => 'Access token not found.'], 401);
        }

        // Step 2: Make a request to the Microsoft Graph API to get messages from the inbox
        $response = Http::withToken($accessToken)
            ->get('https://graph.microsoft.com/v1.0/me/mailFolders/inbox/messages');

        // Step 3: Check if the request was successful
        if ($response->successful()) {
            // Return the inbox messages as JSON
            return response()->json($response->json(), 200);
        } else {
            // Log the error details for better debugging
            \Log::error('Outlook API Error:', [
                'status' => $response->status(),
                'body' => $response->body(), // Log the entire response body
                'error' => $response->json('error', 'Unknown error'),
                'error_description' => $response->json('error_description', 'No description available')
            ]);

            // Return a more detailed error message
            return response()->json([
                'error' => 'Could not retrieve inbox messages.',
                'details' => [
                    'status' => $response->status(),
                    'body' => $response->body(), // Include body for debugging
                    'error' => $response->json('error', 'Unknown error'),
                    'error_description' => $response->json('error_description', 'No description available')
                ]
            ], 500);
        }
    }


    public function ourDrive(Request $request)
    {
        // Step 1: Retrieve the access token from the session
        $accessToken = session('microsoft_access_token');

        // Check if the access token is present
        if (!$accessToken) {
            return response()->json(['error' => 'Access token not found.'], 401);
        }

        // Step 2: Make a request to the Microsoft Graph API to get OneDrive items
        $response = Http::withToken($accessToken)
            ->get('https://graph.microsoft.com/v1.0/me/drive/root/children');

        // Step 3: Check if the request was successful
        // Step 3: Check if the request was successful
        if ($response->successful()) {
            // Return the OneDrive items as JSON
            return response()->json($response->json(), 200);
        } else {
            // Log the full response for debugging
            \Log::error('OneDrive API Error:', [
                'status' => $response->status(),
                'body' => $response->body(), // Log the body of the response
                'error' => $response->json('error', 'Unknown error'),
                'error_description' => $response->json('error_description', 'No description available')
            ]);

            // Return detailed error message if request fails
            return response()->json([
                'error' => 'Could not retrieve OneDrive items.',
                'details' => [
                    'status' => $response->status(),
                    'error' => $response->json('error', 'Unknown error'),
                    'error_description' => $response->json('error_description', 'No description available')
                ]
            ], 500);
        }
    }


    public function listAllMemories(Request $request)
    {
        $driveId = $request->input('driveId');
        $itemId = $request->input('itemId');
        $accessToken = GraphHelper::getAccessToken();

        // Construct the base URL to fetch items
        $baseUrl = "https://graph.microsoft.com/v1.0/drives/$driveId/items/$itemId/children";

        try {
            // Send the request to fetch items
            $response = Http::withToken($accessToken)->get($baseUrl . '?$top=3000');

            // Log the full API response for debugging
            Log::info('API Response:', $response->json());

            if ($response->successful()) {
                $items = $response->json()['value'];

                // Check if galleryMode=true in the request
                $galleryMode = $request->query('galleryMode') === 'true'; // Make sure to define the variable

                // Fetch thumbnails for each item if in gallery mode
                if ($galleryMode) {
                    foreach ($items as &$item) {
                        // Fetch thumbnail URL using Graph API if it's an image
                        if (isset($item['id'])) {
                            $thumbnailUrl = $this->fetchThumbnailUrl($accessToken, $item['id']);
                            $item['thumbnailUrl'] = $thumbnailUrl; // Store the thumbnail URL
                        }
                    }
                }

                // Return view with items and galleryMode flag
                return view('graph.onedrive.all_memories', compact('items', 'galleryMode'));
            } else {
                // Log any errors from the API response
                $errorResponse = $response->json();
                $errorMessage = isset($errorResponse['error']['message']) ? $errorResponse['error']['message'] : 'Unknown error';
                Log::error('Failed to fetch items from OneDrive: ' . $errorMessage);
                return response()->json(['error' => 'Failed to fetch items: ' . $errorMessage]);
            }
        } catch (\Exception $e) {
            // Handle exceptions during the process
            Log::error('Exception occurred while fetching items: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch items: ' . $e->getMessage()]);
        }
    }

    private function fetchThumbnailUrl($accessToken, $itemId)
    {
        // Fetch the thumbnail for the item using Graph API
        $thumbnailUrl = "https://graph.microsoft.com/v1.0/me/drive/items/$itemId/thumbnails";

        // Send the request to get the thumbnail
        $response = Http::withToken($accessToken)->get($thumbnailUrl);

        if ($response->successful()) {
            $thumbnails = $response->json()['value'];
            // Check if any thumbnails exist
            if (!empty($thumbnails)) {
                return $thumbnails[0]['url']; // Return the first thumbnail URL
            }
        }
        return null; // Return null if no thumbnail found
    }


    public function listOneDriveFolders(Request $request)
    {
        $accessToken = GraphHelper::getAccessToken();
        $userId = "4191b5ef-561e-4fd5-b168-f90881c16e4e";

        if ($request->userId != null) {
            $userId = $request->userId;
        }

        // id jem a715a682-7ec4-4cf5-80e8-81a27cd46622
        // id v ea3c5ea7-7635-4f3d-b5a0-f5101f8b935e
        // id me 4191b5ef-561e-4fd5-b168-f90881c16e4e
        // id lev e74aed3d-ed01-4f36-9d43-aecf8e0ef55e
        $url = "{$this->baseUrl}/users/{$userId}/drive/root/children";
        // drive id root jem b!CpE8HgaGGU64oJdRJtB_WxMzbMXLNHZFk3KdlI_8gFaYIwVUMW2aRpu5d0devyD0
        //url jem
        // $url = "https://graph.microsoft.com/v1.0/drives/b!CpE8HgaGGU64oJdRJtB_WxMzbMXLNHZFk3KdlI_8gFaYIwVUMW2aRpu5d0devyD0/items/012FXCIMHUZDQZ7GOX5BB3DBLARBGRA5RK/children";
        // $url = "https://graph.microsoft.com/v1.0/drives/b!CpE8HgaGGU64oJdRJtB_WxMzbMXLNHZFk3KdlI_8gFaYIwVUMW2aRpu5d0devyD0/items/012FXCIMBLTCTC3NAPRBFZQAULMFGKSM43/children";


        // $url = "{$this->baseUrl}/users/4191b5ef-561e-4fd5-b168-f90881c16e4e/drive/root/children";
        // $url = "https://graph.microsoft.com/v1.0/drives/b!P77fNEe2Ikq03XHYDetwvP8Xtkd2969OoeA2_rL6MJxwQ3kgq0EfQZhENr1J4dj9/items/01NDPLXITQKRPX4H2VKFGKJ4IJPMIGHCQ7/children";
        //  /drives/b!P77fNEe2Ikq03XHYDetwvP8Xtkd2969OoeA2_rL6MJxwQ3kgq0EfQZhENr1J4dj9/items/{item-id}/children
        // $url = "https://graph.microsoft.com/v1.0/drives/b!P77fNEe2Ikq03XHYDetwvP8Xtkd2969OoeA2_rL6MJxwQ3kgq0EfQZhENr1J4dj9/items/01NDPLXISXCOYYVRM2UFF363NGTLPZKENO/children";

        // $url = "https://graph.microsoft.com/v1.0/sites/1e3c910a-8606-4e19-b8a0-975126d07f5b/drives/b!CpE8HgaGGU64oJdRJtB_WxMzbMXLNHZFk3KdlI_8gFaYIwVUMW2aRpu5d0devyD0/items/012FXCIMF6Y2GOVW7725BZO354PWSELRRZ";

        // $url = "https://graph.microsoft.com/v1.0/drives/b%21CpE8HgaGGU64oJdRJtB_WxMzbMXLNHZFk3KdlI_8gFaYIwVUMW2aRpu5d0devyD0/items/012FXCIMF6Y2GOVW7725BZO354PWSELRRZ/children";

        try {
            // Send the request with the access token
            $response = Http::withToken($accessToken)->get($url);

            // Check if the request was successful
            if ($response->successful()) {
                // Get the items from the response
                $items = $response->json()['value'];

                // Filter only folders (items that have a "folder" property)
                $folders = array_filter($items, function ($item) {
                    return isset($item['folder']);
                });

                // Map to a simpler structure for the view, including driveId and id
                $folders = array_map(function ($folder) {
                    return [
                        'name' => $folder['name'],
                        'driveId' => $folder['parentReference']['driveId'] ?? null, // Get driveId
                        'id' => $folder['id'], // Get itemId
                        'webUrl' => $folder['webUrl'] ?? '#', // Fallback if webUrl is not present
                    ];
                }, $folders);

                // Return the view with folders
                return view('graph.onedrive.root', compact('folders'));
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch folders: ' . $e->getMessage()
            ]);
        }
    }

    // Method to list all users
    public function listUsers()
    {
        $accessToken = GraphHelper::getAccessToken();
        $url = "{$this->baseUrl}/users?\$select=id,displayName,givenName,mail,jobTitle,mobilePhone,officeLocation&\$top=50"; // Specify the properties to select and set $top for pagination
        $allUsers = [];

        try {
            while ($url) {
                $response = Http::withToken($accessToken)->get($url);

                if ($response->successful()) {
                    $data = $response->json();
                    $users = $data['value'];
                    $allUsers = array_merge($allUsers, $users);

                    // Check for the next page
                    if (isset($data['@odata.nextLink'])) {
                        $url = $data['@odata.nextLink'];
                    } else {
                        $url = null; // No more pages
                    }
                } else {
                    return back()->withErrors(['error' => 'Failed to fetch users: ' . $response->status()]);
                }
            }

            // Pass all users to the view
            return view('graph.users', compact('allUsers'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to fetch users: ' . $e->getMessage()]);
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
