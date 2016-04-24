<?php

namespace Clq;

class Calendar
{

    private $rates;
    private $cur_lang;
    //private static $rates;

    public function __construct()
    {
        $this->cur_lang = return_i18n_languages()[0];
        if (!$this->checkPrerequisites()) {
            throw new \Exception(i18n_r('rates/MISSING_DIR'));
        }
    }


    public static function checkPrerequisites()
    {
        $success = true;
        $gdir = GSDATAPATH . CALENDAR_DIR;
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

    public function getRates()
    {
        $lang = isset($_POST['lang']) ? $_POST['lang'] : $this->cur_lang;
        if (empty($this->rates) || $lang != $this->cur_lang) {
            $this->loadRates($lang);
            if ($lang != $this->cur_lang) {
                $this->cur_lang = $lang;
            }
        }
        return $this->rates;
    }

    public function saveRates()
    {
        $data = new \SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><rates></rates>');

        $ncat = isset($_POST['ncat']) ? $_POST['ncat'] : 0;
        for ($catId = 0; $catId < $ncat; $catId++) {
            $catname = self::CATEGORY_NAME . $catId;
            if (!isset($_POST[$catname]) || empty($_POST[$catname])) {
                continue;
            }
            $category = $data->addChild("category");
            // Add category name
            $category->addChild("name", $_POST[$catname]);

            // Iterate on category prices
            for ($pos = 0; isset($_POST[self::CATEGORY_PRICE . $catId . "_" . $pos]); $pos++) {
                $category->addChild("price", $_POST[self::CATEGORY_PRICE . $catId . "_" . $pos]);
            }

            // Iterate on rates
            $nrate = isset($_POST['nrate_' . $catId]) ? $_POST['nrate_' . $catId] : 0;
            for ($rateId = 0; $rateId < $nrate; $rateId++) {
                $ratename = self::RATE_NAME . $catId . "_" . $rateId;
                if (!isset($_POST[$ratename]) || empty($_POST[$ratename])) {
                    continue;
                }
                $rate = $category->addChild("rate");
                $rate->addChild("name", $_POST[$ratename]);

                // Iterate on category prices
                for ($pos = 0; isset($_POST[self::RATE_PRICE . $catId . '_' . $rateId . '_' . $pos]); $pos++) {
                    $rate->addChild("price", $_POST[self::RATE_PRICE . $catId . '_' . $rateId . '_' . $pos]);
                }
            }
        }

        $lang = isset($_POST['lang']) ? $_POST['lang'] : return_i18n_default_language();

        if (!XMLsave($data, GSDATAPATH.RATES_DIR.RATES_FILENAME.'_'.$lang.'.xml')) {
            return false;
        }
        if ($lang != $this->cur_lang) {
            $this->cur_lang = $lang;
        }
        return true;
    }
}
