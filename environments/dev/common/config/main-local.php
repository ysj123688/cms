<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2-restful',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'mediaUrlManager' => [
            'class' => '\yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'baseUrl' => 'http://media.yii-restful.dev',
        ],
        'i18n' => [
            'translations' => [
                'panel*' => [
                    'class' => 'yii\i18n\GettextMessageSource',
                    'basePath' => '@common/../messages',
                    'sourceLanguage' => 'en-US',
                    'useMoFile' => false,
                    'catalog' => 'panel',
                ],
                'frontend*' => [
                    'class' => 'yii\i18n\GettextMessageSource',
                    'basePath' => '@common/../messages',
                    'sourceLanguage' => 'es-ES',
                    'useMoFile' => false,
                    'catalog' => 'frontend',
                ],
                'common*' => [
                    'class' => 'yii\i18n\GettextMessageSource',
                    'basePath' => '@common/../messages',
                    'sourceLanguage' => 'en-US',
                    'useMoFile' => false,
                    'catalog' => 'common',
                ]
            ]
        ],
    ],
];
