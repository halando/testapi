<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class BannController extends Controller
{
   public function getLoginAttempts($email ){

        $user = User::where("email",$email)->first();
        $loginAttempts = $user->login_attempts;

        return $loginAttempts;
   }
    public function setLoginAttempts($email){
        User::where("email",$email)->increment("login_attempts");
    }
    public function getBannedTime($email){
        
        $user = User::where("email",$email)->first();
        return $user->banned_time;
    }
    public function setBannedTime($email){

        $user = User::where("email",$email)->first();
        $actualTime = Carbon::now()->addHours();
        $bannedTime = $actualTime->addSeconds(60);

        $user->banned_time = $bannedTime;
        $user->save();
        return $bannedTime;
    }

    public function resetBannedData($email){
        $user= User::where("email",$email)->first();
        $user->login_attempts = 0;
        $user->banned_time = null;

        $user->save();
    }
}
