<?php

namespace App\Http\Controllers;


use App\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class SaveForLaterController extends Controller
{

    public function destroy($id)
    {
        //
        Cart::instance('saveForLater')->remove($id);
      return  redirect()->route('cart.index')->with('success','The item deleted successfully from save for later section');

    }

    public function switchToCart($id){
        $item = Cart::instance('saveForLater')->get($id);
        Cart::instance('saveForLater')->remove($id);

        $duplicates= Cart::instance('default')->search(function ($cartItem,$rowId) use($id){
            return $rowId === $id;
        });
        if($duplicates->isNotEmpty()){
            return redirect()->route('cart.index')->with('success','the item is already in save for later section');

        }
        Cart::instance('default')->add($item->id, $item->name, 1 , $item->price)->associate(Product::class);
        return redirect()->back()->with('success',"Product was moved successfully to cart");
    }
}
