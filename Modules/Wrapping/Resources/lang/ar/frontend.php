<?php

return [
    'wrapping' => [
        'title' => 'التغليف',
        'gift_wrapper' => 'نسق هديتك',
        'congratulation_card' => 'اضف كارت للتهنئة',
        'additions' => 'إضافات',
        'btn' => [
            'complete_payment' => 'استكمال الدفع',
            'choose' => 'اختار',
            'close' => 'خروج',
        ],
        'select_at_least_one_element' => 'الرجاء اختيار منتج واحد على الاقل',
        'product_does_not_have_shipment' => 'لا يمكن تغليف هذا المنتج حيث لا يحتوى على أبعاد',
    ],
    'cards' => [
        'form' => [
            'sender_name' => 'من',
            'receiver_name' => 'الى',
            'message' => 'وصل مشاعرك هنا',
        ],
        'validation'    => [
            'sender_name'       =>  [
                'required'      =>  'من فضلك ادخل اسم المرسل',
                'max'           =>  'اسم المرسل يجب ألا يتجاوز 255 حرفاً',
            ],
            'receiver_name'       =>  [
                'required'      =>  'من فضلك ادخل اسم المرسل إليه',
                'max'           =>  'اسم المرسل إليه يجب ألا يتجاوز 255 حرفاً',
            ],
            'message'       =>  [
                'required'      =>  'من فضلك ادخل الرسالة',
                'max'           =>  'الرسالة يجب ألا تتجاوز 3000 حرفاً',
            ],
        ],
        'card_not_found'        => 'هذا الكارت غير موجود',
    ],
    'gifts' => [
        'gift_not_found' => 'هذه الهدية غير موجودة',
        'can_not_wrapp_product' => 'لا يمكن تغليف هذا المنتج',
    ],
    'addons' => [
        'addons_not_found'                                  => 'هذه الإضافة غير موجودة',
        'addons_quantity_not_available'                     => 'كمية هذه الإضافة غير متاحة حالياَ',
        'requested_qty_greater_than_addons_quantity'        => 'الكمية المطلوبة اكبر من كمية الإضافة',
        'enter_quantity_greater_than_zero'                  => 'من فضلك ادخل كمية اكبر من صفر',
    ],
];
