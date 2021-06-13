<html>
    <body onload="document.forms['member_signup'].submit()">
        <form method="POST" name="member_signup" action="https://checkout.flutterwave.com/v3/hosted/pay">
            <input type="hidden" name="public_key" value="FLWPUBK_TEST-4937240a52d479c2a57a187a8e1f65ec-X" />
            <input type="hidden" name="customer[email]" value="{{ $order->customer->email }}" />
            <input type="hidden" name="customer[phone_number]" value="{{ $order->customer->phone }}" />
            <input type="hidden" name="customer[name]" value="{{ $order->customer->name }}" />
            <input type="hidden" name="tx_ref" value="bitethtx-019203" />
            <input type="hidden" name="amount" value="{{ $order->payment }}" />
            <input type="hidden" name="currency" value="{{App\Setting::find(1)->currency}}" />
            <input type="hidden" name="meta[token]" value="20" />
            <input type="hidden" name="redirect_url" value="{{ url('transction_verify/'.$order->id) }}" />
        </form>
    </body>
</html>
