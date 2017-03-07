<?php

namespace Clq;

class Calendar
{

    private $schedule;
    private $cur_lang;
    //private static $rates;

    public function __construct()
    {
        $this->cur_lang = return_i18n_languages()[0];
        if (!$this->checkPrerequisites()) {
            throw new \Exception(i18n_r('calendar/MISSING_DIR'));
        }
        $this->schedule = $this->loadSchedule();
    }


    public static function checkPrerequisites()
    {
        $success = true;
        $gdir = GSDATAOTHERPATH . CALENDAR_DIR;

        if (!file_exists($gdir)) {
            $success = mkdir(substr($gdir, 0, strlen($gdir)-1), 0777) && $success;
            $fp = fopen($gdir . '.htaccess', 'w');
            fputs($fp, 'Deny from all');
            fclose($fp);
        }
        $gdir = GSBACKUPSPATH . CALENDAR_DIR;
      // create directory if necessary
        if (!file_exists($gdir)) {
            $success = @mkdir(substr($gdir, 0, strlen($gdir)-1), 0777) && $success;
            $fp = @fopen($gdir . '.htaccess', 'w');
            if ($fp) {
                fputs($fp, 'Deny from all');
                fclose($fp);
            }
        }
        return $success;
    }

    public static function registerPlugin(
        $type,
        $name,
        $description,
        $edit_function,
        $header_function,
        $content_function
    ) {

        self::$plugins[$type] = array(
        'type' => $type,
        'name' => $name,
        'description' => $description,
        'edit' => $edit_function,
        'header' => $header_function,
        'content' => $content_function
        );
    }

    public function getCurrentLanguage()
    {
        return $this->cur_lang;
    }


    public function loadSchedule()
    {
        if (file_exists(GSDATAOTHERPATH.CALENDAR_DIR.'schedule.json')) {
            return json_decode(file_get_contents(GSDATAOTHERPATH.CALENDAR_DIR.'schedule.json'));
        }
          return new \StdClass();
    }

    public function getSchedule()
    {
        return $this->schedule;
    }

    public function findDate($day)
    {
        foreach ($this->schedule->dates as $date) {
            if ($day != '' && $date->date > $day) {
                break;
            }
            if ($date->date == $day) {
                return $date;
            }
        }
        return -1;
    }

    public function saveEvent()
    {
        $day = '';
        if ($_POST['date'] != '') {
            $day = strtotime($_POST['date']);
        }
        $edit = $_POST['edit'];

        $event = new \StdClass();
        $event->description = $_POST['post-content'];

        $found = false;
        $idx = 0;
        // Schedule is not empty
        if (!empty($this->schedule) && isset($this->schedule->dates)) {
            $dates = $this->schedule->dates;
            // Iterate on dates
            foreach ($dates as $date) {
                // Dates should be ordered. If greater, we got too far
                if ($day != '' && $date->date > $day) {
                    break;
                }
                // Date already exists
                if ($date->date == $day) {
                    // If we edit, just change contents
                    if ($edit && $_POST['pos'] != '') {
                        $date->events[$_POST['pos']]->description = $_POST['post-content'];
                    } else {
                      // else add new event
                        $date->events[] = $event;
                    }
                    $found = true;
                }
                $idx++;
            }
        }
        // day was not found, create new date and add it to collection
        if (!$found) {
            $date = new \StdClass();
            $date->date = $day;
            $date->events[] = $event;
            if (!empty($dates)) {
                if ($day == '') {
                    $idx = 0;
                }
                $start = array_slice($dates, 0, $idx);
                $end = array_slice($dates, $idx, (count($dates) - $idx));
                $start[] = $date;
                $dates = array_merge($start, $end);
            } else {
                $dates[] = $date;
            }
        }
        $this->schedule->dates = $dates;

        file_put_contents(GSDATAOTHERPATH.CALENDAR_DIR.'schedule.json', json_encode($this->schedule));
    }

    public function deleteEvent()
    {
        $day = $_POST['date'];
        $pos = $_POST['pos'];
        $idx = 0;
        foreach ($this->schedule->dates as &$date) {
            if ($date->date == $day) {
                // date has only one event, remove date
                if (count($date->events) == 1) {
                    array_splice($this->schedule->dates, $idx, 1);
                } else {
                    array_splice($date->events, $pos, 1);
                }
                file_put_contents(GSDATAOTHERPATH.CALENDAR_DIR.'schedule.json', json_encode($this->schedule));
                return true;
            }
            $idx++;
        }
        return false;
    }
}
