<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSection extends Model
{
    use HasFactory;
    protected $table = 'student_section';

    protected $fillable = ['student_id', 'section_id', 'is_finished'];
}
