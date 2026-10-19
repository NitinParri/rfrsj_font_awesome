<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgSpriteIconProvider;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

$icons = [];

try {
    $sites = GeneralUtility::makeInstance(SiteFinder::class)->getAllSites();
} catch (\Throwable) {
    return $icons;
}

$spriteFiles = [];

foreach ($sites as $site) {
    $settings = $site->getSettings();

    if ($settings->get('plugin.tx_rfrsjfontawesome.settings.useIconRegistration', false)) {
        foreach ((array)$settings->get('plugin.tx_rfrsjfontawesome.settings.spriteFiles', []) as $file) {
            $spriteFiles[$file] = $file;
        }
    }
}

foreach (array_keys($spriteFiles) as $file) {
    if (PathUtility::isExtensionPath($file)) {
        $absolutePath = GeneralUtility::getFileAbsFileName($file);
        $webPath = PathUtility::getAbsoluteWebPath($absolutePath);
    } else {
        $absolutePath = rtrim(Environment::getPublicPath(), '/') . '/' . ltrim($file, '/');
        $webPath = $file;
    }

    $content = is_file($absolutePath) ? file_get_contents($absolutePath) : false;

    if ($content === false) {
        GeneralUtility::makeInstance(LogManager::class)->getLogger('rfrsj_font_awesome')
            ->warning('Font Awesome sprite file not found or unreadable, skipping', ['file' => $absolutePath]);

        continue;
    }

    $style = pathinfo($file, PATHINFO_FILENAME);

    preg_match_all('/<symbol[^>]*\sid="([^"]+)"/', $content, $matches);

    foreach ($matches[1] as $name) {
        $icons['fa-' . $style . '-' . $name] = [
            'provider' => SvgSpriteIconProvider::class,
            'sprite' => $webPath . '#' . $name,
        ];
    }
}

return $icons;
