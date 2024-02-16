<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResponseController;
use App\Http\Requests\UserRegisterChecker;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class AuthController extends ResponseController
{
    public function register(UserRegisterChecker $request){
        $request->validated();
        $input = $request->all();
        $input["password"]=bcrypt($input["password"]);
        $user = User::create($input);
        $success["name"]=$user->name;
        return $this ->sendResponse($success, "Sikeres Regisztráció");
    }
    public function login(){

    }
    public function logout(){

    }
}
