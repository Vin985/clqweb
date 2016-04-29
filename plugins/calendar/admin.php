<?php

namespace Clq;

try {
    $calendar = new Calendar();
} catch (\Exception $e) {
    $msg = $e->getMessage();
}

$page = isset($_GET['edit']) ? 'edit' : 'events';

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'delete') {
        if ($calendar->deleteEvent()) {
            $msg = i18n_r('calendar/DELETE_SUCCESS');
            $success = true;
        } else {
            $msg = i18n_r('calendar/DELETE_FAILURE');
        }
    } elseif ($_POST['action'] == 'save') {
        if ($calendar->saveEvent()) {
            $msg = i18n_r('calendar/SAVE_SUCCESS');
            $success = true;
        } else {
            $msg = i18n_r('calendar/SAVE_FAILURE');
        }
    } elseif ($_POST['action'] == 'edit') {
        $page = 'edit';
    }
}

?>

<h3 class="floated">
    <?php  i18n('calendar/calendar');  ?>
</h3>
<div class="edit-nav clearfix">
    <a href="load.php?id=calendar&amp;events"
    <?php if ($page == 'events') {
        echo 'class="current"';
}   ?>>
    <?php i18n('calendar/events'); ?></a>
    <?php /*  <a href="load.php?id=calendar&amp;calendar" <?php if (isset($_GET[ 'calendar'])) {
        echo 'class="current"';
} ?>><?php i18n('calendar/calendar'); ?></a> */ ?>
    <a href="load.php?id=calendar&amp;edit" <?php if ($page == 'edit') {
        echo 'class="current"';
} ?>><?php i18n('calendar/add'); ?></a>
</div>
<?php


if ($page == 'events') {
    # Events
    include 'inc/admin_events.php';

} /*elseif (isset($_GET['calendar'])) {
    # Calendar
    include 'inc/admin_calendar.php';

} */ elseif ($page == 'edit') {
    # Edit Event
    include 'inc/admin_edit.php';

}
?>
