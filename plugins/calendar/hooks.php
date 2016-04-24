<?php

define('CALENDAR_DIR', 'calendar/');
define('CALENDAR_FILENAME', 'rates');

# ===== BACKEND PAGES =====

function calendar_register($type, $name, $description, $edit_function, $header_function, $content_function)
{
    require_once(GSPLUGINPATH.'rates/rates.class.php');
    return Rates::registerPlugin($type, $name, $description, $edit_function, $header_function, $content_function);
}

function calendar_main()
{
    include 'inc/calendar.php';
    include(GSPLUGINPATH.'calendar/admin.php');
}

# ===== BACKEND HOOKS =====

function calendar_header()
{
    include(GSPLUGINPATH.'calendar/header.php');
}

function calendar_footer()
{
    include(GSPLUGINPATH.'calendar/footer.php');
}

function display_calendar()
{
    require_once(GSPLUGINPATH.'calendar/frontend.class.php');
    return \Clq\CalendarFrontend::displayCalendar();
}
