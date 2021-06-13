var url = $('#mainurl').val();

$(function ()
{
    setTimeout(() => {
        $("#cooking").fadeOut()
        $('.foodhub_preloader_holder').css('display','none');
    }, 2000);
})

$(document).ready(function()
{
    $('.scrollContainer').each(function() {
        // console.log('$(this)[0] ', $(this)[0])
        const ps = new PerfectScrollbar(
            $(this)[0], {
            suppressScrollY: true
        });
    });

    $(".remove_cart_alert").delay(3500).slideUp(300);
    $('.forgot_password_alert').delay(3500).slideUp(300);
    $(".order_history").click(function () {
        $('.show_order_history').show(500);
        $('.order_history').addClass('activeItem');
        $('.Review').removeClass('activeItem');
        $('.show_Review').hide(500);

    });

    $("#file-input").on('change', function (e)
    {
        var dataimg = new FormData();
        dataimg.append('image', $("#file-input")[0].files[0]);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url + '/update_cover_image',
            type:"post",
            cache : false,
            processData: false,
            contentType: false,
            data: dataimg,
            success: function(data) {
                location.reload();
            }
        });
    });

    $(".Review").click(function ()
    {
        $('.show_order_history').hide(500);
        $('.order_history').removeClass('activeItem');
        $('.show_Review').show(500);
        $('.Review').addClass('activeItem');
    });

    $(".search_grocery").keyup(function(){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: url + '/search_grocery',
            data:
            {
                value: $('.search_grocery').val(),
                id:$('#grocery_shop').val()
            },
            success: function (result) {
                console.log('result', result);
                if (result.success == true) {
                    $('.cartItem').html(result.data);
                }
                else
                {
                    swal(result.data);
                }
            },
            error: function (err) {
                console.log('err ', err)
            }
        });
    });

    $(".grocery_store_search").keyup(function()
    {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: url + '/grocery_store_search',
            data:
            {
                value: $('.grocery_store_search').val(),
            },
            success: function (result) {
                console.log('result', result);
                if (result.success == true) {
                    $('.grocery_data').html(result.data);
                }
                else
                {
                    swal(result.data);
                }
            },
            error: function (err) {
                console.log('err ', err)
            }
        });
    });

    $(".grocery_item_search").keyup(function()
    {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: url + '/grocery_item_search',
            data:
            {
                value: $('.grocery_item_search').val(),
            },
            success: function (result) {
                console.log('result', result);
                if (result.success == true) {
                    $('.grocery_Item_data').html(result.data);
                }
                else
                {
                    swal(result.data);
                }
            },
            error: function (err) {
                console.log('err ', err)
            }
        });
    });

    $(".filter").click(function (event)
    {
        event.stopPropagation();
        $('.filter_card').toggle();
    });

    $(".grocery_filter").click(function (event)
    {
        event.stopPropagation();
        $('.grocary_filter_card').toggle();
    });

    $(document).click(function (e)
    {
        if (!$(e.target).closest('.checkbox-button').length)
        {
            $(".filter_card").hide();
            $(".grocary_filter_card").hide();
        }
    });

    $(".login_button").click(function ()
    {
        $('#exampleModalCenter').modal(
            'hide');
    });
    $(".sign_up_button").click(function () {
        $('#register').modal('hide');
    });

    $('.edit_profile_item').on('click', function () {
        $('.edit_profile').show();
        $('.edit_profile_item').addClass('SettingItemColor');
        $('.notification_item').removeClass('SettingItemColor');
        $('.location_item').removeClass('SettingItemColor');
        $('.notification').hide();
        $('.location').hide();

    });

    $('.notification_item').on('click', function () {
        $('.edit_profile').hide();
        $('.notification').show();
        $('.edit_profile_item').removeClass('SettingItemColor');
        $('.notification_item').addClass('SettingItemColor');
        $('.location_item').removeClass('SettingItemColor');
        $('.location').hide();
    });

    $('.location_item').on('click', function () {
        $('.edit_profile').hide();
        $('.location').show();
        $('.edit_profile_item').removeClass('SettingItemColor');
        $('.notification_item').removeClass('SettingItemColor');
        $('.location_item').addClass('SettingItemColor');
        $('.notification').hide();
    });

    $('.switch input[type=checkbox][name=notification]').change(function () {
        if ($(this).is(':checked')) {
            var checkbox = "checked";
        }
        else {
            var checkbox = "unchecked";
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: url + '/notification',
            data:
            {
                check: checkbox,
            },
            success: function (result) {
                console.log('result', result)
                if (result.success == true) {
                }
            },
            error: function (err) {
                console.log('err ', err)
            }
        });

    });
});

