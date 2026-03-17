# Pixel Integration for Snapchat — Translations

This directory contains translations for the Pixel Integration for Snapchat plugin.

## How to Create Translations

1. Use a tool like Poedit (https://poedit.net/) to create translations.
2. Scan the plugin directory for translatable strings.
3. Create a .po file for your language (e.g., snap-pixel-fr_FR.po for French).
4. Save the file, which will generate both .po and .mo files.
5. Place both files in this directory.

## Translation Files Naming Convention

- .po file: snap-pixel-{locale}.po (e.g., snap-pixel-fr_FR.po)
- .mo file: snap-pixel-{locale}.mo (e.g., snap-pixel-fr_FR.mo)

## Translatable Strings

All user-facing strings in the plugin are wrapped in translation functions:
- `__()` - For simple strings
- `_e()` - For echoed strings
- `sprintf(__(), ...)` - For strings with variables
- `esc_html__()` - For escaped HTML strings
- `esc_attr__()` - For escaped attribute strings

## JavaScript Translations

JavaScript strings use the wp.i18n functions:
- `__()` - For simple strings
- `_x()` - For strings with context
- `_n()` - For plurals
- `sprintf()` - For strings with variables

## Need Help?

If you need assistance with translations, please contact the plugin author. 