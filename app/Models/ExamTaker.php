<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamTaker extends Model
{
    use HasFactory;

    protected $appends = ['m_quiz_result'];

    // Define the method to compute m_quiz_result
    public function getMQuizResultAttribute()
    {
        return json_decode($this->user_answers);
    }
}
