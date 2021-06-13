var url = $('#mainurl').val();
var OneSignal = window.OneSignal || [];
var orderData, coupen_price = 0, payment = 0;

$(document).ready(function()
{
    orderData = new FormData();
    orderData.append('payment_type', 'LOCAL');
    $('.payment_type input[type=radio][name=payment]').change(function ()
    {
        payment = ($('#total_payment').val());
        orderData.append('payment_type', this.value);
        if (this.value == 'PAYPAL')
        {
            $('.paypal_payment').html('');
            $('#paypal').show(500);
            $('#razorpay').hide(500);
            $('#stripe').hide(500);
            $('#paytm').hide(500);
            paypal_sdk.Buttons({
                createOrder: function (data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: payment
                            }
                        }]
                    });
                },

                onApprove: function (data, actions) {
                    return actions.order.capture().then(function (details) {
                        orderData.append('payment_status', 1);
                        orderData.append('payment_token', details.id);
                        confirm_order();
                    });
                }

            }).render('.paypal_payment');

            // }
        }
        if (this.value == 'RAZOR')
        {
            console.log('razor');
            $('#paypal').hide(500);
            $('#razorpay').show(500);
            $('#stripe').hide(500);
            $('#flutterwave').hide(500);
            $('#paystack').hide(500);
            $('#paytm').hide(500);
            var to_pay = $('#to_pay').text().trim().substring(1);
            var options =
            {
                key: $('#RAZORPAY_KEY').val(),
                amount: to_pay * 100,
                name: 'FoodLans',
                description: '',
                currency: $('#hidden_cuurency_type').val(),
                image: 'https://i.imgur.com/n5tjHFD.png',
                handler: demoSuccessHandler
            }
            window.r = new Razorpay(options);
            document.getElementById('paybtn').onclick = function ()
            {
                var to_pay = $('#to_pay').text().trim().substring(1);
                r.open()
            }
        }
        if(this.value == 'STRIPE')
        {
            $('#paypal').hide(500);
            $('#razorpay').hide(500);
            $('#stripe').show(500);
            $('#flutterwave').hide(500);
            $('#paystack').hide(500);
            $('#paytm').hide(500);
        }
        if(this.value == 'FLUTTERWAVE')
        {
            $('#paypal').hide(500);
            $('#razorpay').hide(500);
            $('#stripe').hide(500);
            $('#flutterwave').show(500);
            $('#paystack').hide(500);
            $('#paytm').hide(500);
        }
        if (this.value == 'PAYSTACK')
        {
            $('#paypal').hide(500);
            $('#razorpay').hide(500);
            $('#stripe').hide(500);
            $('#flutterwave').hide(500);
            $('#paystack').show(500);
            $('#paytm').hide(500);
        }
        if (this.value == 'PAYTM')
        {
            $('#paypal').hide(500);
            $('#razorpay').hide(500);
            $('#stripe').hide(500);
            $('#flutterwave').hide(500);
            $('#paystack').hide(500);
            $('#paytm').show(500);
        }
    });

    $('input[type=radio][name=delivery_type]').change(function ()
    {
        payment = ($('#total_payment').val());
        if($('#hidden_session').val() == 'food')
        {
            var c = parseInt($('#hidden_coupen_id').val());
            var currency = $('#currency').val();
            if(this.value == 'delivery')
            {
                if(c != 0)
                {
                    var item_total = parseInt($('#price').val());
                    var res = $('.restaurent_charge').text();
                    var restarunt_charge = parseInt(res.substring(1));
                    var delivery_charge = parseInt($('#hidden_delivery_charge').val());
                    var hidden_coupen_disc_type = $('#hidden_coupen_discountType').val();
                    var hidden_coupen_disc = parseInt($('#hidden_coupen_discount').val());
                    var t = item_total;
                    if(hidden_coupen_disc_type == 'percentage')
                    {
                        var discount = (t) * (hidden_coupen_disc/100);
                        console.log('t',t , 'discount',discount);
                    }
                    if(hidden_coupen_disc_type == 'amount')
                    {
                        var discount = hidden_coupen_disc;
                    }
                    console.log('(t  + delivery_charge + restarunt_charge)',parseInt(t  + delivery_charge + restarunt_charge));
                    $('#discount').html(currency + discount);
                    $("#saved").text('you have saved ' + currency + discount);
                    $('#to_pay').html(currency + ((t  + delivery_charge + restarunt_charge) - discount));
                    $('.total_amount').html(currency + ((t  + delivery_charge + restarunt_charge) - discount));
                    $('.delivery_charge').html(currency + delivery_charge);
                    $('#total_payment').val((t + delivery_charge + restarunt_charge) - discount);
                    orderData.append('coupon_price', discount);
                }
                else
                {
                    var item_total = parseInt($('#price').val());
                    var res = $('.restaurent_charge').text();
                    var restarunt_charge = res.substring(1);
                    var delivery_charge = parseInt($('#hidden_delivery_charge').val());
                    var t = item_total + delivery_charge + parseInt(restarunt_charge);
                    $('#item_total').html(currency + item_total);
                    $('.delivery_charge').html(currency + delivery_charge);
                    $('#to_pay').html(currency + t);
                    $('.total_amount').html(currency + t);
                    $('#total_payment').val(t);
                }
            }
            if(this.value == 'pick up')
            {
                if(c != 0)
                {
                    var item_total = parseInt($('#price').val());
                    var res = $('.restaurent_charge').text();
                    var restarunt_charge = parseInt(res.substring(1));
                    var hidden_coupen_disc_type = $('#hidden_coupen_discountType').val();
                    var hidden_coupen_disc = parseInt($('#hidden_coupen_discount').val());
                    var t = item_total;
                    if(hidden_coupen_disc_type == 'percentage')
                    {
                        var discount = t * (hidden_coupen_disc/100);
                    }
                    if(hidden_coupen_disc_type == 'amount')
                    {
                        var discount = hidden_coupen_disc;
                    }
                    $('#discount').html(currency + discount);
                    $("#saved").text('you have saved ' + currency + discount);
                    $('#to_pay').html(currency + ((t + restarunt_charge) - discount));
                    $('.total_amount').html(currency + ((t + restarunt_charge) - discount));
                    $('.delivery_charge').html(currency + 00);
                    $('#total_payment').val((t + restarunt_charge) - discount);
                    orderData.append('coupon_price', discount);
                }
                else
                {
                    var item_total = parseInt($('#price').val());
                    var res = $('.restaurent_charge').text();
                    var restarunt_charge = res.substring(1);
                    var delivery_charge = parseInt($('#hidden_delivery_charge').val());
                    var t = item_total + parseInt(restarunt_charge);
                    $('.delivery_charge').html(currency + 00);
                    $('#to_pay').html(currency + t);
                    $('.total_amount').html(currency + t);
                    $('#total_payment').val(t);
                }
            }
        }
        else
        {
            var item_total = parseInt($('#price').val());
            var delivery_charge = parseInt($('#hidden_delivery_charge').val());
            var total_amount = parseInt($('#total_payment').val());
            var c = parseInt($('#hidden_coupen_id').val());
            var currency = $('#currency').val();
            if(this.value == 'pick up')
            {
                var t = item_total;
                if(c == 0)
                {
                    $('.to_pay').html(currency + t);
                    $('.total_amount').html(currency+t);
                    $('#total_payment').val(t);
                    $('#price').val(t);
                    $('.delivery_charge').html(currency + 00);
                    $('.to_pay').html(currency + (t));
                }
                else
                {
                    var hidden_coupen_disc_type = $('#hidden_coupen_discountType').val();
                    var hidden_coupen_disc = parseInt($('#hidden_coupen_discount').val());
                    if(hidden_coupen_disc_type == 'percentage')
                    {
                        var discount = t * (hidden_coupen_disc/100);
                    }
                    if(hidden_coupen_disc_type == 'amount')
                    {
                        var discount = hidden_coupen_disc;
                    }
                    $('#discount').html(currency + discount);
                    $("#saved").text('you have saved ' + currency + discount);
                    $('.to_pay').html(currency + (t - discount));
                    $('.total_amount').html(currency + (t - discount));
                    $('.delivery_charge').html(currency + 00);
                    $('#total_payment').val(t - discount);
                    $('#price').val(t);
                    orderData.append('coupon_price', discount);
                }
            }
            if(this.value == 'delivery')
            {
                if(c == 0)
                {
                    var item_total = parseInt($('#price').val());
                    var delivery_charge = parseInt($('#hidden_delivery_charge').val());
                    var total_amount = parseInt($('#total_payment').val());
                    var t = item_total + delivery_charge;
                    $('.delivery_charge').html(currency + delivery_charge);
                    $('.to_pay').html(currency + t);
                    $('.total_amount').html(currency + t);
                    $('#total_payment').val(t);
                    $('#price').val(t);
                }
                else
                {
                    var item_total = parseInt($('#hidden_item_total').val());
                    var delivery_charge = parseInt($('#hidden_delivery_charge').val());
                    var hidden_coupen_disc_type = $('#hidden_coupen_discountType').val();
                    var hidden_coupen_disc = parseInt($('#hidden_coupen_discount').val());
                    if(hidden_coupen_disc_type == 'percentage')
                    {
                        var discount = (item_total) * (hidden_coupen_disc/100);
                    }
                    if(hidden_coupen_disc_type == 'amount')
                    {
                        var discount = hidden_coupen_disc;
                    }
                    $('#discount').html(currency + discount);
                    $("#saved").text('you have saved ' + currency + discount);
                    var total_amount = parseInt($('#total_payment').val());
                    var t = item_total;
                    $('.delivery_charge').html(currency + delivery_charge);
                    $('.to_pay').html(currency + ((t + delivery_charge) - discount));
                    $('.total_amount').html(currency + ((t + delivery_charge) - discount));
                    $('#total_payment').val((t + delivery_charge) - discount);
                    $('#price').val(t);
                    orderData.append('coupon_price', discount);
                }
            }
        }
    });

    $('input[type=radio][name=user_select_address]').change(function ()
    {
        payment = ($('#total_payment').val());
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "post",
            url: url + '/user_select_address',
            data:
            {
                id: this.value,
            },
            success: function (result)
            {
                if(result.success == true)
                {
                   $('.user_select_address').html(result.data);
                   $('#exampleModalCenter').modal('hide');
                }
            },
            error: function (err) {
                console.log('err ', err)
            }
        });
    });

    $("#select_payment_method").click(function ()
    {
        var pay = $('#to_pay').text();
        var to_pay = pay.trim().substring(1);
        if($('#address_id').val() == null)
        {
            swal('Please First Add Any address..!!');
            $('#exampleModalCenter').modal('show');
        }
        else
        {
            $('.payment_method').toggle();
        }
    });

    if($('#hidden_cuurency_type').val() != 'INR')
    {
        var pay = $('#to_pay').text();
        var to_pay = pay.trim().substring(1);
        paypal_sdk.Buttons({
            createOrder: function (data, actions)
            {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: to_pay
                        }
                    }]
                });
            },
            onApprove: function (data, actions)
            {
                return actions.order.capture().then(function (details)
                {
                    orderData.append('payment_status', 1);
                    orderData.append('payment_token', details.id);
                    orderData.append('payment_type', 'PAYPAL');
                    confirm_order();
                });
            }
        }).render('.paypal_payment');
    }

    if($('#hidden_cuurency_type').val() == 'INR')
    {
        payment = ($('#total_payment').val());
        var to_pay = $('#to_pay').text().trim().substring(1);
        var options =
        {
            key: $('#RAZORPAY_KEY').val(),
            amount: to_pay * 100,
            name: 'FoodLans',
            description: '',
            currency: $('#hidden_cuurency_type').val(),
            image: 'https://i.imgur.com/n5tjHFD.png',
            handler: demoSuccessHandler
        }
        window.r = new Razorpay(options);
        $('#paypal').hide(500);
        $('#razorpay').show(500);
        $('#stripe').hide(500);
        document.getElementById('paybtn').onclick = function ()
        {
            var to_pay = $('#to_pay').text().trim().substring(1);
            r.open();
        }
    }

    // $('.payment_type input[type=radio][name=payment]').change(function ()
    // {
    //     orderData.append('payment_type', this.value);
    //     if (this.value == 'PAYPAL') {
    //         $('.paypal_payment').html('');
    //         $('#paypal').show(500);
    //         $('#razorpay').hide(500);
    //         $('#stripe').hide(500);
    //         var pay = $('#to_pay').text();
    //         var to_pay = pay.trim().substring(1);
    //         paypal_sdk.Buttons({
    //             createOrder: function (data, actions) {
    //                 return actions.order.create({
    //                     purchase_units: [{
    //                         amount: {
    //                             value: to_pay
    //                         }
    //                     }]
    //                 });
    //             },
    //             onApprove: function (data, actions) {
    //                 return actions.order.capture().then(function (details) {
    //                     orderData.append('payment_status', 1);
    //                     orderData.append('payment_token', details.id);
    //                     confirm_order();
    //                 });
    //             }
    //         }).render('.paypal_payment');
    //     }

    //     else if (this.value == 'RAZOR')
    //     {
    //         $('#paypal').hide(500);
    //         $('#razorpay').show(500);
    //         $('#stripe').hide(500);
    //         var to_pay = $('#to_pay').text().trim().substring(1);
    //         console.log('to_pay click',to_pay);
    //         var options =
    //         {
    //             key: $('#RAZORPAY_KEY').val(),
    //             amount: to_pay * 100,
    //             name: 'FoodLans',
    //             currency: $('#hidden_cuurency_type').val(),
    //             description: '',
    //             image: 'https://i.imgur.com/n5tjHFD.png',
    //             handler: demoSuccessHandler
    //         }
    //         window.r = new Razorpay(options);
    //         document.getElementById('paybtn').onclick = function ()
    //         {
    //             var to_pay = $('#to_pay').text().trim().substring(1);
    //             r.open();
    //         }
    //     }
    //     else {
    //         $('#paypal').hide(500);
    //         $('#razorpay').hide(500);
    //         $('#stripe').show(500);
    //     }
    // });
});

