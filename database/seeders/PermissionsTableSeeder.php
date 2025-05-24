<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Authorization\Entities\Permission;
use DB;

class PermissionsTableSeeder extends Seeder
{
    protected $mapKey = [
        "show" => [
            "lang" => [
                "ar" => "عرض",
                "en" => "show",
            ]
        ],
        "add" => [
            "lang" => [
                "ar" => "إضافة",
                "en" => "add"
            ]
        ],
        "edit" => [
            "lang" => [
                "ar" => "تعديل",
                "en" => "edit"
            ]
        ],
        "delete" => [
            "lang" => [
                "ar" => "حذف",
                "en" => "delete"
            ]
        ],
    ];


    private $permissions = [
        'access' => [
            'category' => ['ar' => 'لوحة التحكم', 'en' => 'access'],
            'single' => true,
            'name' => 'dashboard_access',
            'display_name' => [
                'en' => 'Dashboard access',
                'ar' => 'صلاحية المرور للوحة التحكم'
            ],
        ],
        'driver_access' => [
            'category' => ['ar' => 'لوحة التحكم', 'en' => 'access'],
            'single' => true,
            'name' => 'driver_access',
            'display_name' => [
                'en' => 'Driver access',
                'ar' => 'صلاحية السائقين'
            ],
        ],
        'cashiers_access' => [
            'category' => ['ar' => 'لوحة التحكم', 'en' => 'access'],
            'single' => true,
            'name' => 'cashiers_access',
            'display_name' => [
                'en' => 'Cashiers access',
                'ar' => 'صلاحية العاملين'
            ],
        ],
        'statistics' => [
            'category' => ['ar' => 'لوحة التحكم', 'en' => 'access'],
            'single' => true,
            'name' => 'statistics',
            'display_name' => [
                'en' => 'Show Statistics',
                'ar' => 'عرض الإحصائيات',
            ],
        ],

        'roles' => [
            'display_name' => ['ar' => 'الصلاحيات', 'en' => 'roles'],
            'single' => false
        ],
        'users' => [
            'display_name' => ['ar' => 'العملاء', 'en' => 'users'],
            'single' => false
        ],
        'admins' => [
            'display_name' => ['ar' => 'المدراء', 'en' => 'admins'],
            'single' => false
        ],
        'countries' => [
            'display_name' => ['ar' => 'الدول', 'en' => 'countries'],
            'single' => false
        ],
        'cities' => [
            'display_name' => ['ar' => 'المدن', 'en' => 'cities'],
            'single' => false
        ],
        'states' => [
            'display_name' => ['ar' => 'المناطق', 'en' => 'states'],
            'single' => false
        ],
        'brands' => [
            'display_name' => ['ar' => 'البراندات', 'en' => 'brands'],
            'single' => false
        ],
        'categories' => [
            'display_name' => ['ar' => 'الأقسام', 'en' => 'categories'],
            'single' => false
        ],
        'products' => [
            'display_name' => ['ar' => 'المنتجات', 'en' => 'products'],
            'single' => false
        ],
        'order_statuses' => [
            'display_name' => ['ar' => 'حالات الطلب', 'en' => 'order statuses'],
            'single' => false
        ],
        'orders' => [
            'display_name' => ['ar' => 'الطلبات', 'en' => 'orders'],
            'single' => false
        ],
        'delivery_charges' => [
            'display_name' => ['ar' => 'قيم التوصيل', 'en' => 'delivery charges'],
            'single' => false
        ],
        'companies' => [
            'display_name' => ['ar' => 'شركات التوصيل', 'en' => 'companies'],
            'single' => false
        ],
        'transactions' => [
            'display_name' => ['ar' => 'العمليات', 'en' => 'transactions'],
            'single' => false
        ],
        'options' => [
            'display_name' => ['ar' => 'إختلافات المنتجات', 'en' => 'options'],
            'single' => false
        ],
        'drivers' => [
            'display_name' => ['ar' => 'السائقين', 'en' => 'drivers'],
            'single' => false
        ],
        'slider' => [
            'display_name' => ['ar' => 'الإسلايدر', 'en' => 'sliders'],
            'single' => false
        ],
        'coupon' => [
            'display_name' => ['ar' => 'الكوبونات', 'en' => 'coupons'],
            'single' => false
        ],
        'advertising' => [
            'display_name' => ['ar' => ' المجموعات الاعلانيه', 'en' => 'advertising groups'],
            'single' => false
        ],
        'notifications' => [
            'display_name' => ['ar' => 'الإشعارات', 'en' => 'notifications'],
            'single' => false
        ],
        'subscribes' => [
            'display_name' => ['ar' => 'الإشتراكات', 'en' => 'subscribes'],
            'single' => false
        ],
        'tags' => [
            'display_name' => ['ar' => 'الوسوم', 'en' => 'tags'],
            'single' => false
        ],
        'search_keywords' => [
            'display_name' => ['ar' => 'كلمات البحث', 'en' => 'search keywords'],
            'single' => false
        ],
        'apphomes' => [
            'display_name' => ['ar' => 'الصفحة الرئيسية', 'en' => 'home page'],
            'single' => false
        ],
        'social_marketing' => [
            'display_name' => ['ar' => 'تسويق مواقع التواصل', 'en' => 'Marketing Social Media'],
            'single' => false
        ],
        'pages' => [
            'display_name' => ['ar' => 'الصفحات', 'en' => 'pages'],
            'single' => false
        ],
        'attributes' => [
            'display_name' => ['ar' => 'الصفات', 'en' => 'Attributes'],
            'single' => false
        ],
        'gifts' => [
            'display_name' => ['ar' => 'الهدايا', 'en' => 'gifts'],
            'single' => false
        ],
        'cards' => [
            'display_name' => ['ar' => 'كروت التهنئة', 'en' => 'cards'],
            'single' => false
        ],
        'wrapping_addons' => [
            'display_name' => ['ar' => 'إضافات التغليف', 'en' => 'wrapping addons'],
            'single' => false
        ],
        'ages' => [
            'display_name' => ['ar' => 'الأعمار', 'en' => 'Ages'],
            'single' => false
        ],
        'barcode' => [
            'display_name' => ['ar' => 'إعدادات الباركود', 'en' => 'Barcode'],
            'single' => false
        ],
        'cashiers' => [
            'display_name' => ['ar' => 'العاملين أو الصرافين', 'en' => 'Cashiers'],
            'single' => false
        ],
        'vendors' => [
            'display_name' => ['ar' => 'المتاجر', 'en' => 'Vendors'],
            'single' => false
        ],

        // single products
        'edit_products_price' => [
            'category' => ['ar' => 'المنتجات', 'en' => 'products'],
            'single' => true,
            'name' => 'edit_products_price',
            'display_name' => [
                'en' => 'Edit Products Price',
                'ar' => 'تعديل سعر المنتج ',
            ],
        ],

        'show_labels' => [
            'category' => ['ar' => 'الباركود', 'en' => 'Labels'],
            'single' => true,
            'name' => 'show_labels',
            'display_name' => [
                'en' => 'Show Labels',
                'ar' => 'عرض الباركود',
            ],
        ],

        'edit_products_qty' => [
            'category' => ['ar' => 'المنتجات', 'en' => 'products'],
            'single' => true,
            'name' => 'edit_products_qty',
            'display_name' => [
                'en' => 'Edit Products Quantity',
                'ar' => 'تعديل كمية المنتج',
            ],
        ],

        'edit_products_title' => [
            'category' => ['ar' => 'المنتجات', 'en' => 'products'],
            'single' => true,
            'name' => 'edit_products_title',
            'display_name' => [
                'en' => 'Edit Products Title',
                'ar' => 'تعديل عنوان المنتج',
            ],
        ],

        'edit_products_description' => [
            'category' => ['ar' => 'المنتجات', 'en' => 'products'],
            'single' => true,
            'name' => 'edit_products_description',
            'display_name' => [
                'en' => 'Edit Products Description',
                'ar' => 'تعديل وصف المنتج',
            ],
        ],

        'edit_products_image' => [
            'category' => ['ar' => 'المنتجات', 'en' => 'products'],
            'single' => true,
            'name' => 'edit_products_image',
            'display_name' => [
                'en' => 'Edit Products Image',
                'ar' => 'تعديل صورة المنتج',
            ],
        ],

        'edit_products_gallery' => [
            'category' => ['ar' => 'المنتجات', 'en' => 'products'],
            'single' => true,
            'name' => 'edit_products_gallery',
            'display_name' => [
                'en' => 'Edit Products Gallery',
                'ar' => 'تعديل صور المنتج',
            ],
        ],

        'edit_products_category' => [
            'category' => ['ar' => 'المنتجات', 'en' => 'products'],
            'single' => true,
            'name' => 'edit_products_category',
            'display_name' => [
                'en' => 'Edit Products Category',
                'ar' => 'تعديل قسم المنتج',
            ],
        ],

        'pending_products_for_approval' => [
            'category' => ['ar' => 'المنتجات', 'en' => 'products'],
            'single' => true,
            'name' => 'pending_products_for_approval',
            'display_name' => [
                'en' => 'Add Product Without Approval',
                'ar' => 'اضافة منتج دون الموافقة',
            ],
        ],

        'review_products' => [
            'category' => ['ar' => 'المنتجات', 'en' => 'products'],
            'single' => true,
            'name' => 'review_products',
            'display_name' => [
                'en' => 'Review Products',
                'ar' => 'مراجعة المنتجات',
            ],
        ],
        //////////////// end single product permissions

        // reports


        'show_product_sale_reports' => [
            'category' => ['ar' => 'التقارير', 'en' => 'reports'],
            'single' => true,
            'name' => 'show_product_sale_reports',
            'display_name' => [
                "ar" => "عرض تقارير المنتجات المباعة",
                "en" => "Show Product Sale Reports"
            ]
        ],

        'show_order_sale_reports' => [
            'category' => ['ar' => 'التقارير', 'en' => 'reports'],
            'single' => true,
            'name' => 'show_order_sale_reports',
            'display_name' => [
                "ar" => "عرض تقارير مبيعات الطلبات",
                "en" => "Show Order Sale Reports "
            ]
        ],

        'show_product_stock_reports' => [
            'category' => ['ar' => 'التقارير', 'en' => 'reports'],
            'single' => true,
            'name' => 'show_product_stock_reports',
            'display_name' => [
                "ar" => "عرض تقارير مخزون المنتجات",
                "en" => "Show Product Stock Reports"
            ]
        ],
        //////////////// end single reports permissions

        // single order

        'show_all_orders' => [
            'category' => ['ar' => 'الطلبات', 'en' => 'orders'],
            'single' => true,
            'name' => 'show_all_orders',
            'display_name' => [

                "ar" => "عرض جميع العمليات",
                "en" => "Show All Orders"
            ],
        ],

        'show_order_details_tab' => [
            'category' => ['ar' => 'الطلبات', 'en' => 'orders'],
            'single' => true,
            'name' => 'show_order_details_tab',
            'display_name' => [

                "ar" => "عرض نافذة تفاصيل الطلب",
                "en" => "Show Order Details Tab"
            ],
        ],

        'show_order_change_status_tab' => [
            'category' => ['ar' => 'الطلبات', 'en' => 'orders'],
            'single' => true,
            'name' => 'show_order_change_status_tab',
            'display_name' => [

                "ar" => "عرض نافذة تغيير حالة الطلب",
                "en" => "Show Change Order Status Tab"
            ],
        ],

        'show_order_change_payment_type_tab' => [
            'category' => ['ar' => 'الطلبات', 'en' => 'orders'],
            'single' => true,
            'name' => 'show_order_change_payment_type_tab',
            'display_name' => [

                "ar" => "عرض نافذة تغيير نوع الدفع للطلب",
                "en" => "Show Change Order Payment Type Tab"
            ],
        ],

        'show_order_change_payment_status_tab' => [
            'category' => ['ar' => 'الطلبات', 'en' => 'orders'],
            'single' => true,
            'name' => 'show_order_change_payment_status_tab',
            'display_name' => [

                "ar" => "عرض نافذة تغيير حالة الدفع للطلب",
                "en" => "Show Change Order Payment Status Tab"
            ],
        ],

        'refund_order' => [
            'category' => ['ar' => 'الطلبات', 'en' => 'orders'],
            'single' => true,
            'name' => 'refund_order',
            'display_name' => [

                "ar" => "إسترجاع الطلب",
                "en" => "Refund Order"
            ],
        ],

        'confirm_payment_order' => [
            'category' => ['ar' => 'الطلبات', 'en' => 'orders'],
            'single' => true,
            'name' => 'confirm_payment_order',
            'display_name' => [

                "ar" => "تأكيد دفع الطلب",
                "en" => "Confirm payment Order"
            ],
        ],
        //////////////// end single orders permissions


        'show_client_settings' => [
            'category' => ['ar' => 'الإعدادات العامة', 'en' => 'client settings'],
            'single' => true,
            'name' => 'show_client_settings',
            'display_name' => [
                'en' => 'Show',
                'ar' => 'عرض ',
            ],
        ],

        'show_logs' => [
            'category' => ['ar' => 'سجل العمليات', 'en' => 'logs'],
            'single' => true,
            'name' => 'show_logs',
            'display_name' => [
                'en' => 'Show Logs',
                'ar' => 'عرض سجل العمليات',
            ],
        ],
    ];

    public function run()
    {
        foreach ($this->permissions as $route => $options) {
            if (isset($options['single']) && $options['single']) {

                $this->createPermission($options['name'], $options['category'], $options['display_name']);
            } else {


                foreach ($this->mapKey as $key => $value) {
                    $this->createPermission($key . "_" . $route, $options['display_name'], $value['lang']);
                }
            }
        }
    }

    public function createPermission($name, $display_name, $description)
    {
        $perm = Permission::where([
            "name" => $name,
        ])->first();

        if ($perm) {
            $perm->update([
                "name" => $name,
                "description" => $description,
                'display_name' => $display_name,
            ]);
        } else {
            Permission::create([
                "name" => $name,
                "description" => $description,
                'display_name' => $display_name,
            ]);
        }
    }
}
