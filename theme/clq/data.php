<?php

$page = isset($_GET["page"]) ? $_GET["page"] : "";
$setlang = isset($_GET["setlang"]);

$result = array();

if ($page == "" || $page == "init" || $setlang) {
    if (!$setlang) {
        $page = "index";
    }
    $result["langs"] = return_i18n_languages();
    $result["tabs"] = return_i18n_menu_data(return_page_slug(), 0, 0, I18N_SHOW_MENU);
    //echo $other_lang;
}

$content = "";
$title = "";
$url = $page;
if (isset($pagesArray[$page])) {
    $data_index = return_i18n_page_data($page);
    $content = strip_decode($data_index->content);
    $content = exec_filter('content', $content);
    $title = strip_decode($data_index->title);
    $url = strip_decode($data_index->url);
}

$page_content = array();
$children = return_i18n_menu_data($page, 1, 1, I18N_SHOW_MENU);
if (!is_null($children)) {
    $page_content["children"] = $children;
}
$page_content["title"] = $title;
$page_content["content"] = $content;

$result["url"] = $url;
$result["page"] = $page_content;
    //$result["content"] = get_content($page);
echo json_encode($result);
