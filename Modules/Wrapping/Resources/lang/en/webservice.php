<?php

return [
    'gifts' => [
        'size_not_suitable' => 'The gift is inappropriate with the product',
        'quantity_not_available' => 'The amount of the gift is not currently available',
        'this_product_not_exist' => 'This product is not currently availableً',
        'please_select_products' => 'Please choose at least one product',
        'this_gift_not_exist' => 'This gift is not currently available',
        'product_not_found' => 'This product is not currently available',
    ],
    'addons' => [
        'quantity_not_available' => 'The amount of the addons is not currently available',
        'this_addons_not_exist' => 'This addons is not currently availableً',
        'please_select_addons' => 'Please choose one or more addons',
        'quantity_exceeded' => 'The requested quantity is greater than the addons quantity',
    ],
    'cards' => [
        'this_card_not_exist' => 'This card is not currently availableً',
        'validation' => [
            'sender_name' => [
                'required' => 'The sender\'s name is required',
                'string' => 'The sender\'s name must be a text value',
                'max' => 'The sender\'s name cannot exceed 190 characters',
            ],
            'receiver_name' => [
                'required' => 'The receiver\'s name is required',
                'string' => 'The receiver\'s name must be a text value',
                'max' => 'The receiver\'s name cannot exceed 190 characters',
            ],
            'message' => [
                'required' => 'The message is required',
                'max' => 'The message cannot exceed 3000 characters',
            ],
        ],
    ],
];
