<?php

namespace Clq;

class CalendarFrontend
{
    public static function displayEvents($admin = false)
    {
        require_once(GSPLUGINPATH . 'calendar/calendar.class.php');
        $text = '<div class="schedule">';
        $calendar = new Calendar();
        $schedule = $calendar->getSchedule();
        //print_r($schedule);
        foreach ($schedule->dates as $date) {
            $text .= self::addDate($date, $admin);
        }
        $text .= '</div>';
        echo $text;
    }


    private static function addDate($date, $admin)
    {
      //print_r($date);
        $text = '<div class="day row flex">';
        $text .= '<div class="date">';
        $text .= date('d-m-Y', $date->date);
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
      // Edit event
        $text .= '<a class="edit button-img" tabindex="0"';
        $text .= 'title="Edit event"';
        $text .= 'href="load.php?id=calendar&amp;edit&amp;date='.$date->date;
        $text .= '&amp;pos='.$pos.'"';
        $text .= '</a>';

      // Remove event
        $text .= '<a class="delete button-img" tabindex="0"';
        $text .= 'title="Remove event"';
        $text .= 'href="#"';
        $text .= 'onclick="javascript:jConfirm(\'';
        $text .=  i18n_r('calendar/CONFIRM_DEL_EVENT');
        $text .= '\', \'Confirmation\',';
        $text .= 'function(confirmed) {if(confirmed) location.href=\'';
        $text .= 'load.php?id=calendar&amp;events&amp;date='.$date->date;
        $text .= '&amp;pos='.$pos.'&amp;delete\'})">';
        $text .= '</a>';

        $text .= '</div>';

        return $text;
    }
}
