<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Code;

class CodeController extends Controller
{
    public function index()
    {
        return Code::all(['id','code','description','type']);
    }

    public function show($id)
    {
        return Code::findOrFail($id);
    }
}
