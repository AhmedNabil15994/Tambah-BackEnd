<?php

return [
    'gifts' => [
        'size_not_suitable' => 'الهدية غير متناسقة مع المنتج',
        'quantity_not_available' => 'كمية الهدية غير متاحة حالياً',
        'this_product_not_exist' => 'هذا المنتج غير موجود حالياً',
        'please_select_products' => 'من فضلك قم بإختيار منتج واحد على الأقل',
        'this_gift_not_exist' => 'هذه الهدية غير موجودة حالياً',
        'product_not_found' => 'المنتج غير متاح حالياً',
    ],
    'addons' => [
        'quantity_not_available' => 'الكمية المطلوبة غير متاحة حالياً',
        'this_addons_not_exist' => 'هذه الإضافة غير موجودة حالياً',
        'please_select_addons' => 'من فضلك قم بإختيار إضافة أو اكثر',
        'quantity_exceeded' => 'الكمية المطلوبة اكبر من كمية الاضافة',
    ],
    'cards' => [
        'this_card_not_exist' => 'هذا الكارت غير موجود حالياً',
        'validation' => [
            'sender_name' => [
                'required' => 'اسم المرسل مطلوب',
                'string' => 'اسم المرسل لابد ان يكون قيمة نصية',
                'max' => 'اسم المرسل يجب ألا يتجاوز 190 حرفاً',
            ],
            'receiver_name' => [
                'required' => 'اسم المرسل إليه مطلوب',
                'string' => 'اسم المرسل إليه لابد ان يكون قيمة نصية',
                'max' => 'اسم المرسل إليه يجب ألا يتجاوز 190 حرفاً',
            ],
            'message' => [
                'required' => 'الرسالة مطلوب',
                'max' => 'الرسالة يجب ألا تتجاوز 3000 حرفاً',
            ],
        ],
    ],
];
