<?php

return [
    'labels' => [
        'datatable' => [
            "show_in" => "Information to show in Labels " ,
            "preview" =>"Preview"
        ],
        'form' => [
           
        ],
        'routes' => [
            'index' => 'barcodes',
        ],
        'validation' => [
            
        ],
    ],
    'barcode'      => [
        'datatable' => [
            'created_at'    => 'Created At',
            'date_range'    => 'Search By Dates',
            'image'         => 'Image',
            'options'       => 'Options',
            'status'        => 'Status',
            'name'         => 'Name',
            "description"         => "Description",
           "paper_width"        => "Paper Width"
        ],
        'form'      => [
            'status'        => 'Status',
            'name'         => 'Name',
            "description"         => "Description",
           "paper_width"        => "Paper Width",
           "is_continuous" => "Is continuous" ,
           "top_margin"    => "Top margin",
           "left_margin"   =>"Left Margin",
           "paper_width"=> "Paper width",
           "width"         =>"Width",
           "height"         =>"Height",
           "paper_width"   =>"Paper width",
           "paper_height"  =>"Paper height",
           "stickers_in_one_row"=>"Stickers in one row",
           "row_distance"=>"Row distance",
           "col_distance"  => "Col distance",
           "stickers_in_one_sheet"=>"Stickers in one sheet ",
            'tabs'              => [
                'general'       => 'General Info.',
                "input_lang"    =>"Data :lang"
            ],
           
        ],
        'routes'    => [
           
            'create'=> 'Create Barcode Setting',
            'index' => 'Barcode Setting',
            'update'=> 'Update Barcode Setting',
        ],
       
    ],
    'reports'      => [
        'datatable' => [
            'created_at'    => 'Created At',
            'date_range'    => 'Search By Dates',
            'image'         => 'Image',
            'options'       => 'Options',
            'status'        => 'Status',
            "vendor_id"     => "Vendor",
            "all"           => "All" ,
            "type"          => "Type" ,
            "vendor_title"  => "Vendor Title" ,
            "cashier"       => "Cashier",
            "branch_id"     => "Branch" ,
            "method_transaction"=>"Transaction Method",



        ],
        "proudct_sales"=>[
            "product" => "Product" ,
            "qty"     => "Qty"  ,
            "total"   => "Total",
            "product_stock"=> "Product stock " ,
            "order_id"      => "Order N.",
            "order_date"    => "Order date",
            "price"         => "Price unit",  
            "type"          => "Type"   ,
            "payment_method"     => "Method" ,
            "vendor_title"  => "Vendor Title"

        ],
        "product_stock"=>[
            "product" => "Product" ,
            "qty"     => "Qty"  ,
            "out_qty"   => "Total Paid Qty",
            "order_date"    => "Created at ",
            "price"         => "Price unit",  
            "type"          => "Type"   ,
            "vendor_title"  => "Vendor Title"

        ],
        "refund"=>[
            "product" => "Product" ,
            "qty"     => "Qty"  ,
            "total"   => "Total",
            "order_id"      => "Order N.",
            "order_date"    => "Order date",
            "price"         => "Price unit",  
            "type"          => "Type"   ,
            "vendor_title"  => "Vendor Title"

        ],
        "order_sales"=>[
            "vendors_count" => "Vendor Count" ,
            "qty"     => "Qty"  ,
            "total"   => "Total",
            "order_id"      => "Order N.",
            "order_date"    => "Order date",
            "payment_method"          => "Payment Method"   ,
            "user"                    => "User" ,
            "cashier"                 => "Cashier" ,
            "discount"                => "Discount"  ,
           
        ]
        ,
        "vendors"=>[
            "title" => "Vendor Title" ,
            
            "total"   => "Total Sales",
            "total_refund"=> "Total Refund" ,
            "qty_refund"=> "Qty  Refund" ,
            "qty"=> "َQty" ,
            "created_at"    => "Created at",
        ],
        "order_refund"=>[
            "vendors_count" => "Vendor Count" ,
            "qty"     => "Qty"  ,
            "total"   => "Total",
            "order_id"      => "Order N.",
            "order_date"    => "Order date",
            "payment_method"          => "Payment Method"   ,
            "user"                    => "User" ,
            "cashier"                 => "Cashier" ,
        ],
        'routes'    => [
            'proudct_sales'=> 'Report Product Sales',
            'order_sales'=> 'Report Order Sales',
            "refund"     => "Report Refund Product Sales" ,
            "order_refund" => "Report Refund Orders",
            "product_stock" => "Product Stock Report" ,
            "vendors"       => "Vendors Report"
           
        ],
       
    ],

];