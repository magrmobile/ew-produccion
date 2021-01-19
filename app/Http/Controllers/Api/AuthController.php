<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
Use JwtAuth;
use Carbon\Carbon;
use App\Stop;

use Illuminate\Auth\Events\Logout;
use Yadahan\AuthenticationLog\AuthenticationLog;

class AuthController extends Controller
{
    public function login(Request $request) 
    {
        $credentials = $request->only('username','password');

        $api_attempt = Auth::guard('api')->attempt($credentials);

        if($api_attempt) {
            $user = Auth::guard('api')->user();
            $jwt = JwtAuth::generateToken($user);

            $ip = $request->ip();
            $userAgent = $request->userAgent();
            $known = $user->authentications()->whereIpAddress($ip)->whereUserAgent($userAgent)->first();

            $authenticationLog = new AuthenticationLog([
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'login_at' => Carbon::now(),
            ]);
    
            $user->authentications()->save($authenticationLog);

            $lastStop = Stop::where('operator_id', $user->id)
                    ->latest('id')
                    ->first();

            if($lastStop == null) {
                $lastStopDateTimeStart = $user->lastLoginAt();
            } else {
                $lastStopDateTimeStart = $lastStop->stop_datetime_end;
            }

            $success = true;
            
            // Return successfull sign in response with generated jwt.
            return compact('success','user','jwt','lastStopDateTimeStart');
        } else {
            // Return response for failed attempt...
            $success = false;
            $message = 'Invalid Credentials';
            return compact('success','message');
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('api')->user();
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $authenticationLog = $user->authentications()->whereIpAddress($ip)->whereUserAgent($userAgent)->first();

        if (! $authenticationLog) {
            $authenticationLog = new AuthenticationLog([
                'ip_address' => $ip,
                'user_agent' => $userAgent,
            ]);
        }

        $authenticationLog->logout_at = Carbon::now();

        $user->authentications()->save($authenticationLog);

        Auth::guard('api')->logout();

        $success = true;

        return compact('success');
    }
}
