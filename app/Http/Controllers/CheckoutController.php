<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Order;
use App\OrderProduct;
use Cartalyst\Stripe\Exception\CardErrorException;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if(\request()->is('guestcheckout')){
            if(Auth::check()){
                return redirect()->route('checkout.index');
            }
        }
        return view('checkout',[
            'discount'=>$this->discountVariables()->get('discount'),
            'newSubtotal'=>$this->discountVariables()->get('new_subtotal'),
            'newTax'=>$this->discountVariables()->get('new_tax'),
            'newTotal'=>$this->discountVariables()->get('new_total'),
        ]);
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

           //charge the stripe
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
            //save the order in database
            $this->saveOrderInDb($request,null);
            //delete the cart content
            Cart::instance('default')->destroy();
            //delete coupon code from the session
            session()->forget('coupon');
            return redirect()->route('confirmation.index')->with('success','Thank you for check out , Your order payment done successfully');
       }catch (CardErrorException $exception){
           $this->saveOrderInDb($request, $exception->getMessage());
           return back()->withErrors('Errors' . $exception->getMessage());
       }
    }

    /**
     * prepare the new variables of cart after apply discount
     * @return \Illuminate\Support\Collection
     */
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
            'new_total'=>$newTotal,
            'code'=>session('coupon')['name']??null
        ]);
    }

    /**
     * Save order information in database after checkout
     * @param $request
     * @param $error
     * @return mixed
     */
    private function saveOrderInDb($request, $error){

       $order= Order::create([
           'user_id' => auth()->user() ? auth()->user()->id : null,
           'billing_email' => $request->email,
           'billing_name' => $request->name,
           'billing_address' => $request->address,
           'billing_city' => $request->city,
           'billing_province' => $request->province,
           'billing_postalcode' => $request->postalcode,
           'billing_phone' => $request->phone,
           'billing_name_on_card' => $request->name_on_card,
           'billing_discount' => $this->discountVariables()->get('discount'),
           'billing_discount_code' => $this->discountVariables()->get('code'),
           'billing_subtotal' => $this->discountVariables()->get('new_subtotal'),
           'billing_tax' => $this->discountVariables()->get('new_tax'),
           'billing_total' => $this->discountVariables()->get('new_total'),
           'error' => $error,


        ]);
        

        foreach (Cart::instance('default')->content() as $item){
            OrderProduct::create([
              'product_id'=>$item->model->id,
              'order_id'=>$order->id,
                'quantity'=>$item->qty
            ]);
        }
        return $order;
    }
}
