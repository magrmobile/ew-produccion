<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->paginate(5);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'product_name' => 'required|min:3',
            'metal_type' => 'required',
            'stock' => 'required'
        ];

        $this->validate($request, $rules);

        Product::create(
            $request->only('product_name','metal_type','stock')
        );

        $notification = 'El Producto se ha registrado correctamente';
        return redirect('/products')->with(compact('notification'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $rules = [
            'product_name' => 'required|min:3',
            'metal_type' => 'required',
            'stock' => 'required'
        ];

        $this->validate($request, $rules);
        $data = $request->only('product_name','metal_type','stock');

        $product->fill($data);
        $product->save(); // UPDATE

        $notification = 'La información del producto se ha registrado correctamente';
        return redirect('/products')->with(compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $productName = $product->product_name;
        $product->delete();

        $notification = "El Producto $productName ha sido eliminado correctamente";
        return redirect('/products')->with(compact('notification'));
    }
}
