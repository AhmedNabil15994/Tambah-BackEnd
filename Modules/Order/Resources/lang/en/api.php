<?php

return [
    'orders' => [
        'validations' => [
            'order_not_found' => 'This order does not currently exist',
            'order_rated' => 'The order has been rated previously',
            'min_order_amount_greater_than_cart_total' => 'The order total amount is less than the minimum order',
            'cannot_cancel_order' => 'The order cannot be canceled currently',
            'order_assigned_to_driver_cannot_cancel_order' => 'The order cannot be canceled currently, the order has assigned to driver',
            'order_canceled_before' => 'The order has been canceled before',
            'oops_error' => 'Something went wrong, please try again later',
            'user_token_is_required' => 'Please, enter user token',

            'user_id' => [
                'required' => 'Enter user id',
                'exists' => 'This user does not currently exist',
                'does_not_match' => 'This user is not identical to the current user',
            ],
            'company_delivery_fees' => [
                'required' => 'Please, Select delivery state to get delivery price',
            ],
            'cart' => [
                'required' => 'Please, Add products to cart',
            ],
            'address_id' => [
                'required' => 'Please, Select state of delivery address',
            ],
        ],
        'html_order' => [
            'invoice_details' => 'Invoice Details',
            'invoice_title' => 'Invoice',
            'billed_to' => 'Billed To',
            'shipped_to' => 'Shipped To',
            'order_date' => 'Order Date',
            'order_status' => 'Order Status',
            'payment_method' => 'Payment Method',
            'order_summary' => 'Order Summary',
            'form' => [
                'item' => 'Item',
                'quantity' => 'Quantity',
                'price' => 'Price',
                'totals' => 'Totals',
                'coupon_discount' => 'Coupon Discount',
            ],
            'discount' => 'Discount',
            'subtotal' => 'Subtotal',
            'shipping' => 'Shipping',
            'coupon' => 'Coupon',
            'total' => 'Total',
        ],
    ],
    'address' => [
        'validations' => [
            'address' => [
                'min' => 'Please add more details , must be more than 10 characters',
                'required' => 'Please add address details',
                'string' => 'Please add address details as string only',
            ],
            'block' => [
                'required' => 'Please enter the block',
                'string' => 'You must add only characters or numbers in block',
            ],
            'building' => [
                'required' => 'Please enter the building number / name',
                'string' => 'You must add only characters or numbers in building',
            ],
            'email' => [
                'email' => 'Email must be email format',
                'required' => 'Please add your email',
            ],
            'mobile' => [
                'digits_between' => 'You must enter mobile number with 8 digits',
                'min' => 'You must enter mobile number with 8 digits',
                'max' => 'You must enter mobile number with 8 digits',
                'numeric' => 'Please add mobile number as numbers only',
                'required' => 'Please add mobile number',
                'string' => 'Please add mobile as string only',
            ],
            'state' => [
                'numeric' => 'Please chose state',
                'required' => 'Please chose state',
            ],
            'state_id' => [
                'numeric' => 'Please chose state',
                'required' => 'Please chose state',
            ],
            'street' => [
                'required' => 'Please enter the street name / number',
                'string' => 'You must add only characters or numbers in street',
            ],
            'username' => [
                'min' => 'username must be more than 2 characters',
                'required' => 'Please add username',
                'string' => 'Please add username as string only',
            ],
            'address_type' => [
                'required' => 'Please, Choose Delivery Address Type',
                'in' => 'Delivery address type values must be included',
            ],
            'selected_address_id' => [
                'required' => 'Please, Choose Address From Previous Addresses',
                'not_found' => 'This address does not currently exist',
            ],
        ],
    ],
    'payment' => [
        'validations' => [
            'required' => 'Please Choose Payment Type',
            'in' => 'The type of payment method must be within:',
        ],
    ],
    'shipping_company' => [
        'validations' => [
            'day_code' => [
                'required' => 'Please Choose Shipping Day Code',
            ],
            'day' => [
                'required' => 'Please Choose Shipping Day',
            ],
        ],
    ],
    'product' => [
        'form' => [
            'product_id' => 'This product id',
        ],
        'validations' => [
            'not_found' => 'is not currently available',
            'qty_exceeded' => 'Required quantity is not available for the product',
            'has_variations' => 'This product contains other "Variations" products that cannot be ordered. You can order a product in it',
        ],
    ],
    'rates' => [
        'user_rate_before' => 'Already rated',
        'user_not_have_order' => 'This request is not affiliated with the user',
        'rated_successfully' => 'The pharmacy has been successfully rated',
        'btnClose' => 'Close',
        'your_rate' => 'Your Rate',
        'rate_now' => 'Rate Now',
        'ratings' => 'Ratings',
        'validation' => [
            'order_id' => [
                'required' => 'Order id is required',
                'exists' => 'Order id is not existed in orders table',
            ],
            'rating' => [
                'required' => 'Rating is required',
                'integer' => 'Rating must be integer',
                'between' => 'Rating value must be between 1 and 5',
            ],
            'comment' => [
                'string' => 'Comment must be string',
                'max' => 'Comment must not exceed 1000 characters',
            ],
        ],
    ],
];
