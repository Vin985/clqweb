<?php
namespace Clq;

require_once(GSPLUGINPATH . 'rates/rates.class.php');
require_once(GSPLUGINPATH . 'rates/helper.php');


global $SITEURL;

$languages = array();
if (function_exists('return_i18n_available_languages')) {
    $languages = return_i18n_available_languages();
}

try {
    $rates = new Rates();
} catch (\Exception $e) {
    $msg = $e->getMessage();
}

$success = false;
$name = @$_GET['name'];
if (isset($_POST['save'])) {
    if ($rates->saveRates()) {
        $msg = i18n_r('rates/SAVE_SUCCESS');
        $success = true;
    } else {
        $msg = i18n_r('rates/SAVE_FAILURE');
    }
} elseif (isset($_POST['load'])) {
    if ($rates->getRates()) {
        $success = true;
    }
}

if (count($languages) != 0) {
    $sel = '';
    $langs = '';
    foreach ($languages as $lang) {
        if ($lang == $rates->getCurrentLanguage()) {
            $sel="selected";
        }
        $langs .= '<option '.$sel.' value="'.$lang.'" >'.$lang.'</option>';
        $sel = '';
    }
} else {
    $langs = '<option value="" selected="selected" >-- '.i18n_r('NONE').' --</option>';
}

//print_r($rates->getRates());

$catId = 0;
$categories = $rates->getRates();

?>

  <form method="post" id="ratesForm" action="load.php?id=rates&amp;edit"
  class="rates_form" accept-charset="utf-8">

      <div class="language">
        <label for="lang" ><?php i18n('LANGUAGE');?>:</label>
          <select name="lang" id="lang" class="text">
                <?php echo $langs; ?>
          </select>
          <div class="submit" style="display: inline-block">
            <input type="submit" name="load" value="<?php i18n('rates/CHANGE_LANGUAGE'); ?>" class="submit" />
          </div>
      </div>
        <?php
        $ncat = count($categories);
        foreach ($categories as $category) {
            $lastCat = !(--$ncat);
            add_row($category, TYPE_CATEGORY, $catId, ($catId==0), $lastCat);
            $nrates = count($category["rates"]);
            $rateId = 0;
            foreach ($category["rates"] as $rate) {
                $lastRate = !(--$nrates);
                add_row($rate, TYPE_RATE, $catId .'_'. $rateId, ($rateId==0), $lastRate);
                $rateId++;
            }
            //add_row_rate($catId, $rateId, "", "");
            $catId++;
        }
    ?>
    <input id="ncat" type="hidden" name="ncat" value="<?php echo count($categories) ?>" />
    <div class="submit" style="width:100%">
      <input type="submit" name="save" value="<?php i18n('rates/SAVE_RATES'); ?>" class="submit" />
    </div>
  </form>
