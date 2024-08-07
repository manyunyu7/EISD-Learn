<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    use HasFactory;
    protected $table = 'course_section';
    protected $fillable = [
        'id',
        'enable_absensi',
        'quiz_session_id',
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
        'updated_at',
        'can_be_accessed',
        'duration_take'
    ];
}
