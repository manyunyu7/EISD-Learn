<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLesson extends Model
{
    use HasFactory;
    protected $table = 'student_lesson';

protected $fillable = [
    'id',
     'student_id',
      'lesson_id',
      'created_at',
      'updated_at',
      'learn_status',
      'certificate_file',
    'student-lesson'
];

}
