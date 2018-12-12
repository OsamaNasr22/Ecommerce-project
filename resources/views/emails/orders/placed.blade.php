@component('mail::message')
# Hi ,{{Auth::check()?auth()->user()->name : 'our clint'}}

<p>Subtotal : {{$order->billing_subtotal}}</p>
<p>Tax : {{$order->billing_tax}}</p>
<p>discount : {{$order->billing_discount}}</p>
<p>Total : {{$order->billing_total}}</p>
<p>Payment Method : {{$order->payment_gateway}}</p>

@component('mail::button', ['url' => route('shop.index')])
Revisit shop
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent
