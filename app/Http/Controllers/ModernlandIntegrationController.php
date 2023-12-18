<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Helpers\MyResponseHelper;
use App\Models\User;
use Illuminate\Http\Request;

class ModernlandIntegrationController extends Controller
{





    public function getAvailableUsers(Request $request){
        try {
            $user = User::whereNull('mdln_username')
                ->orWhere('mdln_username', '')
                ->get();

            if ($user->isEmpty()) {
                return MyHelper::myError('User not found', 404);
            }
            return MyHelper::mySuccess($user, 'Get data successfully', 200);
        } catch (\Exception $e) {
            return MyHelper::myError($e->getMessage(), 500);
        }
    }

    public function checkId(Request $request, $mdlnUserId)
    {
        try {
            $userCount = User::where("mdln_username", '=', $mdlnUserId)->count();

            if ($userCount === 0) {
                return MyHelper::myError("User $mdlnUserId not found", 404);
            }

            return MyHelper::mySuccess(true, 'Get data successfully', 200);
        } catch (\Exception $e) {
            return MyHelper::myError($e->getMessage(), 500);
        }
    }

    /*
     * this function will check if the MDLN userId is already registered on Learning
     */
    public function checkUsername(Request $request){
        $mdlnUserId = $request->mdlnUserId;
    }


}
