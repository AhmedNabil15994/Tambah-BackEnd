<?php

return [
    'login' => [
        'form'          => [
            'btn'       => [
                'login' => 'Sign into your account',
            ],
            'email'     => 'ِEmail address',
            'password'  => 'Password',
            "header"=>"Lets get started!"
        ],
        'routes'        => [
            'index' => 'Login',
        ],
        'validations'   => [
            'email'     => [
                'email'     => 'Please add correct email format',
                'required'  => 'Please add your email address',
            ],
            'failed'    => 'These credentials do not match our records.',
            'password'  => [
                'min'       => 'Password must be more than 6 characters',
                'required'  => 'The password field is required',
            ],
        ],
    ],
    "home"=>[
        'form'          => [
            
            
        ],
        "total_money"=> "Total Money",
        "Full Screen"=> "Full Screen",
        "Edit Profile"=> "Edit Profile",
        'routes'        => [
            'index' => 'Point Sale :name',
        ],
    ]
];
