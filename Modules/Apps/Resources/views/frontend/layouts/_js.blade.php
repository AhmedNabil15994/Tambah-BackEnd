<script>
    function displayErrorsMsg(data, icon = 'error') {
        // console.log('errors::', $.parseJSON(data.responseText));

        var getJSON = $.parseJSON(data.responseText);

        var output = '<ul>';

        if (typeof getJSON.errors == 'string') {
            output += "<li>" + getJSON.errors + "</li>";
        } else {
            // if (getJSON.errors.hasOwnProperty("code")) {
            //     output += "<li>" + getJSON.errors['code'][0] + "</li>";
            // } else {

            for (var error in getJSON.errors) {
                output += "<li>" + getJSON.errors[error] + "</li>";
            }

            // }
        }

        output += '</ul>';

        var wrapper = document.createElement('div');
        wrapper.innerHTML = output;
        Swal.fire({
            position: 'center',
            icon: icon,
            title: wrapper
        });
    }

    function displaySuccessMsg(data) {
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: data,
            showConfirmButton: false,
            timer: 2000
        });
    }

    function deleteFromCartByAjax(productID, productType = 'product') {

        var prdListId = productType === 'product' ? productID : 'var-' + productID;
        $('#headerLoaderDiv').show();

        $.ajax({
            method: "GET",
            url: "{{ url(route('frontend.shopping-cart.deleteByAjax')) }}",
            data: {
                "id": productID,
                "product_type": productType,
            },
            beforeSend: function() {

            },
            success: function(data) {
                $('#prdList-' + prdListId).remove();

                var cartIcon = $('.cat-count');
                var cartItemsInfo = $('#cartItemsInfo');

                $('.cartPrdTotal').html(data.result.cartTotal);

                if (data.result.cartCount == 0) {

                    var info = `
                        <div class="empty-subtitle alert alert-danger col-lg-12 text-center">{{ __('catalog::frontend.cart.empty') }}</div>
                    `;
                    cartItemsInfo.html(info);
                    $('.minicart-items').empty();
                    $('.cat-count').text('');
                    $('#cartIcon').hide();
                    $('#cart_footer_content').hide();

                } else {
                    cartIcon.text('').append(data.result.cartCount);

                    var rowCartItemsInfo = `
                        <div class="subtitle">{{ __('catalog::frontend.cart.you_have') }} <b>( ${data.result.cartCount} )</b> {{ __('catalog::frontend.cart.products_in_your_cart') }}</div>
                    `;
                    cartItemsInfo.html(rowCartItemsInfo);

                }

            },
            error: function(data) {
                displayErrorsMsg(data);
            },
            complete: function(data) {

                var getJSON = $.parseJSON(data.responseText);

                if (getJSON.errors) {
                    displayErrorsMsg(data, 'warning');
                    return true;
                }

                $('#headerLoaderDiv').hide();

            },
        });

    }

    function updateHeaderCart(params) {
        var rowCount,
            rowCartItemsInfo,
            rowCartItemsContainer,
            rowLi,
            cartIcon = $('#cartPrdCount'),
            cartItemsInfo = $('#cartItemsInfo'),
            cartItemsContainer = $('#cartItemsContainer'),
            minicartItems = $('.minicart-items'),
            cartPrdTotal = $('.cartPrdTotal');

        var prdListId = params.productId;
        $('.cartItemsInfo').remove();

        cartItemsInfo.text('').append('<div class="subtitle">{{ __('catalog::frontend.cart.you_have') }}' +
            '<b>( ' + params.cartCount + ' )</b> {{ __('catalog::frontend.cart.products_in_your_cart') }}</div>');

        rowLi = `
                  <div class="media align-items-center">
                     <div class="pro-img d-flex align-items-center">
                        <img class="img-fluid" src="${params.productImage}">
                     </div>
                    <div class="media-body">
                        <span class="product-name">
                            <a href="${params.productDetailsRoute}">${params.productTitle}</a>
                        </span>
                        <div class="product-price d-block">
                            <span class="text-muted">x ${params.productQuantity}</span>
                            <span class="pro-price">${params.productPrice}</span>
                        </div>
                    </div>
                    <button type="button" class="btn remove"
                    onclick="deleteFromCartByAjax(${params.productId}, '${params.product_type}')"><i class="ti-trash"></i></button>
                  </div>
                `;

        if ($("#prdList-" + prdListId).length == 0) {
            //it doesn't exist
            $item = `
                     <li class="product-item"
                        id="prdList-${prdListId}">
                        ${rowLi}
                    </li>
                `;
            minicartItems.prepend($item);
        } else {
            //it exist
            $("#prdList-" + prdListId).html(rowLi);
        }

        var total = params.cartSubTotal;

        $('#cart_footer_content').show();
        $('#cartIcon').show();
        cartIcon.text('').append(params.cartCount);
        cartPrdTotal.text('').append(total);
        displaySuccessMsg(params.message);
    }

    function generalAddToCart(action, productId, btn) {

        btn = $(btn);
        var container = btn.closest('.single-product-add-to-cart-content');
        var loader = container.find('.productLoader');
        var productImage = $('#productImage-' + productId).val();
        var productTitle = $('#productTitle-' + productId).val();
        // var qty = $('#productQuantity-' + productId).val();

        btn.hide();
        loader.show();

        $.ajax({
            method: "POST",
            url: action,
            data: {
                // "qty": qty,
                "request_type": 'general_cart',
                "product_type": 'product',
                "_token": '{{ csrf_token() }}',
            },
            beforeSend: function() {},
            success: function(data) {
                var params = {
                    'productId': productId,
                    'productImage': productImage,
                    'productTitle': productTitle,
                    'product_type': 'product',
                    'message': data.message,
                    'productQuantity': data.data.productQuantity,
                    'productPrice': data.data.productPrice,
                    'productDetailsRoute': data.data.productDetailsRoute,
                    'cartCount': data.data.cartCount,
                    'cartSubTotal': data.data.subTotal,
                };

                updateHeaderCart(params);
                // displaySuccessMsg(data['message']);
            },
            error: function(data) {
                loader.hide();
                btn.show();
                displayErrorsMsg(data);
            },
            complete: function(data) {
                loader.hide();
                btn.show();
            },
        });

    }

    function generalAddToFavourites(action, productId) {

        $('#btnAddToFavourites-' + productId).hide();
        // $('#productLoaderDiv-' + productId).show();

        $.ajax({
            method: "POST",
            url: action,
            data: {
                "_token": '{{ csrf_token() }}',
            },
            beforeSend: function() {},
            success: function(data) {
                var favouriteBadge = $('#favouriteBadge');
                favouriteBadge.text(data.data.favouritesCount);
                // displaySuccessMsg(data['message']);
            },
            error: function(data) {
                // $('#productLoaderDiv-' + productId).hide();
                $('#btnAddToFavourites-' + productId).show();
                displayErrorsMsg(data);
            },
            complete: function(data) {
                // $('#productLoaderDiv-' + productId).hide();
            },
        });

    }

    function showCouponContainer(coupon_value, total) {
        var row = `
            <div class="d-flex mb-20 align-items-center">
                <span class="d-inline-block right-side flex-1"> {{ __('catalog::frontend.cart.coupon_value') }}</span>
                <span class="d-inline-block left-side">${coupon_value}</span>
            </div>
            `;

        $('#couponContainer').html(row);
        $('#cartTotalAmount').html(total);
    }

    $(document).ready(function() {
        $('.img-block').each(function() {
            $(this).height($(this).width());
        });
    });

    $(window).resize(function() {
        $('.img-block').each(function() {
            $(this).height($(this).width());
        });
    }).resize();
</script>
@include('area::frontend.shared._area_tree_js')
