<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\User;
use App\Machine;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function show()
    {
        return Auth::guard('api')->user();
    }

    public function index()
    {
        //$users = User::all(['id','name','username','active_user','role','supervisor_id','process_id']);
        $users = DB::table('users')
                ->select('id','name','username','active_user','role','supervisor_id','process_id')
                ->get();
        return compact("users");
    }

    public function operators()
    {
        //$users = User::all(['id','name','username','active_user','role','supervisor_id','process_id']);
        $operators = DB::table('users')
                ->where('role','operator')
                ->where('active',1)
                ->select('id','name','username','active as active_user','role','supervisor_id','process_id')
                ->get();
        return compact("operators");
    }


    public function operatorsByProcess($id)
    {
        $machine = Machine::findOrFail($id);
        //return $machine->process_id;
        $operators = User::whereProcessId($machine->process_id)->get();
        return compact("operators");
    }
}
