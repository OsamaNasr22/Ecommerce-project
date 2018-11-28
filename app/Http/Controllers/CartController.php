<?php

namespace App\Http\Controllers;

use App\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $suggestions= Product::Suggestions()->get();
        return view('cart')->with('suggestions',$suggestions);
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
    public function store($id)
    {
        //
        $product= Product::findOrFail($id);
       $duplicates = Cart::search(function ($cartItem , $rowId) use ($product){

           return $cartItem->id === $product->id;

       });
       if($duplicates->isNotEmpty()){
           return redirect()->route('cart.index')->with('success','The item is already exists in the cart');
       }
        Cart::add($product->id,$product->name,1,$product->price)
            ->associate(Product::class);
        return redirect()->route('cart.index')->with('success','Item was added to your cart');

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
       $this->validate($request,[
           'quantity'=>'required|numeric|between:1,5'
       ]);
       $cart= Cart::update($id,$request['quantity']);
       session()->flash('success','quantity of product updated successfully');
       return response()->json(['success'=>true],200);
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
        Cart::remove($id);
        return redirect()->back()->with('success',"Product was deleted successfully from the cart");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function switchToSaveForLater($id)
    {
        //
        $item = Cart::get($id);
        Cart::remove($id);
        $duplicates= Cart::instance('saveForLater')->search(function ($cartItem,$rowId) use($id){
            return $rowId === $id;
        });
        if($duplicates->isNotEmpty()){
            return redirect()->route('cart.index')->with('success','the item is already in save for later section');

        }
        Cart::instance('saveForLater')->add($item->id, $item->name, 1 , $item->price)->
        associate(Product::class);
        return redirect()->back()->with('success',"Product was added successfully to save for later");
    }

}
