<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class LessonCategory extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $appends = ["full_img_path"];

    // Define an accessor for the full_img_path attribute
    public function getFullImgPathAttribute()
    {
        // Assuming your image path is stored in a column named 'img_path'
        $imgPath = $this->img_path; // Adjust this according to your actual attribute name

        // Manipulate the path if needed
        // For example, you might want to prepend the base URL
        $fullImgPath = url("/").Storage::url('public/class/category/') . $imgPath;

        return $fullImgPath;
    }
}


