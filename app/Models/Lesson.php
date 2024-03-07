<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    protected $table = 'lessons';
    /**
* fillable
* SELECT `id`, `course_title`,
*`course_cover_image`, `course_trailer`, `mentor_id`,
* `category`, `course_description`, `created_at`,
* `updated_at` FROM `course`
* @var array
*/
// protected $appends = ['first_section_id'];


protected $fillable = [
    'id', 
    'course_title', 
    'course_cover_image',
    'course_trailer',
    'mentor_id',
    'course_category',
    'course_description',
    'created_at',
    'updated_at',
    'start_time',
    'end_time',
    'can_be_accessed', 
    'text_descriptions',
    'pin',
    'position',
    'target_employee',
    'new_class'
];

}
