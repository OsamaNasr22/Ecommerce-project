<?php

namespace App\Http\Controllers;

use App\Coupon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $coupon = Coupon::findByCode($request['code']);
        if(!$coupon) return redirect()->route('checkout.index')->withErrors('Invalid coupon code please try again');
        session()->put('coupon',[
            'code'=>$coupon->code,
            'discount'=>$coupon->discount(Cart::subtotal())
        ]);
        return redirect()->route('checkout.index')->with('success','Coupon has been applied');
    }

    public function destroy(Coupon $coupon)
    {
        //
        session()->forget('coupon');
        return redirect()->route('checkout.index')->with('success','Coupon has been remove');
    }
}
