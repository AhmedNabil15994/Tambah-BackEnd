<?php

return [
    'slider' => [
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'end_at' => 'End at',
            'image' => 'Image',
            'link' => 'Link',
            'options' => 'Options',
            'start_at' => 'Start at',
            'status' => 'Status',
            'type' => 'Type',
        ],
        'form' => [
            'end_at' => 'End at',
            'image' => 'Image',
            'link' => 'Link',
            'start_at' => 'Start at',
            'status' => 'Status',
            'short_title' => 'Short Title',
            'description' => 'Description',
            'title' => 'Title',
            'short_description' => 'Short Description',
            'tabs' => [
                'general' => 'General Info.',
            ],
            'products' => 'Products',
            'categories' => 'Categories',
            'slider_type' => [
                'label' => 'Link Type',
                'external' => 'External',
                'product' => 'Product',
                'category' => 'Category',
            ],
        ],
        'routes' => [
            'create' => 'Create slider images',
            'index' => 'slider images',
            'update' => 'Update slider images',
        ],
        'validation' => [
            'end_at' => [
                'required' => 'Please select slider image ent at',
            ],
            'image' => [
                'required' => 'Please select image of the slider image',
            ],
            'link' => [
                'required' => 'Please add the link of slider image',
                'required_if' => 'Please add the link of slider image',
            ],
            'start_at' => [
                'required' => 'Please select the date of started slider image',
            ],
            'title' => [
                'required' => 'Please add the title of slider',
            ],
            'slider_type' => [
                'required' => 'Please select the type of slider',
                'in' => 'This type of slider must be in',
            ],
            'product_id' => [
                'required_if' => 'Please select the product',
            ],
            'category_id' => [
                'required_if' => 'Please select the category',
            ],
        ],
    ],
    'banner' => [
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'end_at' => 'End at',
            'image' => 'Image',
            'link' => 'Link',
            'options' => 'Options',
            'start_at' => 'Start at',
            'status' => 'Status',
            'type' => 'Type',
        ],
        'form' => [
            'end_at' => 'End at',
            'image' => 'Image',
            'link' => 'Link',
            'start_at' => 'Start at',
            'status' => 'Status',
            'short_title' => 'Short Title',
            'description' => 'Description',
            'title' => 'Title',
            'short_description' => 'Short Description',
            'tabs' => [
                'general' => 'General Info.',
            ],
            'products' => 'Products',
            'categories' => 'Categories',
            'slider_type' => [
                'label' => 'Link Type',
                'external' => 'External',
                'product' => 'Product',
                'category' => 'Category',
            ],
        ],
        'routes' => [
            'create' => 'Create Banner images',
            'index' => 'Banner images',
            'update' => 'Update Banner images',
        ],
        'validation' => [
            'end_at' => [
                'required' => 'Please select Banner image ent at',
            ],
            'image' => [
                'required' => 'Please select image of the Banner image',
            ],
            'link' => [
                'required' => 'Please add the link of Banner image',
                'required_if' => 'Please add the link of Banner image',
            ],
            'start_at' => [
                'required' => 'Please select the date of started Banner image',
            ],
            'title' => [
                'required' => 'Please add the title of Banner',
            ],
            'slider_type' => [
                'required' => 'Please select the type of Banner',
                'in' => 'This type of Banner must be in',
            ],
            'product_id' => [
                'required_if' => 'Please select the product',
            ],
            'category_id' => [
                'required_if' => 'Please select the category',
            ],
        ],
    ],

    'instagrams' => [
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'image' => 'Image',
            'link' => 'Link',
            'options' => 'Options',
            'comments_count' => 'comments count',
            'likes_count' => 'likes count',
            'status' => 'Status',
            'type' => 'Type',
            'video' => 'video',
            'photo' => 'photo',
            'title' => 'title',
        ],
        'form' => [
            'title' => 'title',
            'video' => 'video',
            'photo' => 'photo',
            'comments_count' => 'comments count',
            'likes_count' => 'likes count',
            'image' => 'Image',
            'link' => 'Link',
            'type' => 'type',
            'status' => 'Status',
            'tabs' => [
                'general' => 'General Info.',
            ],
            'slider_type' => [
                'label' => 'Link Type',
                'external' => 'External',
            ],
        ],
        'routes' => [
            'create' => 'Create Instagram images',
            'index' => 'Instagram images',
            'update' => 'Update Instagram images',
        ],
        'validation' => [
            'end_at' => [
                'required' => 'Please select Instagram image ent at',
            ],
            'image' => [
                'required' => 'Please select image of the Instagram image',
            ],
            'link' => [
                'required' => 'Please add the link of Instagram image',
                'required_if' => 'Please add the link of Instagram image',
            ],
            'start_at' => [
                'required' => 'Please select the date of started Instagram image',
            ],
            'title' => [
                'required' => 'Please add the title of Instagram',
            ],
            'slider_type' => [
                'required' => 'Please select the type of Instagram',
                'in' => 'This type of Instagram must be in',
            ],
            'product_id' => [
                'required_if' => 'Please select the product',
            ],
            'category_id' => [
                'required_if' => 'Please select the category',
            ],
        ],
    ],
];
