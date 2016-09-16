<?php

$page = isset($_GET["page"]) ? $_GET["page"] : "";

$result = array();

if ($page == "" || $page == "init") {
    $languages = return_i18n_languages();
    if (count($languages) > 1) {
        $other_lang = $languages[1];
    } else {
        $other_lang = $languages[0];
    }

    $result["lang"] = $other_lang;
    $result["content"] = "Hello World";
    $result["tabs"] = return_i18n_menu_data(return_page_slug(), 0, 0, I18N_SHOW_MENU);
    //echo $other_lang;
} else {
    $result["content"] = get_content($page);
}
echo json_encode($result);
