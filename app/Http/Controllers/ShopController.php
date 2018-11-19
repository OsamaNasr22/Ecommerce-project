<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $products= Product::inRandomOrder()->take(12)->get();
        return view('products')->with('products',$products);
    }


    public function show($slug)
    {
        //
        $product= Product::where('slug',$slug)->firstOrFail();
        $suggestions= Product::where('slug','!=',$slug)->inRandomOrder()->take(4)->get();
        return view('product')->with([
            'product'=>$product,
            'suggestions'=>$suggestions
        ]);

    }

}
