<?php

$page = isset($_GET["page"]) ? $_GET["page"] : "";

$result = array();

if ($page == "" || $page == "init") {
    $page = "index";
    $languages = return_i18n_languages();
    if (count($languages) > 1) {
        $other_lang = $languages[1];
    } else {
        $other_lang = $languages[0];
    }

    $result["lang"] = $other_lang;
    $result["langs"] = return_i18n_languages();
    $result["tabs"] = return_i18n_menu_data(return_page_slug(), 0, 0, I18N_SHOW_MENU);
    //echo $other_lang;
}
$content = "";
$title = "";
$url = $page;
if (isset($pagesArray[$page])) {
    $data_index = getXml(GSDATAPAGESPATH . $page . '.xml');
    $content = strip_decode($data_index->content);
    $title = strip_decode($data_index->title);
    $url = strip_decode($data_index->url);
}

    $result["content"] = $content;
    $result["url"] = $url;
    $result["title"] = $title;
    //$result["content"] = get_content($page);
echo json_encode($result);
