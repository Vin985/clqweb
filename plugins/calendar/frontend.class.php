<?php

namespace Clq;

class CalendarFrontend
{
    public static function displayEvents($admin = false)
    {
        global $LANG;
        if (function_exists('i18n_load_texts')) {
            i18n_load_texts('calendar');
        } else {
            i18n_merge('calendar', substr($LANG, 0, 2)) || i18n_merge('my_plugin', 'en');
        }
        require_once(GSPLUGINPATH . 'calendar/calendar.class.php');
        $text = '<div class="schedule">';
        $text .= '<div class="cal_title">'. i18n_r('calendar/EVENTS_LIST') .'</div>';
        $text .= '<div class="spacer"></div>';
        $calendar = new Calendar();
        $schedule = $calendar->getSchedule();
        $n = 0;
        foreach ($schedule->dates as $date) {
            $odd = ($n % 2 == 0);
            $text .= self::addDate($date, $admin, $odd);
            $n++;
        }
        $text .= '</div>';
        echo $text;
    }


    private static function addDate($date, $admin, $odd)
    {
        $text = '<div class="day row flex '. ($odd ? 'odd' : 'even') .'">';
        $text .= '<div class="date">';
        $disp_date = '';
        if ($date->date != '') {
            $day = strftime('%d', $date->date);
            $month = i18n_r('calendar/'. strftime('%B', $date->date));
            $disp_date = $day . ' '  . $month;
        }
        $text .= $disp_date;
        $text .= '</div>';
        $text .= '<div class="events stack">';
        $pos = 0;
        foreach ($date->events as $event) {
            $text .= '<div class="event flex">';
            $text .= '<div class="description">'. $event->description . '</div>';
            if ($admin) {
                $text .= self::addTools($date, $pos);
            }
            $text .= '</div>';
            $pos++;
        }
        $text .= '</div>';
        $text .= '</div>';
        return $text;
    }

    private static function addTools($date, $pos)
    {

        $text = '<div class="tools flex">';
        $text .= '<form method="post" id="calendarForm" action="load.php?id=calendar"
         accept-charset="utf-8">';
        $text .= '<input type="hidden" name="action" value="" />';
        $text .= '<input type="hidden" name="date" value="'. $date->date .'" />';
        $text .= '<input type="hidden" name="pos" value="'. $pos .'" />';
        // Edit event
        $text .= '<button type="button" class="edit button-img" tabindex="0"';
        $text .= 'title="Edit event"';
        $text .= 'onclick="javascript:change_form_action($(this).closest(\'form\'), \'edit\')"';
        $text .= '</button>';

      // Remove event
        $text .= '<button type="button" class="delete button-img" tabindex="0"';
        $text .= 'title="Remove event"';
        $text .= 'onclick="javascript:jConfirm(\'';
        $text .=  i18n_r('calendar/CONFIRM_DEL_EVENT');
        $text .= '\', \'Confirmation\',';
        $text .= 'function() { change_form_action($(this).closest(\'form\'), \'delete\')}.bind($(this)))"';
        $text .= '</button>';
        $text .= '</form>';

        $text .= '</div>';

        return $text;
    }
}
