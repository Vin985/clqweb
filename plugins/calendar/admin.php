<h3 class="floated">
  <?php  i18n('calendar/calendar');  ?>
</h3>
<div class="edit-nav clearfix">
    <a href="load.php?id=calendar&amp;events"
    <?php if (isset($_GET[ 'events'])) {
        echo 'class="current"';
}   ?>>
    <?php i18n('calendar/events'); ?></a>
    <?php /*  <a href="load.php?id=calendar&amp;calendar" <?php if (isset($_GET[ 'calendar'])) {
        echo 'class="current"';
} ?>><?php i18n('calendar/calendar'); ?></a> */ ?>
    <a href="load.php?id=calendar&amp;edit" <?php if (isset($_GET[ 'edit'])) {
        echo 'class="current"';
} ?>><?php i18n('calendar/add'); ?></a>
</div>
<?php
if (isset($_GET['delete'])) {
    # Delete
    $file = GSDATAOTHERPATH.'calendar/'.$_GET['delete'].'.xml';
    if (file_exists($file)) {
        unlink($file);

        $msg .= i18n_r('calendar/deleteSuccess').'!'
        ?>
    <script type="text/javascript">
        $(function() {
            $('div.bodycontent').before('<div class="<?php echo $isSuccess ? '
                updated ' : '
                error '; ?>" style="display:block;">' +
                <?php echo json_encode($msg); ?> + '</div>');
            $(".updated, .error").fadeOut(500).fadeIn(500);
        });
    </script>
    <?php
    }
}

if (isset($_GET['events'])) {
    # Events
    include 'inc/admin_events.php';

} /*elseif (isset($_GET['calendar'])) {
    # Calendar
    include 'inc/admin_calendar.php';

} */ elseif (isset($_GET['edit'])) {
    # Edit Event
    include 'inc/admin_edit.php';

}
?>
