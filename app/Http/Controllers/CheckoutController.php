<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use Cartalyst\Stripe\Exception\CardErrorException;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return view('checkout',[
            'discount'=>$this->discountVariables()->get('discount'),
            'newSubtotal'=>$this->discountVariables()->get('new_subtotal'),
            'newTax'=>$this->discountVariables()->get('new_tax'),
            'newTotal'=>$this->discountVariables()->get('new_total'),
        ]);
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
    public function store(CheckoutRequest $request)
    {
        //
        $contents= Cart::instance('default')->content()->map(function ($item){
            return $item->model->slug . "," . $item->qty;
        })->values()->toJson();

       try{
            $charge= Stripe::charges()->create([
                'amount'=> $this->discountVariables()->get('new_total') / 100,
                'currency'=>'EGP',
                'source'=>$request['stripeToken'],
                'description'=>'Order',
                'receipt_email'=>$request['email'],
                'metadata'=>[
                    'contents'=>$contents,
                    'quantity'=>Cart::instance('default')->count(),
                    'discount'=>collect($this->discountVariables()->get('discount'))
                ]
            ]);
            Cart::instance('default')->destroy();
            return redirect()->route('confirmation.index')->with('success','Thank you for check out , Your order payment done successfully');
       }catch (CardErrorException $exception){

           return back()->withErrors('Errors' . $exception->getMessage());
       }
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
    private function discountVariables(){
        $tax= config('cart.tax') / 100;


        $discount= session()->get('coupon')['discount']?? 0;
        $newSubtotal=Cart::subtotal()- $discount ;
        $newTax= $newSubtotal * $tax;
        $newTotal= $newSubtotal + $newTax;
        return collect([
            'discount'=>$discount,
            'new_subtotal'=>$newSubtotal,
            'new_tax'=>$newTax,
            'new_total'=>$newTotal
        ]);
    }
}
