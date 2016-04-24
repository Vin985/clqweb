<?php

/*
Plugin Name: Rates
Description: Change rates for the camping
Version: 1.0.0
Author: Camping le Quebecois
Author URI: http://www.campinglequebecois.qc.ca
*/

# get correct id for plugin
$thisfile = basename(__FILE__, ".php");

define('RATES_DIR', 'rates/');
define('RATES_FILENAME', 'rates');

# register plugin
register_plugin(
    $thisfile,
    'Rates',
    '1.0.0',
    'Camping le Quebecois',
    'http://www.campinglequebecois.qc.ca',
    'Change rates for the camping',
    'rates',
    'rates_main'
);

# load i18n texts
if (basename($_SERVER['PHP_SELF']) != 'index.php') { // back end only
    i18n_merge('rates', substr($LANG, 0, 2));
    i18n_merge('rates', 'en');
}

# activate filter
add_action('header', 'rates_header');
add_action('footer', 'rates_footer');
add_action('nav-tab', 'createNavTab', array('tarifs', $thisfile, i18n_r('rates/TAB'), 'edit'));


# ===== BACKEND PAGES =====

function rates_register($type, $name, $description, $edit_function, $header_function, $content_function)
{
    require_once(GSPLUGINPATH.'rates/rates.class.php');
    return Rates::registerPlugin($type, $name, $description, $edit_function, $header_function, $content_function);
}

function rates_main()
{
    if (isset($_GET['edit'])) {
        include(GSPLUGINPATH.'rates/edit.php');
    }
}

# ===== BACKEND HOOKS =====

function rates_header()
{
    include(GSPLUGINPATH.'rates/header.php');
}

function rates_footer()
{
    include(GSPLUGINPATH.'rates/footer.php');
}

function display_rates()
{
    require_once(GSPLUGINPATH.'rates/frontend.class.php');
    return \Clq\RatesFrontend::displayRates();
}
