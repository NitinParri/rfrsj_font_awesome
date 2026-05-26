<?php

declare(strict_types=1);

namespace Refresj\RfrsjFontAwesome\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Site\Entity\SiteSettings;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

final class AddFontAwesomeCss implements MiddlewareInterface
{
    public function __construct(
        private readonly PageRenderer $pageRenderer,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var SiteSettings $settings */
        $settings = $request->getAttribute('site')->getSettings();

        if ((bool)$settings->get('plugin.tx_rfrsjfontawesome.settings.loadCSS', false)) {
            $cssFiles = $settings->get('plugin.tx_rfrsjfontawesome.settings.cssFiles', []);

            if (is_array($cssFiles) && $cssFiles !== []) {
                foreach ($cssFiles as $file) {
                    // EXT: paden omzetten naar relatieve webpaden
                    if (PathUtility::isExtensionPath($file)) {
                        $file = PathUtility::getAbsoluteWebPath(
                            GeneralUtility::getFileAbsFileName($file)
                        );
                    }

                    $this->pageRenderer->addCssFile($file);
                }
            }
        }

        return $handler->handle($request);
    }
}