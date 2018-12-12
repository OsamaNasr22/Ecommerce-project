<?php

namespace App\Http\Controllers;

use App\Coupon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CouponController extends Controller
{

    /**
     *Store coupon details in the session
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


    /**
     * Remove the coupon details form the session.
     * @param Coupon $coupon
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Coupon $coupon)
    {
        //
        session()->forget('coupon');
        return redirect()->route('checkout.index')->with('success','Coupon has been remove');
    }
}
