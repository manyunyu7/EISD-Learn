<?php

namespace App\Http\Controllers;

use App\Models\FileOnS3;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileOnS3Controller extends Controller
{
    public function index()
    {
        // Retrieve files from the database instead of S3 directly
        $files = FileOnS3::where("uploaded_by", auth()->user()->id)->get();
        return view('filemanager.s3.index', compact('files'));
    }

    public function create()
    {
        // Show form to upload a file
        return view('filemanager.s3.create');
    }

    // Controller method handling the file upload
// Controller method handling the file upload
public function store(Request $request)
{
    // Validate the request
    $request->validate([
        'file' => 'required|file',
        'description' => 'nullable|string',
    ]);

    // Get the file
    $file = $request->file('file');

    // Generate a unique file name (or use the original name if preferred)
    $fileName = uniqid() . '_' . $file->getClientOriginalName();

    // Specify the folder path within the bucket
    $folder = 'file_manager/'.auth()->user()->id;

    // Store the file in S3 under the specified folder without specifying ACL
    $path = Storage::disk('s3')->putFileAs($folder, $file, $fileName);

    // Save file information to the database
    $fileRecord = new FileOnS3();
    $fileRecord->filename = $path;
    $fileRecord->description = $request->input('description');
    // Assuming you have authentication and know the uploaded_by user
    $fileRecord->uploaded_by = auth()->user()->id; // or any other logic to get user ID
    $fileRecord->save();

    // Optionally, you can handle additional metadata or validation before saving


    // Redirect or return a response indicating success
    return redirect()->route('filemanager.s3.index')->with('success', 'File deleted successfully');
}
    public function destroy($id)
    {
        // Delete file from S3 and database
        $fileRecord = FileOnS3::findOrFail($id);
        Storage::disk('s3')->delete($fileRecord->path); // Use path stored in database
        $fileRecord->delete();

        return redirect()->route('filemanager.s3.index')->with('success', 'File deleted successfully');
    }
}
