<?php

return [
    'cities' => [
        'form' => [
            'countries' => 'اختر الدولة',
            'status' => 'الحالة',
            'title' => 'العنوان',
            'tabs' => [
                'general' => 'بيانات عامة',
            ]
        ],
        'datatable' => [
            'countries' => 'الدولة',
            'created_at' => 'تاريخ الآنشاء',
            'options' => 'الخيارات',
            'status' => 'الحالة',
            'title' => 'العنوان',
        ],
        'routes' => [
            'create' => 'اضافة المحافظات',
            'index' => 'المحافظات',
            'update' => 'تعديل المحافظة',
        ],
        'validation' => [
            'country_id' => [
                'required' => 'من فضلك اختر الدولة',
            ],
            'title' => [
                'required' => 'من فضلك ادخل العنوان',
                'unique' => 'هذا العنوان تم ادخالة من قبل',
            ],
        ],
    ],

    'countries' => [
        'form' => [
            'currencies_codes' => 'كود العملة',
            'status' => 'الحالة',
            'title' => 'العنوان',
            'code' => 'الكود',
            'delivery_types' => [
                'title' => 'نوع التوصيل',
                'local' => 'محلي',
                'aramex' => 'أراميكس',
            ],
            'tabs' => [
                'general' => 'بيانات عامة',
            ]
        ],
        'datatable' => [
            'created_at' => 'تاريخ الآنشاء',
            'options' => 'الخيارات',
            'status' => 'الحالة',
            'title' => 'العنوان',
            'code' => 'الكود',
        ],
        'routes' => [
            'create' => 'اضافة الدول',
            'index' => 'الدول',
            'update' => 'Update Page',
            'update' => 'تعديل الدولة',
        ],
        'validation' => [
            'title' => [
                'required' => 'من فضلك ادخل العنوان',
                'unique' => 'هذا العنوان تم ادخالة من قبل',
            ],
            'code' => [
                'required' => 'من فضلك ادخل الكود',
                'unique' => 'هذا الكود تم ادخالة من قبل',
                'string' => 'الكود يجب ان يتكون من حروف كبيرة فقط',
            ],
            'currencies_code' => [
                'required' => 'من فضلك اختر كود العمله',
            ],
        ],
    ],
    'states' => [
        'form' => [
            'cities' => 'اختر المنطقة',
            'status' => 'الحالة',
            'title' => 'العنوان',
            'chose_cities'     => 'إختر المحافظة',
            'all_cities'     => 'كل المحافظات',
            'tabs' => [
                'general' => 'بيانات عامة',
            ]
        ],
        'datatable' => [
            'cities' => 'المحافظة',
            'created_at' => 'تاريخ الآنشاء',
            'options' => 'الخيارات',
            'status' => 'الحالة',
            'title' => 'العنوان',
        ],
        'routes' => [
            'create' => 'اضافة المناطق',
            'index' => 'المناطق',
            'update' => 'تعديل المنطقة',

        ],
        'validation' => [
            'city_id' => [
                'required' => 'من فضلك اختر الدولة',
            ],
            'title' => [
                'required' => 'من فضلك ادخل العنوان',
                'unique' => 'هذا العنوان تم ادخالة من قبل',
            ],
        ],
    ],
];
