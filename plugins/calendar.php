<?php

include 'calendar/hooks.php';
# calendarMini
include 'calendar/inc/client_calendarMini.php';
# calendarEvents
include 'calendar/inc/client_calendarEvents.php';

$thisfile = basename(__FILE__, '.php');

register_plugin(
    $thisfile,
    'Calendar',
    '1.8',
    'clq',
    '',
    'Plugin to create camping calendar',
    'calendar',
    'calendar_main'
);

# language
# load i18n texts
if (basename($_SERVER['PHP_SELF']) != 'index.php') { // back end only
    i18n_merge('calendar', 'fr_FR');
    i18n_merge('calendar', 'en_US');
}

# activate filter
add_action('header', 'calendar_header');
add_action('footer', 'calendar_footer');
add_action('nav-tab', 'createNavTab', array('calendar', $thisfile, i18n_r('calendar/calendar'), 'calendar'));


/*
# $_GET check
if (!isset($_GET['calendar']) and !isset($_GET['settings']) and !isset($_GET['edit'])) {
    $_GET['events'] = true;
}*/
