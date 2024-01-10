<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;

class DetailClassController extends Controller
{
    //
    public function viewClass($id){
        $data = Lesson::findOrFail($id);
        // return $data;
        return view("lessons.view_class")->with(compact("data"));
    }
}
