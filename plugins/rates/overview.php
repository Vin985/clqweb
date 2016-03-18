<?php

require_once(GSPLUGINPATH.'rates/rates.class.php');

$success = false;
if (!\Clq\Rates::checkPrerequisites()) {
    $msg = i18n_r('rates/MISSING_DIR');
}

$rates = array();
$rfile = GSDATAPATH . RATES_DIR. RATES_FILENAME;
if (file_exists($rfile)) {
    $data = getXML($rfile);
}
