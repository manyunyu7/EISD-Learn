<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    use HasFactory;
    protected $table = 'Course_Section';
    protected $fillable = [
        'lesson_id'	,
        'lessons_title',
        'section_video',
        'mentor_id',
        'mentor_name',
        'section_id',
        'course_id',
        'section_title',
        'section_order',
        'section_content',
        'created_at',
        'updated_at'
    ];
}
