<?php

namespace Clq;

class Rates
{

    private $rates;
    //private static $rates;

    public function __construct()
    {
        if (!$this->checkPrerequisites()) {
            throw new \Exception(i18n_r('rates/MISSING_DIR'));
        }
    }

    private function loadRates()
    {
        $this->rates = array();
        $data = array();
        $filename = GSDATAPATH.RATES_DIR.RATES_FILENAME;
        if (file_exists($filename)) {
            $data = getXML($filename);
            if ($data) {
                foreach ($data as $key => $value) {
                    if ($key == "category") {
                        $category = $this->extractCategory($value);
                        $this->rates[] = $category;
                    }
                }
            }
        }
    }

    private function extractCategory($data)
    {
        $category = array();
        foreach ($data as $key => $value) {
            if ($key == "label") {
                $category["label"] = (string) $value;
            } elseif ($key == "rate") {
                $category["rates"][] = $this->extractRate($value);
            }
        }
        return $category;
    }

    private function extractRate($data)
    {
        $rate = array();
        foreach ($data as $key => $value) {
            $rate[$key] = (string) $value;
        }
        return $rate;
    }

    public static function checkPrerequisites()
    {
        $success = true;
        $gdir = GSDATAPATH . RATES_DIR;
        if (!file_exists($gdir)) {
            $success = mkdir(substr($gdir, 0, strlen($gdir)-1), 0777) && $success;
            $fp = fopen($gdir . '.htaccess', 'w');
            fputs($fp, 'Deny from all');
            fclose($fp);
        }
        $gdir = GSBACKUPSPATH . RATES_DIR;
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

    public function getRates()
    {
        if (empty($this->rates)) {
            $this->loadRates();
        }
        return $this->rates;
    }

    public function saveRates()
    {
        $data = new \SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><rates></rates>');

        for ($catId = 0; isset($_POST['cat_name_'.$catId]); $catId++) {
            if (empty($_POST['cat_name_'.$catId])) {
                continue;
            }
            $category = $data->addChild("category");
            $category->addChild("label", $_POST['cat_name_'.$catId]);
            for ($rateId = 0; isset($_POST['name_'.$catId ."_" .$rateId]); $rateId++) {
                if (empty($_POST['name_'.$catId ."_" .$rateId])) {
                    continue;
                }
                $rate = $category->addChild("rate");
                $rate->addChild("name", $_POST['name_'.$catId ."_" .$rateId]);
                $rate->addChild("value", $_POST['value_'.$catId ."_" .$rateId]);
            }
            if (!XMLsave($data, GSDATAPATH.RATES_DIR.RATES_FILENAME)) {
                return false;
            }
            return true;
        }
    }

    public function saveUndo($name, $newname)
    {
        if ($name != $newname && !unlink(GSDATAPATH.I18N_GALLERY_DIR.$newname.'.xml')) {
            return false;
        }
        if (!copy(GSBACKUPSPATH.I18N_GALLERY_DIR.$name.'.xml', GSDATAPATH.I18N_GALLERY_DIR.$name.'.xml')) {
            return false;
        }
        return true;
    }
}
