<?php

global $i18n;
print_r($i18n);


function i18n_merge_impl($plugin, $lang, &$globali18n)
{

    $i18n = array(); // local from file
    if (!isset($globali18n)) {
        $globali18n = array(); //global ref to $i18n
    }
    $path     = ($plugin ? GSPLUGINPATH.$plugin.'/lang/' : GSLANGPATH);
    $filename = $path.$lang.'.php';
    $prefix   = $plugin ? $plugin.'/' : '';

    if (!filepath_is_safe($filename, $path) || !file_exists($filename)) {
        return false;
    }

    include($filename);

  // if core lang and glboal is empty assign
    if (!$plugin && !$globali18n && count($i18n) > 0) {
        $globali18n = $i18n;
        return true;
    }

  // replace on per key basis
    if (count($i18n) > 0) {
        foreach ($i18n as $code => $text) {
            if (!array_key_exists($prefix.$code, $globali18n)) {
                $globali18n[$prefix.$code] = $text;
            }
        }
    }
    return true;
}
