# Font Awesome for CKEditor
[![Donate](https://img.shields.io/badge/PayPal-Donate-green?style=for-the-badge&logo=paypal)](https://paypal.me/NitinParri)
[![TYPO3](https://img.shields.io/badge/TYPO3-rfrsj__font__awesome-FF8600?style=for-the-badge&logo=typo3&logoColor=FF8600)](https://extensions.typo3.org/extension/rfrsj_font_awesome)
[![Repo](https://img.shields.io/badge/Github-repo-blue?style=for-the-badge&logo=github)](https://github.com/NitinParri/rfrsj_font_awesome)
[![Latest Stable version](https://img.shields.io/packagist/v/refresj/rfrsj-font-awesome?style=for-the-badge)](https://packagist.org/packages/refresj/rfrsj-font-awesome)

TYPO3 extension that integrates Font Awesome 7 Pro+ icons into the CKEditor 5 rich text editor. Provides a toolbar button to browse and insert Font Awesome icons directly into content with extra options. *Inspired by the extension rte_ckeditor_fontawesome.*

## Features

- Browse and insert Font Awesome icons via a modal dialog in CKEditor 5
- Supports Font Awesome 7.2 Pro+ icon packs with all styles: Classic, Sharp, Duotone, Sharp Duotone and Brands
- Filter icons by icon pack and style or search icons by name
- Set extra options: Size, Animation, Rotation, Pull and/or Auto width
- CSS files can be configured once via Site Settings and are automatically applied to CKEditor (and optionally to the frontend)
- **Font Awesome is not included!** You have to purchase a license and upload the required files so you can include the CSS files by setting the correct paths in Site Settings.

## Requirements

| Requirement | Version |
|---|---|
| PHP | ^8.5 |
| TYPO3 CMS | ^14.3 |

## Installation

Install the extension

## Configuration

### 1. Add the Site Set

Add set via ***Sites → Setup → Pencil icon button (bottom right of your site)*** and under `Sets for this Site` add the site set `Font Awesome for CKEditor [refresj/refresj-font-awesome]`.

Or add `refresj/refresj-font-awesome` to your site's or your Site Set's config.yaml:

```yaml
sets:
  - refresj/refresj-font-awesome
```

### 2. Upload Font Awesome files

1. Download [Font Awesome Pro+ for web](https://fontawesome.com/releases/7.2.0/pro-plus/web/download) (license required)
2. Create a new folder `FontAwesome` in ***Media → fileadmin/***
3. Upload the downloaded `css` and `webfonts` folders to the newly created folder

### 3. Configure CSS files

After adding the Site Set, configure the Font Awesome CSS files via ***Sites → Setup → Gear icon button (bottom right of your site)***:

| Setting | Description |
|---|---|
| `plugin.tx_rfrsjfontawesome.settings.loadCSS` | Enable or disable frontend CSS inclusion |
| `plugin.tx_rfrsjfontawesome.settings.cssFiles` | List of Font Awesome CSS file paths to include |

Both `EXT:` paths and absolute paths (e.g. `/fileadmin/...`) are supported:

```yaml
plugin.tx_rfrsjfontawesome.settings.cssFiles:
  - '/fileadmin/FontAwesome/css/fontawesome.min.css'
  - '/fileadmin/FontAwesome/css/brands.min.css'
  - '/fileadmin/FontAwesome/css/thin.min.css'
  - '/fileadmin/FontAwesome/css/light.min.css'
  - '/fileadmin/FontAwesome/css/regular.min.css'
  - '/fileadmin/FontAwesome/css/solid.min.css'
  - '/fileadmin/FontAwesome/css/sharp-thin.min.css'
  - '/fileadmin/FontAwesome/css/sharp-light.min.css'
  - '/fileadmin/FontAwesome/css/sharp-regular.min.css'
  - '/fileadmin/FontAwesome/css/sharp-solid.min.css'
  - '/fileadmin/FontAwesome/css/duotone-thin.min.css'
  - '/fileadmin/FontAwesome/css/duotone-light.min.css'
  - '/fileadmin/FontAwesome/css/duotone-regular.min.css'
  - '/fileadmin/FontAwesome/css/duotone.min.css'
  - '/fileadmin/FontAwesome/css/sharp-duotone-thin.min.css'
  - '/fileadmin/FontAwesome/css/sharp-duotone-light.min.css'
  - '/fileadmin/FontAwesome/css/sharp-duotone-regular.min.css'
  - '/fileadmin/FontAwesome/css/sharp-duotone-solid.min.css'
```

### 4. RTE configuration

The extension extends the three default TYPO3 RTE presets which can be used in your TCA or page TSconfig:

| Preset | Description |
|---|---|
| `default` | Standard editor configuration |
| `full` | Full editor configuration |
| `minimal` | Minimal editor configuration |

To use a preset in page TSconfig:

```typoscript
# Default for all RTE textareas:
RTE.default.preset = full

# Specific RTE textareas such as ext:powermail:
RTE.config.tt_content {
    settings\.flexform\.sender\.body.preset < RTE.default.preset
    settings\.flexform\.receiver\.body.preset < RTE.default.preset
}
```

To use a preset in your TCA:

```php
'config' => [
    'type' => 'text',
    'enableRichtext' => true,
    'richtextConfiguration' => 'full',
],
```

## How it works

### Icon insertion

Clicking the Font Awesome toolbar button (green flag icon) opens a modal dialog. The dialog allows you to:

- Select an icon pack: Brands, Classic, Sharp, Duotone, Sharp Duotone
- Select a style: Thin, Light, Regular, Solid
- Search icons by name
- Set extra options Size, Animation, Rotation, Pull and/or toggle Auto width

Clicking an icon inserts an `<i>` element with the correct Font Awesome classes into the editor.

## Known Problems

### No icons showing

If no icons are showing, you didn't add a correct path to the CSS file(s) in Site Settings.

### Extra options not inserted

You have to select them **before** you click on an icon.

### License lower than Pro+

If you have a Font Awesome license lower than Pro+, the extension will still work. Icons not included in your license tier will simply not be available.

## License

This extension is released under the [GNU General Public License v2.0 or later](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html).

## Author

Nitin Parri - [Refresj](https://refresj.nl)