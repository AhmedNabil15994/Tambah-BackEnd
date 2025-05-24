<?php
return [
    'occasion'  => [
        'oops_error'        =>  'Something went wrong',
        'validation'    =>  [
            'name' => [
                'required'          => 'Occasion name is required',
                'string'            => 'Occasion name must be a text value',
                'max'               => 'Occasion name cannot exceed 190 characters',
            ],
            'category_id' => [
                'required'          => 'Category is required',
                'exists'            => 'This category is not found',
            ],
            'occasion_date' => [
                'required'          => 'Occasion date is required',
                'date'              => 'Occasion date must be a date',
            ],
        ],
    ],
];