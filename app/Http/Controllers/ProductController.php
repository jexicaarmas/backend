<?php

namespace App\Http\Controllers;


use App\Models\Product; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $products = Product::all();
      return $this->showAll($products);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
          'reference'     => 'required|string|unique:products,reference', 
          'name'          => 'required|string', 
          'description'   => 'nullable|string',
          'quantity'      => 'required|integer',
          'enable'        => 'required|boolean',
          'image'         => 'required|image',
        ]);

      $product = new Product; 
      $product->reference    = $request->reference; 
      $product->name         = $request->name;
      $product->description  = $request->description;
      $product->quantity     = $request->quantity;
      $product->enable       = $request->enable;
      $product->image        = $request->image->store('');
      $product->save(); 

      return $this->showMessage(__('messages.store'), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
      return $this->showOne($product);
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
      $this->validate($request, [
          'name'          => 'string', 
          'description'   => 'nullable|string',
          'quantity'      => 'integer',
          'enable'        => 'boolean',
          'image'         => 'image',
        ]);
      
      $product->name         = $request->name;
      $product->description  = $request->description;
      $product->quantity     = $request->quantity;
      $product->enable       = $request->enable;
      if ($request->hasFile('image')){
        Storage::delete($product->image);
        $product->image        = $request->image->store(''); 
      } 
      $product->update(); 

      return $this->showMessage(__('messages.update'), 200);
    }

    public function search(Request $request)
    {
       $this->validate($request, [
        'keyword'        => 'required|string', 
        'value'          => 'required|string'
      ]);

      $products = Product::where($request->keyword, 'LIKE', '%'. $request->value . '%')->get();
      return $this->showAll($products);
    }
}