function online_payment()
{
    $('.online_payment').show(500);
    $('.offline').hide(500);
}

function applycoupen(use_for)
{
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: url + '/apply_coupen',
        data:
        {
            coupen_value: $('#coupen').val(),
            price: $('#price').val(),
            use_for: use_for
        },
        success: function (result)
        {
            console.log("result",result);
            if (result.success == true)
            {
                $('.remove_coupen_col').html('<button class="text-danger btn remove_coupen" onclick="remove_coupen()">Remove coupen</button>');
                $('#saved').show();
                $('#hidden_coupen_id').val(result.data.coupen_id);
                $('#hidden_coupen_discount').val(result.data.discount);
                $('#hidden_coupen_discountType').val(result.discountType);
                $('#hidden_coupen_use_for').val(result.use_for);
                if(result.use_for == 'Food')
                {
                    var restarunt_charge = parseInt($('#hidden_rastaurant_charge').val());
                    if(result.discountType == 'percentage')
                    {
                        var d = parseInt(result.data.discount);
                        var final = parseInt(result.data['final']);
                        var discount = (final) * (d/100);
                        var to_pay  = (final + restarunt_charge) - discount;
                        console.log('final + restarunt_charge',final + restarunt_charge);
                        $("#to_pay").text(result.currency + to_pay);
                        $('#total_payment').val(to_pay);
                        $("#saved").text('you have saved ' + result.currency + ((final) - to_pay));
                        $('.total_amount').text(result.currency + to_pay);
                        $("#discount").text(result.currency+discount);
                        orderData.append('coupon_price', discount);
                    }
                    else
                    {
                        var d = parseInt(result.data.discount);
                        var final = parseInt(result.data['final']);
                        var discount = d;
                        var to_pay  = (final + restarunt_charge) - discount;
                        $("#to_pay").text(result.currency + to_pay);
                        $('#total_payment').val(to_pay);
                        $("#saved").text('you have saved ' + result.currency + ((final) - to_pay));
                        $('.total_amount').text(result.currency + to_pay);
                        $("#discount").text(result.currency+discount);
                        orderData.append('coupon_price', discount);
                    }
                    if($('input[type=radio][name=delivery_type]:checked').val() == 'delivery')
                    {
                        if(result.discountType == 'percentage')
                        {
                            var delivery_charge = parseInt($('#hidden_delivery_charge').val());
                            var d = parseInt(result.data.discount);
                            var final = parseInt(result.data['final']);
                            var discount = (final) * (d/100);
                            var to_pay  = (final + delivery_charge + restarunt_charge) - discount;
                            console.log('to_pay',to_pay);
                            $("#to_pay").text(result.currency + to_pay);
                            $('#total_payment').val(to_pay);
                            $("#saved").text('you have saved ' + result.currency + discount);
                            $('.total_amount').text(result.currency + to_pay);
                            $("#discount").text(result.currency+discount);
                            orderData.append('coupon_price', discount);
                        }
                        else
                        {
                            var delivery_charge = parseInt($('#hidden_delivery_charge').val());
                            var d = parseInt(result.data.discount);
                            var final = parseInt(result.data['final']);
                            var discount = d;
                            var to_pay  = (final + delivery_charge) - discount;
                            $("#to_pay").text(result.currency + to_pay);
                            $('#total_payment').val(to_pay);
                            $("#saved").text('you have saved ' + result.currency + discount);
                            $('.total_amount').text(result.currency + to_pay);
                            $("#discount").text(result.currency+discount);
                            orderData.append('coupon_price', discount);
                        }
                    }
                }
                else
                {
                    if(result.discountType == "percentage")
                    {
                        var delivery_charge = parseInt($('#hidden_delivery_charge').val());
                        var d = parseInt(result.data.discount);
                        var final = parseInt(result.data['final']);
                        var discount = (final) * (d/100);
                        var to_pay  = (final) - discount;
                        $("#to_pay").text(result.currency + to_pay);
                        $('#total_payment').val(to_pay);
                        $("#saved").text('you have saved ' + result.currency + ((final) - to_pay));
                        $('.total_amount').text(result.currency + to_pay);
                        $("#discount").text(result.currency+discount);
                        orderData.append('coupon_price', discount);
                    }
                    else
                    {
                        var d = parseInt(result.data.discount);
                        var final = parseInt(result.data['final']);
                        var discount = result.data.discount;
                        var to_pay  = final - discount;
                        $("#to_pay").text(result.currency + to_pay);
                        $('#total_payment').val(to_pay);
                        $("#saved").text('you have saved ' + result.currency + ((final) - to_pay));
                        $('.total_amount').text(result.currency + to_pay);
                        $("#discount").text(result.currency+discount);
                        orderData.append('coupon_price', discount);
                    }
                    if($('input[type=radio][name=delivery_type]:checked').val() == 'delivery')
                    {
                        if(result.discountType == 'percentage')
                        {
                            var delivery_charge = parseInt($('#hidden_delivery_charge').val());
                            var d = parseInt(result.data.discount);
                            var final = parseInt(result.data['final']);
                            var discount = (final) * (d/100);
                            var to_pay  = (final + delivery_charge) - discount;
                            $('.delivery_charge').val(result.data.currency + delivery_charge);
                            $("#to_pay").text(result.currency + to_pay);
                            $('#total_payment').val(to_pay);
                            $("#saved").text('you have saved ' + result.currency + ((final+delivery_charge) - to_pay));
                            $('.total_amount').text(result.currency + to_pay);
                            $("#discount").text(result.currency+discount);
                            orderData.append('coupon_price', discount);
                        }
                        else
                        {
                            var delivery_charge = parseInt($('#hidden_delivery_charge').val());
                            var d = parseInt(result.data.discount);
                            var final = parseInt(result.data['final']);
                            var discount = parseInt(result.data.discount);
                            var to_pay  = (final+delivery_charge) - discount;
                            $('.delivery_charge').val(result.data.currency + delivery_charge);
                            $("#to_pay").text(result.currency + to_pay);
                            $('#total_payment').val(to_pay);
                            $("#saved").text('you have saved ' + result.currency + ((final+delivery_charge) - to_pay));
                            $('.total_amount').text(result.currency + to_pay);
                            $("#discount").text(result.currency+discount);
                            orderData.append('coupon_price', discount);
                        }
                    }
                }
                orderData.append('coupon_id', parseInt(result.data.coupen_id));
                orderData.append('discount',parseInt($('#hidden_coupen_discount').val()));
            }
            else
            {
                $('#coupen').val('');
                swal(result.data);
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

$(function () {
    $form = $(".require-validation");
    $('form.require-validation').bind('submit', function (e) {
        $form = $(".require-validation"),
            inputSelector = ['input[type=email]', 'input[type=password]',
                'input[type=text]', 'input[type=file]',
                'textarea'].join(', '),
            $inputs = $form.find('.required').find(inputSelector),
            $errorMessage = $form.find('div.error'),
            valid = true;
        $errorMessage.addClass('hide');

        $('.has-error').removeClass('has-error');
        $inputs.each(function (i, el) {
            var $input = $(el);
            if ($input.val() === '') {
                $input.parent().addClass('has-error');
                $errorMessage.removeClass('hide');
                e.preventDefault();
            }
        });
        $('.stripe-error').css('display', 'none')
        var month = $('.expiry-date').val().split('/')[0];
        var year = $('.expiry-date').val().split('/')[1];
        $('.card-expiry-month').val(month);
        $('.card-expiry-year').val(year);
        if (!$form.data('cc-on-file'))
        {
            e.preventDefault();
            Stripe.setPublishableKey($form.data('stripe-publishable-key'));
            var ccNum = $('.card-number').val(),
            cvcNum = $('.card-cvc').val(),
            expMonth = $('.card-expiry-month').val(),
            expYear = $('.card-expiry-year').val();

            if (!Stripe.card.validateCardNumber(ccNum)) {
                $('.stripe-error').css('display', 'inline')
                $('.stripe-error')
                .removeClass('hide')
                .text('The credit card number appears to be invalid.');

            }

            if (!Stripe.card.validateCVC(cvcNum)) {
                $('.stripe-error').css('display', 'inline')
                $('.stripe-error')
                .removeClass('hide')
                .text('The CVC number appears to be invalid.');
            }

            if (!Stripe.card.validateExpiry(expMonth, expYear)) {
                $('.stripe-error').css('display', 'inline')
                $('.stripe-error')
                .removeClass('hide')
                .text('The expiration date appears to be invalid.');
            }

            Stripe.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);
        }
    });
});

function stripeResponseHandler(status, response) {
    console.log('res ', response)
    if (response.error) {
        $('.error')
            .removeClass('hide')
            .find('.alert')
            .text(response.error.message);
    }
    else
    {
        var token = response['id'];
        $form.find('input[type=text]').empty();
        $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
        var paymentData = new FormData($('#stripe-payment-form')[0]);
        paymentData.append('payment');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: 'frontendStripePayment',
            data: paymentData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (result) {
                console.log('result', result)
                if (result.success == true)
                {
                    orderData.append('payment_status', 1);
                    orderData.append('payment_token', result.data);
                    confirm_order();
                }
                else {
                    swal('payment not complete');
                }
            },
            error: function (err) {
                console.log(err);
                swal(err.responseJSON.message);
            }
        });

    }
}

