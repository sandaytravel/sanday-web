<html>
    <head></head>
    <body>
        <form action="{{env('PAYPAL_URL')}}" method="post" id="paypalBtn" style="display:  block ">
            <input type="hidden" name="business" value="{{env('PAYPAL_BUSINESS_EMAIL')}}">
            @php
            $json_data = ['transaction_id' => $transaction_id];
            @endphp
            <input type="hidden" name="notify_url" value="http://159.89.201.12/payment/ipnNotify">
            <input type="hidden" name="rm" value="2">
            <input type="hidden" name="cmd" value="_cart">
            <input type="hidden" name="upload" value="1">
            <input type="hidden" name="custom" value="{{ json_encode($json_data)}}">
            @php $suffix = 1; @endphp
            @foreach($orders as $key1 => $value1)
            @foreach($value1->oredr_ietms as $key => $value)
            <input type="hidden" name="item_name_{{$suffix}}" value="{{$value->packagequantity->name}}">
<!--            <input type="hidden" name="amount_{{$suffix}}" value="{{$value->total}}">
            <input type="hidden" name="quantity_{{$suffix}}" value="{{$value->quantity}}">-->
            <input type="hidden" name="amount_{{$suffix}}" value="1">
            <input type="hidden" name="quantity_{{$suffix}}" value="1">
            @php $suffix++; @endphp
            @endforeach
            @endforeach 
            <input type="hidden" name="currency_code" value="USD">
            <input type="hidden" name="return" value="http://159.89.201.12/payment/status">

            <!--<input type="image" name="submit" border="0" width="250" src="http://legiit.aipxperts.com:8080/public/frontend/images/paynow_button.png" alt="Pay Now">-->
            <!--<img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif">-->
        </form>

        <script src="{{admin_asset('js/jquery-3.1.1.min.js')}}"></script>
        <script>
        $(document).ready(function () {
            $("#paypalBtn").submit();
        });
        </script>
    </body>
</html>