function setFilter(event, type) {

    event.stopPropagation();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: url + '/filter',
        data:
        {
            check: type,
        },
        success: function (result) {
            if (result.success == true)
            {
                $('#resultData').html(result.data);
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });

}

function setGroceryFilter(event,type)
{
    event.stopPropagation();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: url + '/grocery_filter',
        data:
        {
            check: type,
        },
        success: function (result) {
            console.log('result', result);
            if (result.success == true)
            {
                $('.grocery_data').html(result.data);
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}


function itemfilter(type)
{
    // alert($('#shop_id').val());
    // if ($('#chkVeg').prop('checked') || $('#chkNonVeg').prop('checked') || $('#chkAll').prop('checked'))
    // {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: url + '/itemfilter',
            data:
            {
                shop_id: $('#shop_id').val(),
                type: type,
            },
            success: function (result) {
                if (result.success == true) {
                    $('#data').html(result.data);
                }
                else {
                    swal({
                        text: result.data,
                    });
                }
                console.log('result', result)
            },
            error: function (err) {
                console.log('err ', err)
            }
        });
    // }
}


function addtocart(id, price, type)
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: url + '/add_cart',
        data:
        {
            id: id,
            price: price,
            type: type
        },
        success: function (result) {
            console.log('result', result)
            if (result.success == true)
            {
                swal({
                    text: 'Product Added to Cart Successfully..',
                });
                console.log('type ' , type)
                if(type == 'item'){

                    $('#addcartitem'+id).css('display', 'none');
                    $('.sessionUpdateitem'+id).html(result.data);
                } else {
                    $('#addcartcombo'+id).css('display', 'none');
                    $('.sessionUpdatecombo'+id).html(result.data);
                }
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

function user_login()
{
    var email = $('#email').val();
    var password = $('#password').val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: url + '/user_login',
        data:
        {
            email: email,
            password: password,
        },
        success: function (result) {
            if (result.success == true)
            {
                window.location.reload();
            }
            else
            {
                $('.form-group .user_email').html(result.message);
            }
        },
        error: function (err) {
            console.log('err', err)
            for (let v1 of Object.keys(err.responseJSON.errors))
            {
                $("."+v1).html(Object.values(err.responseJSON.errors[v1]));
                if(v1 == 'email')
                {
                    $(".user_email").html(Object.values(err.responseJSON.errors[v1]));
                }
                if(v1 == 'password')
                {
                    $(".user_password").html(Object.values(err.responseJSON.errors[v1]));
                }
            }
        }
    });
}

function clearValidation(){
    console.log('hello there!')
    $('#user_register_form .password').html('');
    $('form .user_password').html('');
}
function user_register()
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: url + '/user_register',
        data:
        {
            name:$('#name').val(),
            email:$('#register_email').val(),
            password:$('#register_password').val(),
            phone:$('#phone').val(),
        },
        success: function (result)
        {
            if (result.success)
            {
                $('#exampleModalCenter').modal('show');
                $('#register').modal('hide');
                document.getElementById('user_register_form').reset();
                $('.name').html('');
                $('.email').html('');
                $('.password').html('');
                $('.phone').html('');
            }
            console.log('result', result)
        },
        error: function (err) {
            console.log('err ', err)
            for (let v1 of Object.keys( err.responseJSON.errors)) {
                console.log(v1);
                $("."+v1).html(Object.values(err.responseJSON.errors[v1]));
                $(".user_password").html('');
            }
        }
    });
}

