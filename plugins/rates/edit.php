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
if (isset($_GET['undo']) && !isset($_POST['save'])) {
    $newname = @$_GET['new'] ? $_GET['new'] : $name;
    if (i18n_gallery_save_undo($name, $newname)) {
        $msg = i18n_r('rates/UNDO_SUCCESS');
        $success = true;
    } else {
        $msg = i18n_r('rates/UNDO_FAILURE');
    }
    $gallery = return_i18n_gallery(@$_GET['name']);
} elseif (isset($_POST['save'])) {
    if ($rates->saveRates()) {
        $msg = i18n_r('rates/SAVE_SUCCESS');
        $success = true;
    } else {
        $msg = i18n_r('rates/SAVE_FAILURE');
    }
}




//print_r($rates->getRates());

$catId = 0;
$rateId = 0;
$categories = $rates->getRates();

?>

  <form method="post" id="ratesForm" action="load.php?id=rates&amp;edit"
  accept-charset="utf-8">

        <?php
        $ncat = count($categories);
        foreach ($categories as $category) {
            $lastCat = !(--$ncat);
            add_row_category($catId, $category['label'], $lastCat);
            $nrates = count($category["rates"]);
            foreach ($category["rates"] as $rate) {
                $lastRate = !(--$nrates);
                add_row_rate($catId, $rateId, $rate['name'], $rate['value'], $lastRate);
                $rateId++;
            }
            //add_row_rate($catId, $rateId, "", "");
            $catId++;
        }
    ?>
    <div class="submit" style="width:100%">
      <input type="submit" name="save" value="<?php i18n('rates/SAVE_RATES'); ?>" class="submit" />
    </div>
  </form>
