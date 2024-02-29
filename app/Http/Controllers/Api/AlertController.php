<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Iluminate\Support\Facades\Mail;
use App\Mail\AlertMail;

class AlertController extends Controller
{
public function sendMail($user, $time){
    $content = [
        "title" => "Figyelmeztető levél",
        "user" => $user,
        "time" => $time
    ];
    Mail::to("laravelejlesztes@gmail.com")->send(new AlertMail($content));
}
}
