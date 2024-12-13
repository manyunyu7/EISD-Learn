<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamQuestionAnswers extends Model
{
    use HasFactory;

    protected $appends = [
        'img_question',
    ];

    public function getImgQuestionAttribute()
    {

        if(str_contains($this->image,"question-img-s3")){
            return "https://lms-modernland.s3.ap-southeast-3.amazonaws.com/".$this->image;
        }
        // Assuming you have a column named 'img_filename' that stores the image filename
        // You can modify this to generate the full image path as per your requirement
        return asset('storage/profile/' . $this->image);
    }
}
