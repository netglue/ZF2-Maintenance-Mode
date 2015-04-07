<?php

return [

    'maintenance-mode' => [
        'lock-file' => __DIR__.'/../data/lock',
        'response-type' => 'foo/bar',
    ],

    // Set up error templates
    'view_manager' => [
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/500',
        'template_map' => [
            'error/404' => __DIR__.'/../data/error.phtml',
            'error/500' => __DIR__.'/../data/error.phtml',
            'layout/layout' => __DIR__.'/../data/layout.phtml',
        ],
    ],

];
