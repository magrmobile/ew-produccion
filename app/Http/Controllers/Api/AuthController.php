<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
Use JwtAuth;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login(Request $request) 
    {
        $credentials = $request->only('username','password');

        $web_attempt = Auth::guard('web')->attempt($credentials);
        if($web_attempt) {
            $userw = Auth::guard('web')->user();
            $api_attempt = Auth::guard('api')->attempt($credentials);
            if($api_attempt) {
                $user = Auth::guard('api')->user();
                $jwt = JwtAuth::generateToken($user);
                $success = true;
    
                if($userw->lastLoginAt()) {
                    $lastLogin = Carbon::createFromFormat('Y-m-d H:i:s',$userw->lastLoginAt());
                } else {
                    $lastLogin = Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'));
                }
    
                $lastLoginDate = $lastLogin->format('Y-m-d');
                $lastLoginTime = $lastLogin->format('H:i:s');
    
                //$lastlogin = $user->lastloginAt();
                // Return successfull sign in response with generated jwt.
                return compact('success','user','jwt','lastLoginDate','lastLoginTime');
            } else {
                // Return response for failed attempt...
                $success = false;
                $message = 'Invalid Credentials';
                return compact('success','message');
            }
        }
    }

    public function logout()
    {
        Auth::guard('api')->logout();
        Auth::guard('web')->logout();
        $success = true;
        return compact('success');
    }
}
