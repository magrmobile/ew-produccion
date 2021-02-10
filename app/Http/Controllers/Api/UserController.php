<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\User;
use App\Machine;

class UserController extends Controller
{
    public function show()
    {
        return Auth::guard('api')->user();
    }

    public function operatorsByProcess($id)
    {
        $machine = Machine::findOrFail($id);
        //return $machine->process_id;
        return User::whereProcessId($machine->process_id)->get();
    }
}
