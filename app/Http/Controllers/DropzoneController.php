<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DropzoneController extends Controller
{
    public function dropzone()
    {
        $files = scandir(public_path('images'));
        $data = [];
        foreach ($files as $row) {
            if ($row != '.' && $row != '..') {
                $data[] = [
                    'name' => explode('.', $row)[0],
                    'url' => asset('images/' . $row)
                ];
            }
        }
        return view('welcome', compact('data'));
    }
    
    public function dropzoneStore(Request $request)
    {
        $image = $request->file('file');
   
        $imageName = time() . '-' . strtoupper(Str::random(10)) . '.' . $image->extension();
        $image->move(public_path('images'), $imageName);
   
        return response()->json(['success'=> $imageName]);
    }
}
