<?php

namespace Clq;

require_once(GSPLUGINPATH . 'calendar/frontend.class.php');

try {
    $calendar = new Calendar();
} catch (\Exception $e) {
    $msg = $e->getMessage();
}

if (isset($_GET['delete'])) {
  echo 'delete';
    if ($calendar->deleteEvent()) {
            $msg = i18n_r('calendar/DELETE_SUCCESS');
            $success = true;
    } else {
            $msg = i18n_r('calendar/DELETE_FAILURE');
    }
} elseif (isset($_POST['save'])) {
    if ($calendar->saveEvent()) {
            $msg = i18n_r('calendar/SAVE_SUCCESS');
            $success = true;
    } else {
            $msg = i18n_r('calendar/SAVE_FAILURE');
    }
}

CalendarFrontend::displayEvents(true);
