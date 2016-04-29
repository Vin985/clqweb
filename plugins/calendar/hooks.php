<?php

define('CALENDAR_DIR', 'calendar/');
define('CALENDAR_FILENAME', 'rates');

# ===== BACKEND PAGES =====

function calendar_register($type, $name, $description, $edit_function, $header_function, $content_function)
{
    require_once('calendar.class.php');
    return Calendar::registerPlugin($type, $name, $description, $edit_function, $header_function, $content_function);
}

function load_languages()
{
    global $LANG;
    if (basename($_SERVER['PHP_SELF']) != 'index.php') {
        i18n_merge('calendar', substr($LANG, 0, 2)) || i18n_merge('calendar', 'en');
    }
}

function calendar_main()
{
    require_once('calendar.class.php');
    include 'inc/calendar.php';
    include 'admin.php';
}

# ===== BACKEND HOOKS =====

function calendar_header()
{
    include('header.php');
}

function calendar_footer()
{
    //include('footer.php');
}

function display_calendar()
{
    require_once('frontend.class.php');
    return \Clq\CalendarFrontend::displayEvents();
}
