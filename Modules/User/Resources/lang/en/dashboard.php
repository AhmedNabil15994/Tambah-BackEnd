<?php

return [
    'subscribe' => [
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'options' => 'Options',
            'email' => 'Email',
        ],
        'routes' => [
            'create' => 'Create Subscriptions',
            'index' => 'Subscriptions',
            'update' => 'Update Subscribe',
        ],
    ],
    'admins' => [
        'create' => [
            'form' => [
                'confirm_password' => 'Confirm Password',
                'email' => 'Email',
                'general' => 'General Info.',
                'image' => 'Profile Image',
                'info' => 'Info.',
                'mobile' => 'Mobile',
                'name' => 'Name',
                'password' => 'Password',
                'roles' => 'Roles',
            ],
            'title' => 'Create Employees',
        ],
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'email' => 'Email',
            'image' => 'Image',
            'mobile' => 'Mobile',
            'name' => 'Name',
            'roles' => 'Roles',
            'options' => 'Options',
        ],
        'index' => [
            'title' => 'Employees',
        ],
        'update' => [
            'form' => [
                'confirm_password' => 'Confirm Password',
                'email' => 'Email',
                'general' => 'General info.',
                'image' => 'Profile Image',
                'mobile' => 'Mobile',
                'name' => 'Name',
                'password' => 'Change Password',
                'roles' => 'Roles',
            ],
            'title' => 'Update Employees',
        ],
        'validation' => [
            'email' => [
                'required' => 'Please enter the email of admin',
                'unique' => 'This email is taken before',
            ],
            'mobile' => [
                'digits_between' => 'Please add mobile number only 8 digits',
                'numeric' => 'Please enter the mobile only numbers',
                'required' => 'Please enter the mobile of admin',
                'unique' => 'This mobile is taken before',
            ],
            'name' => [
                'required' => 'Please enter the name of admin',
            ],
            'password' => [
                'min' => 'Password must be more than 6 characters',
                'required' => 'Please enter the password of admin',
                'same' => 'The Password confirmation not matching',
            ],
            'roles' => [
                'required' => 'Please select the role of admin',
            ],
        ],
    ],
    'drivers' => [
        'create' => [
            'form' => [
                'confirm_password' => 'Confirm Password',
                'email' => 'Email',
                'general' => 'General Info.',
                'image' => 'Profile Image',
                'info' => 'Info.',
                'mobile' => 'Mobile',
                'name' => 'Name',
                'password' => 'Password',
                'roles' => 'Roles',
                'company' => 'Company',
            ],
            'title' => 'Create Drivers',
        ],
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'email' => 'Email',
            'image' => 'Image',
            'mobile' => 'Mobile',
            'name' => 'Name',
            'options' => 'Options',
            'company' => 'Company',
        ],
        'index' => [
            'title' => 'Drivers',
        ],
        'update' => [
            'form' => [
                'confirm_password' => 'Confirm Password',
                'email' => 'Email',
                'general' => 'General info.',
                'image' => 'Profile Image',
                'mobile' => 'Mobile',
                'name' => 'Name',
                'password' => 'Change Password',
                'roles' => 'Roles',
            ],
            'title' => 'Update Drivers',
        ],
        'validation' => [
            'email' => [
                'required' => 'Please enter the email of driver',
                'unique' => 'This email is taken before',
            ],
            'mobile' => [
                'digits_between' => 'Please add mobile number only 8 digits',
                'numeric' => 'Please enter the mobile only numbers',
                'required' => 'Please enter the mobile of admin',
                'unique' => 'This mobile is taken before',
            ],
            'name' => [
                'required' => 'Please enter the name of admin',
            ],
            'password' => [
                'min' => 'Password must be more than 6 characters',
                'required' => 'Please enter the password of admin',
                'same' => 'The Password confirmation not matching',
            ],
            'roles' => [
                'required' => 'Please select the role of driver',
            ],
            'company_id' => [
                'required' => 'Please select the shipping company of driver',
            ],
        ],
    ],
    'sellers' => [
        'create' => [
            'form' => [
                'confirm_password' => 'Confirm Password',
                'email' => 'Email',
                'general' => 'General Info.',
                'image' => 'Profile Image',
                'info' => 'Info.',
                'mobile' => 'Mobile',
                'name' => 'Name',
                'password' => 'Password',
                'roles' => 'Roles',
            ],
            'title' => 'Create Sellers',
        ],
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'email' => 'Email',
            'image' => 'Image',
            'mobile' => 'Mobile',
            'name' => 'Name',
            'options' => 'Options',
        ],
        'index' => [
            'title' => 'Sellers',
        ],
        'update' => [
            'form' => [
                'confirm_password' => 'Confirm Password',
                'email' => 'Email',
                'general' => 'General info.',
                'image' => 'Profile Image',
                'mobile' => 'Mobile',
                'name' => 'Name',
                'password' => 'Change Password',
                'roles' => 'Roles',
            ],
            'title' => 'Update Seller',
        ],
        'validation' => [
            'email' => [
                'required' => 'please enter the email of seller',
                'unique' => 'This email is taken before',
            ],
            'mobile' => [
                'digits_between' => 'Please add mobile number only 8 digits',
                'numeric' => 'Please enter the mobile only numbers',
                'required' => 'Please enter the mobile of seller',
                'unique' => 'This mobile is taken before',
            ],
            'name' => [
                'required' => 'Please enter the name of seller',
            ],
            'password' => [
                'min' => 'Password must be more than 6 characters',
                'required' => 'Please enter the password of seller',
                'same' => 'The Password confirmation not matching',
            ],
            'roles' => [
                'required' => 'Please select the role of seller',
            ],
        ],
    ],
    'users' => [
        'create' => [
            'form' => [
                'confirm_password' => 'Confirm Password',
                'email' => 'Email',
                'general' => 'General Info.',
                'image' => 'Profile Image',
                'info' => 'Info.',
                'mobile' => 'Mobile',
                'name' => 'Name',
                'password' => 'Password',
            ],
            'title' => 'Create Clients',
        ],
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'email' => 'Email',
            'image' => 'Image',
            'mobile' => 'Mobile',
            'name' => 'Name',
            'order_count' => 'Success Orders Count',
            'options' => 'Options',
        ],
        'index' => [
            'title' => 'Clients',
        ],
        'update' => [
            'form' => [
                'confirm_password' => 'Confirm Password',
                'email' => 'Email',
                'general' => 'General info.',
                'image' => 'Profile Image',
                'mobile' => 'Mobile',
                'name' => 'Name',
                'password' => 'Change Password',
            ],
            'title' => 'Update User',
        ],
        'validation' => [
            'email' => [
                'required' => 'Please enter the email of user',
                'unique' => 'This email is taken before',
            ],
            'mobile' => [
                'digits_between' => 'Please add mobile number only 8 digits',
                'numeric' => 'Please enter the mobile only numbers',
                'required' => 'Please enter the mobile of user',
                'unique' => 'This mobile is taken before',
            ],
            'name' => [
                'required' => 'Please enter the name of user',
            ],
            'password' => [
                'min' => 'Password must be more than 6 characters',
                'required' => 'Please enter the password of user',
                'same' => 'The Password confirmation not matching',
            ],
        ],
    ],
    'cashiers'   => [
        'create'    => [
            'form'  => [
                'confirm_password'  => 'Confirm Password',
                'email'             => 'Email',
                'general'           => 'General Info.',
                'image'             => 'Profile Image',
                'info'              => 'Info.',
                'mobile'            => 'Mobile',
                'name'              => 'Name',
                'password'          => 'Password',
                'roles'             => 'Roles',
                "branch_id"         => "Branch ",
            ],
            'title' => 'Create Cashiers',
        ],
        'datatable' => [
            'created_at'    => 'Created At',
            'date_range'    => 'Search By Dates',
            'email'         => 'Email',
            'image'         => 'Image',
            'mobile'        => 'Mobile',
            'name'          => 'Name',
            'options'       => 'Options',
            'roles'       => 'Roles',
        ],
        'index'     => [
            'title' => 'Cashiers',
        ],
        'update'    => [
            'form'  => [
                'confirm_password'  => 'Confirm Password',
                'email'             => 'Email',
                'general'           => 'General info.',
                'image'             => 'Profile Image',
                'mobile'            => 'Mobile',
                'name'              => 'Name',
                'password'          => 'Change Password',
                'roles'             => 'Roles',
                "branch_id"         => "Branch ",
            ],
            'title' => 'Update Cashier',
        ],
        'validation' => [
            'email'     => [
                'required'  => 'please enter the email of seller',
                'unique'    => 'This email is taken before',
            ],
            'mobile'    => [
                'digits_between'    => 'Please add mobile number only 8 digits',
                'numeric'           => 'Please enter the mobile only numbers',
                'required'          => 'Please enter the mobile of seller',
                'unique'            => 'This mobile is taken before',
            ],
            'name'      => [
                'required'  => 'Please enter the name of seller',
            ],
            'password'  => [
                'min'       => 'Password must be more than 6 characters',
                'required'  => 'Please enter the password of seller',
                'same'      => 'The Password confirmation not matching',
            ],
            'roles'     => [
                'required'  => 'Please select the role of seller',
            ],
        ],
    ],
];
