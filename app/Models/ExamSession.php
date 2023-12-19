<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    use HasFactory;
    protected $appends = ["quiz_name"];

    public function getQuizNameAttribute(){
        $exam = Exam::find($this->exam_id);
        if($exam==null){
            return "";
        }
        return $exam->title;
    }
}
