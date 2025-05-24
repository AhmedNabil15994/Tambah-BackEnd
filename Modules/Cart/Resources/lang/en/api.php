<?php

return [
    'cart' => [
        'product' => [
            'not_found' => 'This product is not available now, with id:',
        ],
    ],
    'validations' => [
        'cart' => [
            'vendor_not_match' => 'Items in cart not match with this vendor , clear the cart and try again',
        ],
        'user_token' => [
            'required' => 'Enter User Token',
        ],
        'state_id' => [
            'required' => 'Enter Migration Id',
            'exists' => 'This Migration is not found',
        ],
        'address_id' => [
            'required' => 'Enter Address Id',
            'exists' => 'This address is not found',
        ],
    ],
];
