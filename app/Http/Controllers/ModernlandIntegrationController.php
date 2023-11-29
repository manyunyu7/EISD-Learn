<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ModernlandIntegrationController extends Controller
{

    public function getLearningUsers(Request $request){
        return User::all();
    }

    /*
     * this function will check if the MDLN userId is already registered on Learning
     */
    public function checkUsername(Request $request){
        $mdlnUserId = $request->mdlnUserId;
    }


}