function update_cart(id, type, operation)
{
    var name = parseInt($('#qty' + id).val());
    var price = parseInt($('#price' + id).val());
    var original_price = parseInt($('#original_price' + id).val());
    if (operation == 'plus')
    {
        $('.minus'+id).prop('disabled', false);
        name = name + 1;
        price = original_price + price;
        $('#qty' + id).val(name);
        $('#price' + id).val(price);
    }
    else
    {
        if (name == 1)
        {
            $('.minus'+id).prop('disabled', true);
        }
        else {
            $('.minus').prop('disabled', false);
            name = name - 1;
            price = price - original_price;
            $('#qty' + id).val(name);
            $('#price' + id).val(price);
        }
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: url + '/update_cart',
        data:
        {
            id: id,
            type: type,
            operation: operation,
            qty: name,
            price: price
        },
        success: function (result)
        {
            if (result.success == true)
            {
                swal({
                    text: result.message,
                });
                var hidden_coupen = $('#hidden_coupen_id').val();
                $('.order_list_price').html(result.data.price);
                if(hidden_coupen != 0)
                {
                    if(result.data.use_for == 'Food')
                    {
                        var restarunt_charge = parseInt($('#hidden_rastaurant_charge').val());
                        if(result.data.discountType == 'percentage')
                        {
                            var d = parseInt(result.data.discount);
                            var final = parseInt(result.data.to_pay);
                            var discount = (final) * (d/100);
                            var to_pay  = (final + restarunt_charge) - discount;
                            $('#item_total').html(result.data.currency + result.data.price);
                            $("#to_pay").html(result.data.currency + to_pay);
                            $('#total_payment').val(to_pay);
                            $("#saved").text('you have saved ' + result.currency + parseInt(discount));
                            $('.total_amount').text(result.data.currency + to_pay);
                            $("#discount").text(result.data.currency+discount);
                            $('#price').val(result.data.price);
                            orderData.append('coupon_price', ((final) - to_pay));
                        }
                        else
                        {
                            var d = parseInt(result.data.discount);
                            var final = parseInt(result.data.to_pay);
                            var discount = d;
                            var to_pay  = (final + restarunt_charge) - discount;
                            $("#to_pay").html(result.data.currency + to_pay);
                            $('#item_total').html(result.data.currency + result.data.price);
                            $('#total_payment').val(to_pay);
                            $("#saved").text('you have saved ' + result.currency + ((final) - to_pay));
                            $('.total_amount').text(result.data.currency + to_pay);
                            $("#discount").text(result.data.currency+discount);
                            $('#price').val(result.data.price);
                            orderData.append('coupon_price', ((final) - to_pay));
                        }
                        if($('input[type=radio][name=delivery_type]:checked').val() == 'delivery')
                        {
                            if(result.data.discountType == 'percentage')
                            {
                                var delivery_charge = parseInt($('#hidden_delivery_charge').val());
                                var d = parseInt(result.data.discount);
                                var final = parseInt(result.data.to_pay);
                                var discount = (final) * (d/100);
                                var to_pay  = (final + restarunt_charge + delivery_charge) - discount;
                                console.log('to_pay',to_pay);
                                $("#to_pay").html(result.data.currency + to_pay);
                                $('#item_total').html(result.data.currency + result.data.price);
                                $('#total_payment').val(to_pay);
                                $('#price').val(result.data.price);
                                $("#saved").text('you have saved ' + result.data.currency + discount);
                                $('.total_amount').text(result.data.currency + to_pay);
                                $("#discount").text(result.data.currency+discount);
                            }
                            if(result.data.discountType == 'amount')
                            {
                                var delivery_charge = parseInt($('#hidden_delivery_charge').val());
                                var d = parseInt(result.data.discount);
                                var final = parseInt(result.data.to_pay);
                                var discount = d;
                                var to_pay  = (final + restarunt_charge + delivery_charge) - discount;
                                $("#to_pay").html(result.data.currency + to_pay);
                                $('#item_total').html(result.data.currency + result.data.price);
                                $('#total_payment').val(to_pay);
                                $("#saved").text('you have saved ' + result.data.currency + discount);
                                $('.total_amount').text(result.data.currency + to_pay);
                                $("#discount").text(result.data.currency+discount);
                                $('#price').val(result.data.price);
                            }
                        }
                    }
                }
                else
                {
                    if($('input[type=radio][name=delivery_type]:checked').val() == 'delivery')
                    {
                        var delivery_charge = parseInt($('#hidden_delivery_charge').val());
                        $('#price').val(result.data.price);
                        $('.price').html(result.data.price) + delivery_charge;
                        $('#item_total').html(result.data.currency + result.data.price);
                        $('#to_pay').text(result.data.currency + (parseInt(result.data.to_pay) + parseInt(delivery_charge)));
                        $('.total_amount').html(result.data.currency + (parseInt(result.data.to_pay) + parseInt(delivery_charge)));
                        $('#total_payment').val((parseInt(result.data.to_pay) + parseInt(delivery_charge)));
                    }
                    else
                    {
                        $('#price').val(result.data.price);
                        $('.price').html(result.data.price);
                        $('#item_total').html(result.data.currency + result.data.price);
                        $('#to_pay').text(result.data.currency + result.data.to_pay);
                        $('.total_amount').html(result.data.currency + result.data.to_pay);
                        $("#discount").html(result.data.currency + result.data.discount);
                        $('#total_payment').val(result.data.to_pay);
                    }
                }
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function user_address()
{
    if ($('#set_as_default').is(":checked")) {
        var a = "checked";
    }
    else {
        var a = "unchecked";
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        url: url + '/user_address',
        data:
        {
            address_type: $('#address_type').val(),
            soc_name: $('#soc_name').val(),
            street: $('#street').val(),
            city: $('#city').val(),
            zipcode: $('#zipcode').val(),
            lat: $('#lat').val(),
            lang: $('#lang').val(),
            set_as_default: a,
        },
        success: function (result) {
            if (result.success) {
                $(".display_user_address").append("<div><img src='/image/icon/Icon awesome-map-marker-alt.png'>" + result.data.soc_name + ',' + result.data.street + ',' + "&nbsp;" + result.data.city + ',' + "&nbsp;" + result.data.zipcode + "</div>");
                $('#exampleModalCenter').modal('hide');
                swal('Address Inserted successfully..!!');
                location.reload();
            }
            else {
                swal(result.data);
            }
            console.log('result', result)
        },
        error: function (err) {
            console.log('err', err)
            for (let v1 of Object.keys( err.responseJSON.errors))
            {
                console.log(v1)
                $("."+v1).html(Object.values(err.responseJSON.errors[v1]));
            }
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

        var month = $('.expiry-date').val().split('/')[0];
        var year = $('.expiry-date').val().split('/')[1];
        $('.card-expiry-month').val(month);
        $('.card-expiry-year').val(year);

        if (!$form.data('cc-on-file')) {
            e.preventDefault();
            Stripe.setPublishableKey($form.data('stripe-publishable-key'));
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
        paymentData.append('payment', 100);

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
                if (result.success == true) {
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

function update_profile() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        url: url + '/update_profile',
        data:
        {
            name: $('#name').val(),
            phone: $('#phone').val(),
            email: $('#email').val(),
        },
        success: function (result) {
            swal({
                text: "Update",
            });
            console.log('result', result)
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function update_address() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        url: url + '/update_user_address',
        data:
        {
            name: $('#name').val(),
            phone: $('#phone').val(),
            email: $('#email').val(),
        },
        success: function (result) {
            console.log('result', result)
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function display_category(id, element)
{
    $('.grocery_navbar ul li').children('a').each((index, el) => {
        if($(el).hasClass('active_link')){
            $(el).removeClass('active_link')
        }
    })
    $(element).addClass('active_link');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        url: url + '/display_category',
        data:
        {
            id: id,
        },
        success: function (result)
        {
            $(".grocery_item").html('');
            if (result.success == true)
            {
                console.log(result.data);
                $(".grocery_item").html(result.data.groceryItem);
                $(".category_name").html(result.data.category_name)
            }
            else {
                $(".grocery_item").html(result.data.groceryItem);
                $(".category_name").html(result.data.category_name);
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function addbookmark() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        url: url + '/addBookmark',
        data:
        {
            id: $('#shop_id').val(),
        },
        success: function (result) {
            console.log('result', result)
            if (result.success == true) {
                swal({
                    text: result.data,
                });
            }
            else
            {
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

function edit_address(id) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        url: url + '/edit_address',
        data:
        {
            id: id,
        },
        success: function (result) {
            console.log('result', result)
            if (result.success == true)
            {
                $(".edit_user_address").show();
                $(".edit_user_address").html(result.data);
                loadAddressMap();
            }
            else {

            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}
function update_address_setting()
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        url: url + '/update_address',
        data:
        {
            id: $('#id').val(),
            lat: $('#lat').val(),
            lang: $('#lang').val(),
            soc_name: $('#soc_name').val(),
            address_type: $("input[name='address_type']:checked").val(),
            default_address: $("input[name='default_address']:checked").val(),
            street: $('#street').val(),
            city: $('#city').val(),
            zipcode: $('#zipcode').val()
        },
        success: function (result) {
            console.log('result', result)
            if (result.success == true) {
                swal({
                    text: "Edit address successfully..!!",
                });
                $("#address_id" + result.data.id).html
                (
                    "<div><img src='/image/icon/Icon awesome-map-marker-alt.png'>"
                    + result.data.soc_name
                    + ','
                    + result.data.street + ','
                    + "&nbsp;" + result.data.city + ','
                    + "&nbsp;" + result.data.zipcode + "</div>"
                    + "<img src='/image/icon/edit_address.png' onclick='edit_address('" + result.data.id + "')' class='edit_address' alt=''>"
                    + "<img src='/image/icon/delete.png' onclick='delete_address('" + result.data.id + "')' class='delete_address float-right' alt=''>"
                    + "<hr>"
                );
            }
            else {
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function delete_address(id) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        url: url + '/delete_address',
        data:
        {
            id: id,
        },
        success: function (result) {
            console.log('result', result)
            if (result.success == true) {
                swal({
                    text: result.message,
                });
                $('.display_user_address').html(result.data);
            }
            else {

            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function makeRating(ele, rate) {
    $(ele + ' .ratings i').each(function (index, element) {
        setTimeout(() => {

            if (rate > index) {
                $(element).removeClass('fa fa-star-o');
                $(element).addClass('fa fa-star');
            }
            else {
                $(element).removeClass('fa fa-star');
                $(element).addClass('fa fa-star-o');
            }
        }, 100);
    })
}

function add_review(ele,id) {
    console.log('ele ', ele)
    var star = [];
    $(ele + ' .ratings i').each(function (index, value) {
        console.log('value ', value)
        if ($(value).hasClass('fa-star')) {
            star.push(index + 1);
        }
    });
    console.log(star);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        url: url + '/add_review',
        data:
        {
            message: $('#message' + id).val(),
            shop_id: $('#shop_id' + id).val(),
            order_id: $('#order_id' + id).val(),
            rate: star.length
        },
        success: function (result) {
            console.log('result', result)
            if (result.success == true)
            {
                $('#exampleModalCenter'+id).modal('hide');
                $('.add_review'+id).hide();
                $('.display_review'+id).html(result.reviewString);
                swal({
                    text: "This review made our day...!!!",
                });
            }
            else {
                $('#exampleModalCenter'+id).modal('hide');
                swal(result.data);
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function add_grocery_review(ele,id)
{
    var star = [];
    $(ele + ' .ratings i').each(function (index, value) {
        console.log('value ', value)
        if ($(value).hasClass('fa-star')) {
            star.push(index + 1);
        }
    });
    console.log(star);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        url: url + '/add_grocery_review',
        data:
        {
            message: $('#message' + id).val(),
            shop_id: $('#shop_id' + id).val(),
            order_id: $('#order_id' + id).val(),
            rate: star.length
        },
        success: function (result) {
            console.log('result', result)
            if (result.success == true)
            {
                $('#orderModalCenter'+id).modal('hide');
                $('.add_review'+id).hide();
                $('.display_review'+id).html(result.reviewString);
                swal({
                    text: "This review made our day...!!!",
                });
            }
            else {
                $('#orderModalCenter'+id).modal('hide');
                swal(result.data);
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}
function cancel_order(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this order!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "post",
            url: url + '/cancel_order',
            data:
            {
                order_id: $('#order_id' + id).val(),
            },
            success: function (result) {
                if (result.success == true) {
                    Swal.fire(
                        'Deleted!',
                        'Your Order has been deleted.',
                        'success'
                    )
                }
            }
        });
    })
}

function search()
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        url: url + '/search',
        data:
        {
            lat: 22.3039,
            lang: 70.8022,
            search_meal: $('#search_meal').val(),
            address: $('#address').val()
        },
        success: function (result) {
            console.log('result', result)
            if (result.success == true) {
                $('.search').html(result.data);
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

function add_grocery_cart(id,operation)
{
    var qty = parseInt($('#qty' + id).val());
    var price = parseInt($('#price' + id).val());
    var original_price = parseInt($('#original_price' + id).val());
    if (operation == 'plus')
    {
        $('.minus'+id).prop('disabled', false);
    }
    else
    {
        if (qty == 1)
        {
            $('.minus'+id).prop('disabled', true);
        }
        else
        {
            $('.minus'+id).prop('disabled', false);
        }
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: url + '/add_grocery_cart',
        data:
        {
            id: id,
            price: price,
            operation: operation,
            qty:1,
        },
        success: function (result)
            {
            console.log('result', result)
            if (result.success == true)
            {
                swal({
                text: result.message,
                });
                $('#qty' + id).val(result.data.qty);
                $('#price' + id).val(result.data.price);
                $('.price').html(result.data.final_price);
                var hidden_coupen = $('#hidden_coupen_id').val();
                if(hidden_coupen != 0)
                {
                    if(result.data.discountType == 'percentage')
                    {
                        $('#hidden_item_total').val(result.data.to_pay);
                        var d = parseInt(result.data.discount);
                        var final = parseInt(result.data.to_pay);
                        var discount = (final) * (d/100);
                        var to_pay  = (final) - discount;
                        console.log("final",result.data.to_pay);
                        $('#hidden_item_total').val(final);
                        $('#price').val(final);
                        $('#item_total').html(result.data.currency + final);
                        $('.total_amount').html(result.data.currency + parseInt(to_pay));
                        $("#saved").text('you have saved ' + result.data.currency + parseInt(discount));
                        $("#discount").html(result.data.currency + parseInt(discount));
                        $("#to_pay").text(result.data.currency + parseInt(to_pay));
                        $('#total_payment').val(to_pay);
                        $('.order_list_price').html(parseInt(to_pay));
                        $("#saved").text('you have saved ' + result.data.currency + parseInt(((final) - to_pay)));
                        $('.total_amount').text(result.data.currency + parseInt(to_pay));
                        $("#discount").text(result.data.currency + parseInt(discount));
                    }
                    else
                    {
                        var final = parseInt(result.data.to_pay);
                        $('#hidden_item_total').val(final);
                        var discount = parseInt(result.data.discount);
                        var to_pay  = (final) - discount;
                        $('#hidden_item_total').val(final);
                        $('#price').val(final);
                        $('.order_list_price').html(parseInt(result.data.to_pay));
                        $('#item_total').html(result.data.currency + final);
                        $('.total_amount').html(result.data.currency + parseInt(to_pay));
                        $("#saved").text('you have saved ' + result.data.currency + parseInt(discount));
                        $("#discount").html(result.data.currency + parseInt(discount));
                        $("#to_pay").text(result.data.currency + parseInt(to_pay));
                        $('#total_payment').val(to_pay);
                        $("#saved").text('you have saved ' + result.data.currency + parseInt(((final) - to_pay)));
                        $('.total_amount').text(result.data.currency + parseInt(to_pay));
                        $("#discount").text(result.data.currency + parseInt(discount));
                    }
                }
                else
                {
                    $('.order_list_price').html(parseInt(result.data.to_pay));
                    $('#price').val(result.data.final_price);
                    $('#hidden_item_total').val(parseInt(result.data.final_price));
                    $('#item_total').html(result.data.currency + parseInt(result.data.final_price));
                    $('.total_amount').text(result.data.currency + parseInt(result.data.to_pay));
                    $('#total_payment').val(result.data.to_pay);
                    $('#to_pay').text(result.data.currency + parseInt(result.data.to_pay));
                }
                if($('input[type=radio][name=delivery_type]:checked').val() == 'delivery')
                {
                    var delivery_charge = parseInt($('#hidden_delivery_charge').val());
                    if(hidden_coupen != 0)
                    {
                        if(result.data.discountType == 'percentage')
                        {
                            $('.delivery_charge').val(result.data.currency + delivery_charge);
                            var d = parseInt(result.data.discount);
                            var final = result.data.to_pay;
                            var discount = (final) * (d/100);
                            var to_pay  = (final + delivery_charge) - discount;
                            console.log('discount',discount);
                            $('#price').val(final);
                            $('#hidden_item_total').val(final);
                            $('#item_total').html(result.data.currency + final);
                            $('.order_list_price').html(parseInt(to_pay));
                            $('.total_amount').html(result.data.currency + parseInt(to_pay));
                            $("#saved").text('you have saved ' + result.data.currency + parseInt(discount));
                            $('#total_payment').val(to_pay);
                            $("#discount").html(result.data.currency + parseInt(discount));
                            $("#to_pay").text(result.data.currency + parseInt(to_pay));
                            $("#saved").text('you have saved ' + result.data.currency + parseInt(((final + delivery_charge) - to_pay)));
                            $('.total_amount').text(result.data.currency + parseInt(to_pay));
                            $("#discount").text(result.data.currency + parseInt(discount));
                        }
                        else
                        {
                            var delivery_charge = parseInt($('#hidden_delivery_charge').val());
                            $('.delivery_charge').val(result.data.currency + delivery_charge);
                            var d = parseInt(result.data.to_pay['discount']);
                            var final = result.data.to_pay;
                            var discount = parseInt(result.data.discount);
                            var to_pay = (final + delivery_charge) - discount;
                            $('#price').val(final);
                            $('#hidden_item_total').val(final);
                            $('.order_list_price').html(parseInt(to_pay));
                            $('#item_total').html(result.data.currency + final);
                            $('.total_amount').html(result.data.currency + parseInt(to_pay));
                            $("#saved").text('you have saved ' + result.data.currency + parseInt(discount));
                            $("#discount").html(result.data.currency + parseInt(discount));
                            $("#to_pay").text(result.data.currency + parseInt(to_pay));
                            $('#total_payment').val(to_pay);
                            $("#saved").text('you have saved ' + result.data.currency + parseInt(((final + delivery_charge) - to_pay)));
                            $('.total_amount').text(result.data.currency + parseInt(to_pay));
                            $("#discount").text(result.data.currency + parseInt(discount));
                        }
                    }
                    else
                    {
                        var delivery_charge = parseInt($('#hidden_delivery_charge').val());
                        $('#price').val(result.data.final_price);
                        $('.order_list_price').html(parseInt(result.data.to_pay));
                        $('#hidden_item_total').val(parseInt(result.data.to_pay + delivery_charge));
                        $('#item_total').html(result.data.currency + parseInt(result.data.final_price));
                        $('.total_amount').text(result.data.currency + parseInt(result.data.to_pay + delivery_charge));
                        $('#total_payment').val(result.data.to_pay + delivery_charge);
                        $("#to_pay").text(result.data.currency + parseInt(result.data.to_pay + delivery_charge));
                    }
                }
            }
            else
            {
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

function removeSingleItem(id,type)
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: url + '/removeSingleItem',
        data:
        {
            id: id,
            type: type,
        },
        success: function (result)
            {
            console.log('result', result)
            if (result.success == true)
            {
                swal(result.data);
                location.reload();
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
