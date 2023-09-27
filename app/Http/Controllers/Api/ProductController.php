<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $products = DB::table('products')
               ->select('id','product_name','metal_type','process_id','stock')
               ->get();
        return compact("products");
        //return Product::all(['id','product_name','metal_type','stock']);
    }

    public function getByProcess($process_id) 
    {
        if($process_id != 0) {
            $products = DB::table('products')
               ->where('process_id', $process_id)
               ->select('id','product_name','metal_type','stock')
               ->get();
            //return Product::where('process_id', $process_id)->get(['id','product_name','metal_type','stock']);
        } else {
            $products = DB::table('products')
               ->select('id','product_name','metal_type','stock')
               ->get();
            //return Product::all(['id','product_name','metal_type','stock']);
        }
        
        return compact("products");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
