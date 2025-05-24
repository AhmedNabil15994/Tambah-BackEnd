<?php

return [
    'store_path' => public_path("/"),
    "type" => [
        "PDF417", "C39", "C39+", "C39E", "C39E+",
        "C93", "S25", "S25+", "I25", "I25+", "C128", "C128A", "C128B"
        // , "C128C"

    ],
    "default_type" => "C39+",
];
