<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalProject extends Model
{
    use HasFactory;
    protected $table = 'Student_Submission';
    protected $fillable = [
        'id'	,
        'student_id',
        'lesson_id',
        'status',
        'note',
        'teacher_note',
        'file',
        'nilai',
        'created_at',
        'updated_at'
    ];
}
