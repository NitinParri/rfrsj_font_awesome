<?php

return [
    'frontend' => [
        'rfrsj/font-awesome-css' => [
            'target' => \Refresj\RfrsjFontAwesome\Middleware\AddFontAwesomeCss::class,
            'after' => [
                'typo3/cms-frontend/site',
            ],
            'before' => [
                'typo3/cms-frontend/page-resolver',
            ],
        ],
    ],
];
