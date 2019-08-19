@extends('layouts.base')

@section('content')
<div class="container">
    <div class="gateway--info">
        <div class="gateway--desc">
            @if(session()->has('message'))
            <p class="message">
                {{ session('message') }}
            </p>
            @endif
            <p><strong>Order Overview !</strong></p>
            <hr>
            <p>Item : Yearly Subscription cost !</p>
            <p>Amount : ${{ "1" }}</p>
            <hr>
        </div>
        <div class="gateway--paypal">
            <form action="{{ route('checkout.payment.paypal', ['order' => encrypt(mt_rand(1, 20))]) }}" method="post" id="paypalBtn" style="display:  block ">
                {{ csrf_field() }}
                <input type="hidden" name="business" value="legiit-seller@aipxperts.com">

                <input type="hidden" name="notify_url" value="http://192.168.1.35/san-php/ipnNotify">
                <input type="hidden" name="rm" value="2">
                <input type="hidden" name="cmd" value="_cart">
                <input type="hidden" name="upload" value="1">
                <input type="hidden" name="custom" value="{&quot;uid&quot;:12,&quot;affiliate_id&quot;:&quot;&quot;,&quot;totalPromoDiscount&quot;:0,&quot;totalCouponDiscount&quot;:0,&quot;is_custom_order&quot;:0}">


                <input type="hidden" name="item_name_1" value="QA Services....">
                <input type="hidden" name="amount_1" value="1">
                <input type="hidden" name="quantity_1" value="1">
                <input type="hidden" name="item_number_1" value="main_111_42_0">


                <input type="hidden" name="currency_code" value="USD">
                <input type="hidden" name="return" value="http://192.168.1.35/san-php/dashboard">

                <button type="submit" class="btn btn-pay">
                    <i class="fa fa-paypal" aria-hidden="true"></i> Pay with PayPal
                </button>
            </form>
        </div>
    </div>
</div>
@stop