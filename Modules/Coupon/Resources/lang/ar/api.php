<?php

return [
    'coupons' => [
        'enter' => 'ادخل الكوبون',
        'checked_successfully' => 'تم اضافة الكوبون بنجاح',
        'validation' => [
            'code' => [
                'required' => 'من فضلك ادخل كود الخصم',
                'exists' => 'هذا الكود غير صحيح ',
                'expired' => 'هذا الكود غير صالح ',
                'custom' => 'هذا الكود غير مخصص لك ',
                'not_found' => 'هذا الكود غير موجود ',
            ],
            'coupon_value_greater_than_cart_total' => 'قيمة الكوبون اكبر من المبلغ الإجمالى للسلة',
            'condition_error' => 'حدث خطأ ما, الرجاء المحاولة لاحقا',
            'coupon_is_used' => 'انت بالفعل تستخدم كوبون',
            'cart_is_empty' => 'السلة فارغة, يرجى اضافة منتجات للسلة اولا',
            'user_max_uses' => 'عفوا لقد تجاوزت الحد الأقصى لإستخدام هذا الكوبون',
            'coupon_does_not_apply_to_products' => 'هذا الكوبون غير مخصص لمنتجات السلة',
        ],
    ],
];
