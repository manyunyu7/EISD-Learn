<?php

namespace App\Http\Controllers;

use App\Models\RegistrationCode;
use Illuminate\Http\Request;

class RegistrationCodeController extends Controller
{
    //crud method with RegistrationCode model
    public function index(){

    }

    public function create(){

    }

    public function store(){

        $data = new RegistrationCode();
        $data->registration_code = request('registration_code');

    }

    public function show(){

    }

    public function edit(){

    }

    public function update(){

    }

    public function destroy(){

    }
}