function case_on_delivery() {
    $('.online_payment').hide(500);
    $('.offline').show(500);
}

function remove_coupen()
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url + '/remove_coupen',
        data:
        {
        },
        success: function (result)
        {
            if (result.success == true)
            {
                $('#coupen').val('');
                $('.remove_coupen').hide();
                if($('input[type=radio][name=delivery_type]:checked').val() == 'delivery')
                {
                    console.log('result',result);
                    console.log('result',result.data.final_price);
                    console.log('result',result.data.deilvery_charge);
                    $('#item_total').val(result.data.currency + parseInt(result.data.final_price));
                    $('#to_pay').text(result.data.currency + parseInt(result.data.final_price + result.data.deilvery_charge + result.data.finalWithResturantCharge));
                    $('.total_amount').text(result.data.currency + parseInt(result.data.final_price + result.data.deilvery_charge + result.data.finalWithResturantCharge));
                    $('#total_payment').val(parseInt(result.data.final_price + result.data.deilvery_charge + result.data.finalWithResturantCharge));
                    $('#discount').text(result.data.currency + 00);
                    $('#saved').hide();
                    orderData.delete('coupon_id');
                    orderData.delete('coupon_price');
                    orderData.delete('discount');
                }
                else
                {
                    $('#item_total').val(result.data.currency + parseInt(result.data.final_price));
                    $('#to_pay').text(result.data.currency + result.data.final_price + result.data.finalWithResturantCharge);
                    $('.total_amount').text(result.data.currency + parseInt(result.data.final_price + result.data.finalWithResturantCharge));
                    $('#total_payment').val(parseInt(result.data.final_price + result.data.finalWithResturantCharge));
                    $('#discount').text(result.data.currency + 00);
                    $('#saved').hide();
                    orderData.delete('coupon_id');
                    orderData.delete('coupon_price');
                    orderData.delete('discount');
                }
                $('#hidden_coupen_id').val(0);
                $('#hidden_coupen_discount').val(0);
                $('#hidden_coupen_discountType').val(0);
            }
            else {
                swal({
                    text: result.data,
                });
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function confirm_order()
{
    var to_pay = $('#to_pay').text().trim().substring(1);
    orderData.append('payment',to_pay);
    orderData.append('address_id',$('#address_id').val());
    orderData.append('delivery_type',$("input[name='delivery_type']:checked").val());
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        url: url + '/confirm_order',
        data: orderData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (result)
        {
            if(result.success == true)
            {
                if(result.url != undefined)
                {
                    window.open(result.url,'_self');
                }
                else
                {
                    swal({
                        text: "Payment successfully!",
                        icon: "success",
                    });
                    location.replace('/user_details')
                }
            }
            else
            {
                swal('Please First Add Any address..!!')
                $('#exampleModalCenter').modal('show');
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function padStart(str) {
    return ('0' + str).slice(-2)
}

function demoSuccessHandler(transaction)
{
    $("#paymentDetail").removeAttr('style');
    $('#paymentID').text(transaction.razorpay_payment_id);
    var paymentDate = new Date();
    $('#paymentDate').text
    (
        padStart(paymentDate.getDate()) + '.' + padStart(paymentDate.getMonth() + 1) + '.' + paymentDate.getFullYear() + ' ' + padStart(paymentDate.getHours()) + ':' + padStart(paymentDate.getMinutes())
    );
    orderData.append('payment_token', transaction.razorpay_payment_id);
    orderData.append('payment_status', 1);
    confirm_order();
}

function flutterwave()
{
    confirm_order();
}

var paymentForm = document.getElementById('paymentForm');
paymentForm.addEventListener('submit', payWithPaystack, false);
function payWithPaystack()
{
    var amount = $('#to_pay').text().trim().substring(1);
    var handler = PaystackPop.setup(
    {
        key: $('#paystack-public-key').val(),
        email: document.getElementById('email-address').value,
        amount: amount * 100,
        currency: $('#hidden_cuurency_type').val(),
        ref: Math.floor(Math.random() * (999999 - 111111)) + 999999,
        callback: function (response)
        {
            orderData.append('payment_status', 1);
            orderData.append('payment_token', response.reference);
            orderData.append('payment_type', 'PAYSTACK');
            confirm_order();
        },
        onClose: function ()
        {
            alert('Transaction was not completed, window closed.');
        },
    });
    handler.openIframe();
}

function paytm()
{
    $.ajax(
    {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "get",
        url: url + '/paytmPayment',

        success: function (result)
        {
            console.log('res',result);
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}
