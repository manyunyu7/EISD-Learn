<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    use HasFactory;

    protected $table = 'lesson_progress';

    protected $fillable = [
        'student_id',
        'lesson_id',
        'section_id',
        'type', // material / pretest / quiz / posttest / evaluation
        'status' // completed / inprogress / not_started
    ];

    // Relasi opsional
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }
}
