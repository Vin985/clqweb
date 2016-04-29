<?php

namespace Clq;

try {
    $calendar = new Calendar();
} catch (\Exception $e) {
    $msg = $e->getMessage();
}


$date = date('d-m-Y');
$contents = '';
// edition
if (isset($_POST['date']) && isset($_POST['pos'])) {
    $day = $calendar->findDate($_POST['date']);
    if (!empty($day)) {
        $date = ($day->date != '') ? date('d-m-Y', $day->date) : '';
        $contents = $day->events[$_POST['pos']]->description;
    }
}

?>
<form action="load.php?id=calendar" class="schedule" method="POST">
    <input type="hidden" name="edit" value="<?php echo empty($_POST['date']) ? 'false' : 'true' ?>" />
    <input type="hidden" name="pos" value="<?php echo isset($_POST['pos']) ? $_POST['pos'] : '' ?>" />
    <input type="hidden" name="action" value="save" />
    <p>
        <label><?php i18n('calendar/date'); ?>:</label>
            <link type="text/css" href="../plugins/calendar/css/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
            <script type="text/javascript" src="../plugins/calendar/js/jquery-ui-1.8.17.custom.min.js"></script>
            <script type="text/javascript">
                $(function(){
                    // Datepicker
                    $('#datepicker').datepicker({
                        inline: true,
                        dateFormat: 'dd-mm-yy',
                        firstDay: 1,
                        nextText: '<?php i18n('calendar/next'); ?>',
                        prevText: '<?php i18n('calendar/prev'); ?>',
                        dayNames: <?php echo DAY_NAMES;?>,
                        dayNamesShort: <?php echo DAY_NAMES_SHORT;?>,
                        dayNamesMin: <?php echo DAY_NAMES_SHORT;?>,
                        monthNames: <?php echo MONTH_NAMES;?>,
                    });
                });
            </script>
        <input class="text short" id="datepicker" type="text" name="date" value="<?php echo $date; ?>" style="width: 150px;" maxlength="10" />
    </p>
    <p>
        <label><?php i18n('calendar/contents'); ?>:</label>

        <textarea name="post-content" id="post-content"><?php echo $contents; ?></textarea>
    </p>
    <input type="submit" class="submit" name='save' value="<?php i18n('calendar/save'); ?>" />
</form>
