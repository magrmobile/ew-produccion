<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Process;
use App\User;

class ProcessController extends Controller
{
    public function index($id)
    {
        return User::whereProcessId($id)->get(["id","username","name"]);
    }
}
