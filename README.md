# Font Awesome for CKEditor

TYPO3 extension that integrates Font Awesome 7 Pro+ icons into the CKEditor 5 rich text editor. Provides a toolbar button to browse and insert Font Awesome icons directly into content with extra options. *Inspired by the extension rte_ckeditor_fontawesome.*

## Features

- Browse and insert Font Awesome icons via a modal dialog in CKEditor 5
- Supports Font Awesome 7.2 Pro+ icon packs and all styles: Classic, Sharp, Duotone, Sharp Duotone and Brands
- Filter icons by icon pack and style or search with keyword 
- Add extra options: Size, Animation, Rotation, Pull and/or Auto width
- CSS files configured once via Site Settings - automatically applied to CKEditor (and optional to the frontend)
- **Font Awesome is not included!** You have to include it yourself with the paths set in Site Settings.

## Requirements

| Requirement | Version |
|---|---|
| PHP | ^8.5 |
| TYPO3 CMS | ^14.3 |
| Font Awesome Pro | 7.2.x |

## Installation

Install the extension

## Configuration

### 1. Add the Site Set

Add `refresj/refresj-font-awesome` to your site's sets:

```yaml
sets:
  - refresj/refresj-font-awesome
```

### 2. Configure CSS files

After adding the Site Set, configure the Font Awesome CSS files via **Admin Tools → Sites → [your site] → Settings**:

| Setting | Description |
|---|---|
| `plugin.tx_rfrsjfontawesome.settings.loadCSS` | Enable or disable frontend CSS inclusion |
| `plugin.tx_rfrsjfontawesome.settings.cssFiles` | List of Font Awesome CSS file paths to include |

Both `EXT:` paths and absolute paths (e.g. `/fileadmin/...`) are supported:

```yaml
plugin.tx_rfrsjfontawesome.settings.cssFiles:
  - 'EXT:your_extension/Resources/Public/Css/Icons/all.css'
  - 'EXT:your_extension/Resources/Public/Css/Icons/brands.css'
  - 'EXT:your_extension/Resources/Public/Css/Icons/thin.css'
  - 'EXT:your_extension/Resources/Public/Css/Icons/light.css'
  - 'EXT:your_extension/Resources/Public/Css/Icons/regular.css'
  - 'EXT:your_extension/Resources/Public/Css/Icons/solid.css'
  - 'EXT:your_extension/Resources/Public/Css/Icons/sharp-thin.css'
  - 'EXT:your_extension/Resources/Public/Css/Icons/sharp-light.css'
  - 'EXT:your_extension/Resources/Public/Css/Icons/sharp-regular.css'
  - 'EXT:your_extension/Resources/Public/Css/Icons/sharp-solid.css'
  - 'EXT:your_extension/Resources/Public/Css/Icons/duotone-thin.css'
  - 'EXT:your_extension/Resources/Public/Css/Icons/duotone-light.css'
  - 'EXT:your_extension/Resources/Public/Css/Icons/duotone-regular.css'
  - 'EXT:your_extension/Resources/Public/Css/Icons/duotone.css'
  - 'EXT:your_extension/Resources/Public/Css/Icons/sharp-duotone-thin.css'
  - 'EXT:your_extension/Resources/Public/Css/Icons/sharp-duotone-light.css'
  - 'EXT:your_extension/Resources/Public/Css/Icons/sharp-duotone-regular.css'
  - 'EXT:your_extension/Resources/Public/Css/Icons/sharp-duotone-solid.css'
```

### 3. RTE configuration

The extension extends the three default TYPO3 RTE presets which can be used in your TCA or page TSconfig:

| Preset | Description |
|---|---|
| `default` | Standard editor configuration |
| `full` | Full editor configuration |
| `minimal` | Minimal editor configuration |

To use a preset in your TCA:

```php
'config' => [
    'type' => 'text',
    'enableRichtext' => true,
    'richtextConfiguration' => 'default',
],
```

## How it works

### Icon insertion

Clicking the Font Awesome toolbar button opens a modal dialog. The dialog allows you to:

- Select an icon pack: Brands, Classic, Sharp, Duotone, Sharp Duotone
- Select a style: Thin, Light, Regular, Solid
- Search icons by name
- Add Size, Animation, Rotation or toggle Auto width

Clicking an icon inserts an `<i>` element with the correct Font Awesome classes into the editor.

## Known Problems

If no icons are showing, you didn't add a correct path to the CSS file in Site Settings.

## License

This extension is released under the [GNU General Public License v2.0 or later](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html).

## Author

Nitin Parri — [refresj.nl](https://refresj.nl)