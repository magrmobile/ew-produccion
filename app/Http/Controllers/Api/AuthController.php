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

        if(Auth::guard('api')->attempt($credentials)) {
            $user = Auth::guard('api')->user();
            $jwt = JwtAuth::generateToken($user);
            $success = true;

            $lastLogin = Carbon::createFromFormat('Y-m-d H:i:s',$user->lastLoginAt());
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

    public function logout()
    {
        Auth::guard('api')->logout();
        $success = true;
        return compact('success');
    }
}
