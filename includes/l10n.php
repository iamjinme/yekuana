<?php
// Adapted from wp

function get_locale() {
    global $locale;
    global $CFG;

    if (isset($locale))
        return $locale;

    // locale defined in config.php
    if (!empty($CFG->locale))
        $locale = $CFG->locale;

    if (empty($locale))
        $locale = '';

    return $locale;
}

// Return a translated string.
function __($text, $domain = 'default') {
    global $l10n;

    if (isset($l10n[$domain]))
        return $l10n[$domain]->translate($text);
    else
        return $text;
}

// Echo a translated string.
function _e($text, $domain = 'default') {
    global $l10n;

    if (isset($l10n[$domain]))
        echo $l10n[$domain]->translate($text);
    else
        echo $text;
}

// Return the plural form.
function __ngettext($single, $plural, $number, $domain = 'default') {
    global $l10n;

    if (isset($l10n[$domain])) {
        return $l10n[$domain]->ngettext($single, $plural, $number);
    } else {
        if ($number != 1)
            return $plural;
        else
            return $single;
    }
}

function load_textdomain($domain, $mofile) {
    global $l10n;

    if (isset($l10n[$domain]))
        return;

    if (is_readable($mofile))
        $input = new CachedFileReader($mofile);
    else
        return;

    $l10n[$domain] = new gettext_reader($input);
}

function load_default_textdomain() {
    global $l10n;
    global $CFG;

    $locale = get_locale();
    if (empty($locale) )
        $locale = 'es_BO';

    $mofile = $CFG->incdir . "languages/{$locale}/yupana.mo";

    load_textdomain('default', $mofile);
}

function languages_available() {
    global $CFG;

    $langs = array();
    $lang_mos = $CFG->incdir . 'languages/*/yupana.mo';

    foreach (glob($lang_mos) as $lang) {
        preg_match('#languages/(.+)/yupana\.mo$#', $lang, $matches);
        $langs[] = $matches[1];
    }

    return $langs;
}

?>
