<?php
return [
    // Example
    /*[
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => [
            'v1/user',
        ],
    ],*/
    // Example of compund primary key
    /*[
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => 'v1/compound-model',
        'extraPatterns' => [
            'GET test' => 'compund-model/test',
        ],
        'tokens' => [
            '{id}' => '<id:\\d+,\\w+>',
        ],
    ]*/
    // Example of a custom defined endpoint
    'GET service-status' => 'site/status',
];
