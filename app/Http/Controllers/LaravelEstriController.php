<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaravelEstriController extends Controller
{
    public function imageUpload()
    {
        return view('s3.image_upload');
    }

    public function getAllFilesWithUrls()
    {
        // Get all files from the 'testing' directory
        $files = Storage::disk('s3')->allFiles('testing');

        // Array to store file URLs
        $fileUrls = [];

        // Generate signed URLs for each file
        foreach ($files as $file) {
            $url = Storage::disk('s3')->temporaryUrl($file, now()->addMinutes(60)); // Adjust expiry time as needed
            $fileUrls[] = $url;
        }

        return $fileUrls;
    }

    public function imageUploadPost(Request $request)
    {
        $request->validate([
//            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time().'.'.$request->image->extension();

        // Upload the file to S3
        $path = Storage::disk('s3')->put('testing', $request->image);

        return $path;
        // Generate a signed URL for accessing the file
        $url = Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(60)); // Adjust expiry time as needed


        /* Store $imageName name in DATABASE from HERE */

        return back()
            ->with('success', 'You have successfully uploaded the image.')
            ->with('image', $url);
    }
}
