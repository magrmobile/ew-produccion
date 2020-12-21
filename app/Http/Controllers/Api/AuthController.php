<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
Use JwtAuth;

class AuthController extends Controller
{
    public function login(Request $request) 
    {
        $credentials = $request->only('username','password');

        if(Auth::guard('api')->attempt($credentials)) {
            $user = Auth::guard('api')->user();
            $jwt = JwtAuth::generateToken($user);
            $error = false;

            // Return successfull sign in response with generated jwt.
            $data = compact('user','jwt');
            return compact('error','data');
        } else {
            // Return response for failed attempt...
            $error = true;
            $message = 'Invalid Credentials';
            return compact('error','message');
        }
    }

    public function logout()
    {
        Auth::guard('api')->logout();
        $success = true;
        return compact('success');
    }
}
