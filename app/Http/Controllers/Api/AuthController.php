<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResponseController;
use App\Http\Requests\UserRegisterChecker;
use App\Http\Requests\UserLoginChecker;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;


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
    public function login(UserLoginChecker $request){
        $request->validated();
        if(Auth::attempt(["email"=>$request->email,"password"=>$request->email, "password"=>$request->password])){
            $bannedtime = (new BannController)->getBannedTime($request->email);
            if($bannedtime > Carbon::now()->addHours()){
                return $this->sendError("Túl sok próbálkozás", ["nextLogin"=>$bannedtime],429);
            }
            (new BannController)->resetBannedData($request->email);
           $authUser = Auth::user();
           $success["token"] = $authUser->createToken($authUser->name."token")->plainTextToken;
            $success["time"] = (new BannController)->setBannedTime($request->email);
           $success["name"] = $authUser->name;
           return $this->sendResponse($success, "Sikeres bejelentkezés");
       }
        else{
            $loginAttempts = (new BannController)->getLoginAttempts($request->email);
            if($loginAttempts<3){
                (new BannController)->setLoginAttempts($request->email);
                return $this->sendError("Sikertelen bejelentkezés",["Hibás email vagy jelszó"],401);
       
            }elseif ($loginAttempts ==3) {
                $bannedtime = (new BannController)->setBannedTime($request->email);
              (new AlertController)->sendMail($request->email,$bannedtime);

             return $this->sendError("Sikertelen azonosítás",["error" => "Túl sok probálkozás"],401);
            }
        
        }
     
   }
    public function logout(){
        auth("sanctum")->user()->currentAccesToken()->delete();

        return $this->sendResponse([], "Sikeres kijelentkezés");
    }
}
