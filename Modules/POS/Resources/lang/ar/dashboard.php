<?php

return [
    'labels' => [
        'datatable' => [
            "show_in" => " المعلومات التى تظهر فى الباركود" ,
            "preview" =>"عرض"
        ],
        'form' => [
           
        ],
        'routes' => [
            'index' => 'طباعة باركود',
        ],
        'validation' => [
            
        ],
    ],
    'barcode'      => [
        'datatable' => [
            'created_at'    => 'تاريخ الآنشاء',
            'date_range'    => 'البحث بالتواريخ',
            'options'       => 'الخيارات',
            'status'        => 'الحالة',
            'name'         => 'الاسم',
            "description"         => "الوصف",
            "method_transaction"=>"طريقة الدفع",
             "paper_width"=> "عرض الورقه"
        ],
        'form'      => [
            'status'        => 'الحالة',
            'name'         => 'الاسم',
            "description"         => "الوصف",
            "is_continuous" => "مستمر" ,
            "top_margin"    => "المارحن العلوى",
            "left_margin"   =>"المارجن الشمالى",
            "paper_width"=> "عرض الورقه",
            "width"         =>"العرض",
            "height"         =>"الطول",
            "paper_width"   =>"عرض الصفحة",
            "paper_height"  =>"طول الصفحه",
            "stickers_in_one_row"=>"عدد Stickers فى الصف الواحد",
            "row_distance"=>"المسافه بين الصفوف",
            "col_distance"  => "المسافه بين الاعمده",
            "stickers_in_one_sheet"=>"Stickers in one sheet ",
            'tabs'              => [
                'general'       => 'بيانات عامة',
                "input_lang"    =>"بيانات :lang"
            ],
        
        ],
        'routes'    => [
           
            'create'=> 'اضافة اعداد باركود ',
            'index' => ' اعدادت الباركود',
            'update'=> 'تعديل اعدادت باركود',
        ],
       
    ],
    'reports'      => [
        'datatable' => [
            'created_at'    => 'تاريخ الآنشاء',
            'date_range'    => 'البحث بالتواريخ',
            'options'       => 'الخيارات',
            'status'        => 'الحالة',
            "vendor_id"     => "المتجر",
            "all"           => "الكل",
            "type"          => "النوع" ,
            "cashier"       => "الكاشير",
            "method_transaction"       => "طرق الدفع",
            "branch_id"     => "الفرع",
        ],
        "proudct_sales"=>[
            "product" => "اسم المنتج" ,
            "qty"     => "الكميه"  ,
            "total"   => "الاجمالى",
            "product_stock"=> "المخزون " ,
            "order_id"      => "رقم الطلب",
            "order_date"    => " تاريح الطلب",
            "price"         => "سعر القطعه",  
            "type"          => "النوع"   ,
            "vendor_title"  => "اسم المتجر",
            "payment_method"     => "طربقة الدفع" ,
        ],
        "product_stock"=>[
            "product" => "اسم المنتج" ,
            "qty"     => "الكميه"  ,
            "total"   => "اجمالى الكمية المباعه",
            "order_date"    => " تاريح  الانشاء",
            "price"         => "سعر القطعه",  
            "type"          => "النوع"   ,
            "out_qty"  => "الكمية المباعه",
            "vendor_title"  => "اسم المتجر"
        ],
        "order_sales"=>[
            "vendors_count" => "عدد المتاجر فى الطلب" ,
            "qty"     => "الكميه"  ,
            "total"   => "الاجمالى",
            "order_id"      => "رقم الطلب",
            "order_date"    => " تاريح الطلب",
            "payment_method"          => "طريقة الدفع"   ,
            "vendor_title"  => "اسم المتجر" ,
            "branch_title"  => "اسم الفرع",
            "from_cashier"  => "POS" ,
            "discount"  => "الخصم" ,
            "user"  => "العميل" ,
            "cashier"  => "الكاشير" ,

        ],
        "refund"=>[
            "product" => "اسم المنتج" ,
            "qty"     => "الكميه"  ,
            "total"   => "الاجمالى",
            "product_stock"=> "المخزون " ,
            "order_id"      => "رقم الطلب",
            "created_at"    => " تاريح الطلب",
            "price"         => "سعر القطعه",  
            "type"          => "النوع"  ,
            "order_date"    => " تاريح  الانشاء",
            "vendor_title"  => "اسم المتجر"

        ],
        "vendors"=>[
            "title" => "اسم المتجر" ,
            
            "total"   => "اجمالى المبيعات",
            "total_refund"=> "اجمالى  المرتجع" ,
            "qty_refund"=> "اجمالى  الكميه المرتجعه" ,
            "qty"=> "اجمالى الكميات فى المخزن" ,
            "created_at"    => " تاريح الطلب",
        ],
        "order_refund"=>[
            "vendors_count" => "عدد المتاجر فى الطلب" ,
            "qty"     => "الكميه"  ,
            "total"   => "الاجمالى",
            "cashier"  => "الكاشير" ,
            "order_id"      => "رقم الطلب",
            "order_date"    => " تاريح الطلب",
            "user"  => "العميل" ,
            "payment_method"          => "طريقة الدفع"   ,
            "vendor_title"  => "اسم المتجر"
        ],
        'routes'    => [
            'proudct_sales'=> 'تقارير المنتجات المباعه',
            "refund"       => "تقارير المرجع",
            'order_sales'=> 'تقارير الطلبات المباعه',
            "order_refund" => "تقارير مرتجعات الطلبات",
            "product_stock" => "تقارير مخزون المنتجات" ,
            "vendors"       => "تقارير المتاجر"  ,

        ],
       
    ],
  

];