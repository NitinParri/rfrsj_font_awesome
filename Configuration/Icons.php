<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgSpriteIconProvider;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

$icons = [];

$logger = GeneralUtility::makeInstance(LogManager::class)->getLogger('rfrsj_font_awesome');

try {
    $sites = GeneralUtility::makeInstance(SiteFinder::class)->getAllSites();
} catch (\Throwable $exception) {
    $logger->warning('Could not load sites, no Font Awesome icons registered', ['exception' => $exception]);

    return $icons;
}

$spriteFiles = [];

foreach ($sites as $site) {
    $settings = $site->getSettings();

    if ($settings->get('plugin.tx_rfrsjfontawesome.settings.useIconRegistration', false)) {
        foreach ((array)$settings->get('plugin.tx_rfrsjfontawesome.settings.spriteFiles', []) as $file) {
            if (is_string($file) && $file !== '') {
                $spriteFiles[$file] = $file;
            }
        }
    }
}

foreach (array_keys($spriteFiles) as $file) {
    if (PathUtility::isExtensionPath($file)) {
        $absolutePath = GeneralUtility::getFileAbsFileName($file);
    } else {
        $absolutePath = rtrim(Environment::getPublicPath(), '/') . '/' . ltrim($file, '/');
    }

    $content = is_file($absolutePath) ? file_get_contents($absolutePath) : false;

    if ($content === false) {
        $logger->warning('Font Awesome sprite file not found or unreadable, skipping', ['file' => $file, 'path' => $absolutePath]);

        continue;
    }

    preg_match_all('/<symbol[^>]*\sid="([^"]+)"/', $content, $matches);

    if ($matches[1] === []) {
        $logger->warning('Font Awesome sprite file contains no <symbol id="..."> elements, skipping', ['file' => $file, 'path' => $absolutePath]);

        continue;
    }

    $style = pathinfo($file, PATHINFO_FILENAME);
    $webPath = PathUtility::getAbsoluteWebPath($absolutePath);

    foreach ($matches[1] as $name) {
        $icons['fa-' . $style . '-' . $name] = [
            'provider' => SvgSpriteIconProvider::class,
            'sprite' => $webPath . '#' . $name,
        ];
    }
}

return $icons;
