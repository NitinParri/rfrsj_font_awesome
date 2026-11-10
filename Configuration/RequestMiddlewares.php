<?php

declare(strict_types=1);

use Refresj\RfrsjFontAwesome\Middleware\AddFontAwesomeCss;

return [
    'frontend' => [
        'rfrsj/font-awesome-css' => [
            'target' => AddFontAwesomeCss::class,
            'after' => [
                'typo3/cms-frontend/site',
            ],
            'before' => [
                'typo3/cms-frontend/page-resolver',
            ],
        ],
    ],
];
