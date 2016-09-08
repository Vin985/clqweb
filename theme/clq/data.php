<?php

  $languages = return_i18n_languages();
if (count($languages) > 1) {
    $other_lang = $languages[1];
} else {
    $other_lang = $languages[0];
}

echo $other_lang;
