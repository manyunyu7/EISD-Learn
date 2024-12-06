<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    protected $appends = ["full_img_path"];

    // Define an accessor for the full_img_path attribute
    public function getFullImgPathAttribute()
    {
        // Assuming your image path is stored in a column named 'img_path'
        $imgPath = $this->course_cover_image; // Adjust this according to your actual attribute name

        if (str_contains($this->course_cover_image, "lesson-s3")) {
            return env('AWS_BASE_URL') . $this->course_cover_image;
        }

        // Manipulate the path if needed
        // For example, you might want to prepend the base URL
        $fullImgPath = url("/") . Storage::url('public/class/category/') . $imgPath;

        return $fullImgPath;
    }


    protected $fillable = [
        'id',
        'course_title',
        'course_cover_image',
        'course_trailer',
        'mentor_id',
        'course_category',
        'category_id',
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
        'new_class',
        'department_id',
        'position_id',
        'tipe',
        'rating_course'
    ];
}
