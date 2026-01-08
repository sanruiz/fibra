# Translations Required

This file lists the translations that need to be added after regenerating the `.pot` file.

## Commands to Run

```bash
# From theme root: wp-content/themes/soma/

# 1. Generate .pot template
wp i18n make-pot . languages/soma.pot --domain=soma --exclude=node_modules,vendor,tests

# 2. Update existing .po files
wp i18n update-po languages/soma.pot languages/

# 3. Add Spanish translations to languages/es_ES.po
# See translations list below

# 4. Compile .mo files
wp i18n make-mo languages/
```

## Spanish Translations to Add

After running `wp i18n update-po`, open `languages/es_ES.po` and add these translations:

### ShareQuotation Widget (4 strings)

```po
msgid "Show Volume"
msgstr "Mostrar Volumen"

msgid "Show Date"
msgstr "Mostrar Fecha"

msgid "Show Change"
msgstr "Mostrar Cambio"

msgid "Show Percentage"
msgstr "Mostrar Porcentaje"
```

### TeamMember Widget (1 string)

```po
msgid "Show Photo"
msgstr "Mostrar Foto"
```

## Verification

After adding translations and compiling:

1. Check that all new `msgid` entries have corresponding `msgstr` values
2. Verify no empty `msgstr ""` entries remain for new strings
3. Test widget controls in WordPress admin to verify translations display correctly

## Notes

- All new strings use the `soma` text domain
- Strings follow WordPress i18n best practices
- Use `esc_html__()` for label strings
- Use `__()` for general translatable strings
