<?php

return [
    'slider' => [
        'datatable' => [
            'created_at' => 'تاريخ الآنشاء',
            'date_range' => 'البحث بالتواريخ',
            'end_at' => 'الانتهاء في',
            'image' => 'الصورة',
            'link' => 'الرابط',
            'options' => 'الخيارات',
            'start_at' => 'يبدأ في',
            'status' => 'الحاله',
            'type' => 'النوع',
        ],
        'form' => [
            'end_at' => 'الانتهاء في',
            'image' => 'الصورة',
            'link' => 'رابط السلايدر',
            'start_at' => 'يبدأ في',
            'status' => 'الحاله',
            'short_title' => 'عنوان مختصر',
            'description' => 'الوصف',
            'title' => 'العنوان',
            'short_description' => 'الوصف المختصر',
            'tabs' => [
                'general' => 'بيانات عامة',
            ],
            'products' => 'المنتجات',
            'categories' => 'أقسام المنتجات',
            'slider_type' => [
                'label' => 'نوع السلايدر',
                'external' => ' رابط خارجى',
                'product' => 'منتجات',
                'category' => 'أقسام منتجات',
            ],
        ],
        'routes' => [
            'create' => 'اضافة صور السلايدر',
            'index' => 'صور السلايدر',
            'update' => 'تعديل السلايدر',
        ],
        'validation' => [
            'end_at' => [
                'required' => 'من فضلك اختر تاريخ الانتهاء',
            ],
            'image' => [
                'required' => 'من فضلك اختر صورة السلايدر',
            ],
            'link' => [
                'required' => 'من فضلك ادخل رابط السلايدر',
                'required_if' => 'من فضلك ادخل رابط السلايدر',
            ],
            'start_at' => [
                'required' => 'من فضلك اختر تاريخ البدء',
            ],
            'title' => [
                'required' => 'من فضلك ادخل عنوان السلايدر',
            ],
            'slider_type' => [
                'required' => 'من فضلك اختر نوع السلايدر',
                'in' => 'نوع السلايدر يجب ان يكون ضمن القيم الآتيه',
            ],
            'product_id' => [
                'required_if' => 'من فضلك اختر المنتج',
            ],
            'category_id' => [
                'required_if' => 'من فضلك اختر القسم',
            ],
        ],
    ],

    'banner' => [
        'datatable' => [
            'created_at' => 'تاريخ الآنشاء',
            'date_range' => 'البحث بالتواريخ',
            'end_at' => 'الانتهاء في',
            'image' => 'الصورة',
            'link' => 'الرابط',
            'options' => 'الخيارات',
            'start_at' => 'يبدأ في',
            'status' => 'الحاله',
            'type' => 'النوع',
        ],
        'form' => [
            'end_at' => 'الانتهاء في',
            'image' => 'الصورة',
            'link' => 'رابط البانار',
            'start_at' => 'يبدأ في',
            'status' => 'الحاله',
            'short_title' => 'عنوان مختصر',
            'description' => 'الوصف',
            'title' => 'العنوان',
            'short_description' => 'الوصف المختصر',
            'tabs' => [
                'general' => 'بيانات عامة',
            ],
            'products' => 'المنتجات',
            'categories' => 'أقسام المنتجات',
            'slider_type' => [
                'label' => 'نوع البانار',
                'external' => ' رابط خارجى',
                'product' => 'منتجات',
                'category' => 'أقسام منتجات',
            ],
        ],
        'routes' => [
            'create' => 'اضافة صور البانار',
            'index' => 'صور البانار',
            'update' => 'تعديل البانار',
        ],
        'validation' => [
            'end_at' => [
                'required' => 'من فضلك اختر تاريخ الانتهاء',
            ],
            'image' => [
                'required' => 'من فضلك اختر صورة البانار',
            ],
            'link' => [
                'required' => 'من فضلك ادخل رابط البانار',
                'required_if' => 'من فضلك ادخل رابط البانار',
            ],
            'start_at' => [
                'required' => 'من فضلك اختر تاريخ البدء',
            ],
            'title' => [
                'required' => 'من فضلك ادخل عنوان البانار',
            ],
            'slider_type' => [
                'required' => 'من فضلك اختر نوع البانار',
                'in' => 'نوع البانار يجب ان يكون ضمن القيم الآتيه',
            ],
            'product_id' => [
                'required_if' => 'من فضلك اختر المنتج',
            ],
            'category_id' => [
                'required_if' => 'من فضلك اختر القسم',
            ],
        ],
    ],

    'instagrams' => [
        'datatable' => [
            'comments_count' => 'عدد الكومنتات',
            'likes_count' => 'عدد الاعجاب',
            'created_at' => 'تاريخ الآنشاء',
            'date_range' => 'البحث بالتواريخ',
            'image' => 'الصورة',
            'link' => 'الرابط',
            'options' => 'الخيارات',
            'status' => 'الحاله',
            'type' => 'النوع',
            'video' => 'فيديو ',
            'photo' => 'صورة',
        ],
        'form' => [
            'title' => 'العنوان',
            'video' => 'فيديو ',
            'photo' => 'صورة',
            'type' => 'النوع',
            'comments_count' => 'عدد الكومنتات',
            'likes_count' => 'عدد الاعجاب',
            'end_at' => 'الانتهاء في',
            'image' => 'الصورة',
            'link' => 'رابط البانار',
            'start_at' => 'يبدأ في',
            'status' => 'الحاله',
            'tabs' => [
                'general' => 'بيانات عامة',
            ],
            'products' => 'المنتجات',
            'categories' => 'أقسام المنتجات',
            'slider_type' => [
                'label' => 'نوع صور انستجرام',
                'external' => ' رابط خارجى',
            ],
        ],
        'routes' => [
            'create' => 'اضافة صور صور انستجرام',
            'index' => 'صور صور انستجرام',
            'update' => 'تعديل صور انستجرام',
        ],
        'validation' => [
            'end_at' => [
                'required' => 'من فضلك اختر تاريخ الانتهاء',
            ],
            'image' => [
                'required' => 'من فضلك اختر صورة صور انستجرام',
            ],
            'link' => [
                'required' => 'من فضلك ادخل رابط صور انستجرام',
                'required_if' => 'من فضلك ادخل رابط صور انستجرام',
            ],
            'start_at' => [
                'required' => 'من فضلك اختر تاريخ البدء',
            ],
            'title' => [
                'required' => 'من فضلك ادخل عنوان صور انستجرام',
            ],
            'slider_type' => [
                'required' => 'من فضلك اختر نوع صور انستجرام',
                'in' => 'نوع صور انستجرام يجب ان يكون ضمن القيم الآتيه',
            ],
            'product_id' => [
                'required_if' => 'من فضلك اختر المنتج',
            ],
            'category_id' => [
                'required_if' => 'من فضلك اختر القسم',
            ],
        ],
    ],
];
