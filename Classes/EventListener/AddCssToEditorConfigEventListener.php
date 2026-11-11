<?php

declare(strict_types=1);

namespace Refresj\RfrsjFontAwesome\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\RteCKEditor\Form\Element\Event\AfterPrepareConfigurationForEditorEvent;

#[AsEventListener(identifier: 'rfrsj-font-awesome/add-css-to-editor-config')]
final class AddCssToEditorConfigEventListener
{
    public function __construct(
        private readonly SiteFinder $siteFinder,
    ) {}

    public function __invoke(AfterPrepareConfigurationForEditorEvent $event): void
    {
        $data = $event->getData();
        $pageId = (int)($data['effectivePid'] ?? 0);
        if ($pageId === 0) {
            return;
        }

        try {
            $site = $this->siteFinder->getSiteByPageId($pageId);
        } catch (SiteNotFoundException) {
            return;
        }

        $settings = $site->getSettings();

        $cssFiles = $settings->get('plugin.tx_rfrsjfontawesome.settings.cssFiles', []);
        if (!is_array($cssFiles) || $cssFiles === []) {
            return;
        }

        $cssMap = [];
        foreach ($cssFiles as $key => $file) {
            if (!is_string($file) || $file === '') {
                continue;
            }

            if (PathUtility::isExtensionPath($file)) {
                $absolutePath = GeneralUtility::getFileAbsFileName($file);
                if ($absolutePath === '') {
                    continue;
                }

                $file = PathUtility::getAbsoluteWebPath($absolutePath);
            }

            $cssMap[is_string($key) ? $key : 'file_' . $key] = $file;
        }

        if ($cssMap === []) {
            return;
        }

        $config = $event->getConfiguration();
        $config['ui']['RfrsjFontAwesome']['css'] = $cssMap;
        $event->setConfiguration($config);
    }
}
