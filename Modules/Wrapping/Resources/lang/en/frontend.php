<?php

return [
    'wrapping'      => [
        'title'                     => 'Wrapping',
        'gift_wrapper'              => 'Gift Wrapper',
        'congratulation_card'       => 'Congratulation Card',
        'additions'                 => 'Additions',
        'btn'               => [
            'complete_payment'                      => 'Complete the payment',
            'choose'                                => 'Choose',
            'close' => 'Close',
        ],
        'select_at_least_one_element'               => 'Please choose at least one product',
        'product_does_not_have_shipment' => 'This product cannot be wrapped because it does not have dimensions',
    ],
    'cards'     =>  [
        'form'      =>  [
            'sender_name'   =>  'From',
            'receiver_name'   =>  'To',
            'message'   =>  'Write Your Message Here',
        ],
        'validation'    => [
            'sender_name'       =>  [
                'required'      =>  'Please, Enter Sender Name',
                'max'           =>  'The sender\'s name cannot exceed 255 characters',
            ],
            'receiver_name'       =>  [
                'required'      =>  'Please, Enter Receiver Name',
                'max'           =>  'The receiver\'s name cannot exceed 255 characters',
            ],
            'message'       =>  [
                'required'      =>  'من فضلك ادخل الرسالة',
                'max'           =>  'The message cannot exceed 3000 characters',
            ],
        ],
        'card_not_found'            => 'This card is not found',
    ],
    'gifts' => [
        'gift_not_found' => 'This gift is not found',
        'can_not_wrapp_product' => 'This product cannot be wrapped',
    ],
    'addons' => [
        'addons_not_found'                                  => 'This Addons is not found',
        'addons_quantity_not_available'                     => 'The quantity of this add-on is not currently available',
        'requested_qty_greater_than_addons_quantity'        => 'The quantity required is greater than the quantity of add-on',
        'enter_quantity_greater_than_zero'                  => 'Please enter a quantity greater than zero',
    ],
];
