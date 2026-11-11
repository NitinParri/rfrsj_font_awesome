<?php

declare(strict_types=1);

namespace Refresj\RfrsjFontAwesome\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

final class AddFontAwesomeCss implements MiddlewareInterface
{
    public function __construct(
        private readonly AssetCollector $assetCollector,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $site = $request->getAttribute('site');

        if ($site instanceof Site) {
            $settings = $site->getSettings();

            if ((bool)$settings->get('plugin.tx_rfrsjfontawesome.settings.loadCSS', false)) {
                foreach ((array)$settings->get('plugin.tx_rfrsjfontawesome.settings.cssFiles', []) as $key => $file) {
                    if (!is_string($file) || $file === '') {
                        continue;
                    }

                    if (PathUtility::isExtensionPath($file)) {
                        if (!is_file(GeneralUtility::getFileAbsFileName($file))) {
                            continue;
                        }
                    } elseif (!str_starts_with($file, 'URI:') && preg_match('#^(https?:)?//#i', $file) !== 1) {
                        $file = 'URI:' . $file;
                    }

                    $this->assetCollector->addStyleSheet('rfrsj-font-awesome-' . $key, $file, [], ['csp' => true]);
                }
            }
        }

        return $handler->handle($request);
    }
}
