<?php

return [

    'maintenance-mode' => [

        /**
         * HTTP Status Code to return when in maintenance mode
         */
        'status-code' => 503,

        /**
         * Location of the Lock File
         */
        'lock-file' => __DIR__ . '/../maintenance-mode',

        /**
         * HTML file or other to return as the response
         */
        'response-file' => __DIR__ . '/../data/Maintenance.html',

        /**
         * Content type header for response
         */
        'response-type' => 'text/html',
    ],

    'console' => [
        'router' => [
            'routes' => [
                'netglue/maintenance' => [
                    'options' => [
                        'route'    => 'maintenance [--verbose|-v]',
                        'defaults' => [
                            'controller' => 'NetglueMaintenanceMode\Controller\ConsoleController',
                            'action'     => 'toggle',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
