<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailClassController extends Controller
{
    //
    public function viewClass($id){
        // $lesson_id = $lesson->id;
        $data = Lesson::findOrFail($id);
        $dayta = DB::table('course_section as c')
            ->select(
                'a.id as lesson_id',
                'a.course_title as lessons_title',
                'a.mentor_id',
                'b.name as mentor_name',
                'c.id as section_id',
                'c.quiz_session_id',
                'c.section_order',
                'c.section_title',
                'c.section_content',
                'c.section_video',
                'c.created_at',
                'c.updated_at',
                'c.can_be_accessed'
            )
            ->leftJoin('lessons as a', 'a.id', '=', 'c.course_id')
            ->leftJoin('users as b', 'a.mentor_id', '=', 'b.id')
            ->where('a.id', $id)
            ->orderBy('c.section_order', 'ASC')
            ->get();
        // return $dayta;

        $jumlahSection = $dayta->count();
        return view("lessons.view_class")->with(compact("data","dayta", "jumlahSection"));
    }
    
}
