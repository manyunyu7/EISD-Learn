<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AitiHubController extends Controller
{
    public function checkAitiHub(){
        return DB::connection('ithub')->table('users')->get();
    }
}
