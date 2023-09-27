<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class Select2SearchController extends Controller
{
    public function index() {
        return view('home');
    }

    public function selectSearch(Request $request) {
        $products = [];

        if($request->has('q')){
            $search = $request->q;
            $products = Product::select('id','product_name')
                        ->where('product_name', 'like', '%'.$search.'%')
                        ->get();
        }else{
            $products = Product::all();
        }

        return response()->json($products);
    }
}
