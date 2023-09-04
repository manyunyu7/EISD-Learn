<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Exam extends Model
{
    use HasFactory;

    protected $appends = ["img_full_path"];

    public function getImgFullPathAttribute(){
        $url = asset('storage/exam/cover/' . $this->image);
        return $url;
    }

}
