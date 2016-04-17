<?php

namespace Clq;

function add_row_category($catId, $category, $last)
{
    $name = $category["label"];

    $text = '<div id="category_' . $catId . '" class="category">';
    $text .= '<div class="cat_title row flex">';
    $text .= '<div class="name">';
    $text .= '<input type="text"';
    $text .= 'name="c_name_'. $catId . '"';
    $text .= 'title="'. i18n_r('rates/FORM_TITLE_CATEGORY') .'"';
    $text .= (!empty($name)) ? 'value="' . $name . '" ' : '';
    $text .= 'tabindex="1"';
    $text .= 'placeholder="'. i18n_r('rates/PLACEHOLDER_CATEGORY_LABEL').'"/>';
    $text .= '</div>';

    $text .= add_prices($category, 'c', $catId, -1);

/*
    $position = 0;
    $n = (isset($category["prices"]) ? count($category["prices"]) : 1);
    for ($pos = 0; $pos < $n; $pos++) {
        $price = isset($category["prices"]) ? $category["prices"][$pos] : '';
      // Price
        $text .= '<div class="value flex">';
        $text .= '<input type="text"';
        $text .= 'name="catprice_'. $catId . '_'. $position . '"';
        $text .= 'title="'. i18n_r('rates/FORM_TITLE_VALUE') .'"';
        $text .= 'tabindex="1"';
        $text .= 'value="' . $price . '"';
        $text .= 'placeholder="'. i18n_r('rates/FORM_TITLE_VALUE').'"/>';
      // Add column
        $text .= '<button type="button" title="Add price" tabindex="0"';
        $text .= 'onclick="javascript:add_price_column($(this).closest(\'.category\'), 1)">+';
        $text .= '</button>';
        $text .= '</div>';

    }
*/
    $text .= add_tools("category", $catId, $last);
    $text .= '</div>';
    echo $text;
}

function add_row_rate($catId, $rateId, $rate, $last)
{
    $name = $rate['name'];


    $text = add_name($rate, 'rate', $catId . '_' . $rateId);

    /*$text = '<div id="rate_' . $catId . '_' . $rateId . '" class="rate row flex">';
    $text .= '<div class="name">';
    $text .= '<input id="name_' . $catId . '_' . $rateId . '"';
    $text .= 'type="text" name="name_' . $catId . '_' . $rateId . '"';
    $text .= 'title="' . i18n_r('rates/FORM_TITLE_NAME') .'"';
    $text .= (!empty($name)) ? 'value="' . $name . '" ' : '';
    $text .= 'tabindex="1"';
    $text .= 'placeholder="'.i18n_r('rates/PLACEHOLDER_RATE_NAME').'"/>';
    $text .= '</div>';*/

    $text .= add_prices($rate, 'r', $catId, $rateId);
  /*  $text .= '<div class="value flex">';
    $text .= '<input id="value_' . $catId . '_' . $rateId . '"';
    $text .= 'type="text" name="value_' . $catId . '_' . $rateId . '"';
    $text .= 'title="' . i18n_r('rates/FORM_TITLE_VALUE') .'"';
    $text .= (!empty($value)) ? 'value="' . $value . '" ' : '';
    $text .= 'tabindex="1"';
    $text .= 'placeholder="'.i18n_r('rates/PLACEHOLDER_RATE_VALUE').'"/>';

    $text .= '</div>';*/

    $text .= add_tools("rate", $rateId, $last);
    $text .= '</div>';
    if ($last) {
        $text .= '</div>';
    }
    echo $text;
}

function add_row($data, $type, $id, $first, $last)
{
    $text = '';
    if ($type == 'category') {
        $text .= '<div id="category_' . $id . '" class="category">';
    }

    $class = ($type == 'rate') ? 'rate' : 'cat_title';
    $tag = ($type == 'rate') ? 'id="' . $type . '_' . $id. '" ' : '';

    $text .= '<div '. $tag .'class="'. $class.' row flex">';
    $text .= add_name($data, $type, $id);
    $text .= add_prices($data, $type, $id);
    $text .= add_tools($type, $first, $last);

    $text .= '</div>';

    if ($type == 'rate' && $last) {
        $text .= '</div>';
    }
    echo $text;
}


function add_name($data, $type, $id)
{
    $name = $type. '_name_'. $id;
    $value = $data['name'];

    $text = '<div class="name">';
    $text .= '<input ';
    $text .= 'type="text" name="' . $name . '"';
    $text .= 'title="' . i18n_r('rates/FORM_TITLE_'.strtoupper($type).'_NAME') .'"';
    $text .= (!empty($value)) ? 'value="' . $value . '" ' : '';
    $text .= 'tabindex="1"';
    $text .= 'placeholder="'.i18n_r(
        'rates/PLACEHOLDER_'. strtoupper($type) .'_NAME'
    ).'"/>';
    $text .= '</div>';

    return $text;
}

function add_prices($data, $type, $id)
{
    $text = '';
    $name = $type. '_price_'. $id;

    $n = (isset($data["prices"]) ? count($data["prices"]) : 1);
    for ($pos = 0; $pos < $n; $pos++) {
        $price = isset($data["prices"]) ? $data["prices"][$pos] : '';
      // Price
        $text .= '<div class="value flex">';
        $text .= '<input type="text"';
        $text .= 'name="'. $name . '_' . $pos . '"';
        $text .= 'title="'. i18n_r('rates/FORM_TITLE_VALUE') .'"';
        $text .= 'tabindex="1"';
        $text .= 'value="' . $price . '"';
        $text .= 'placeholder="'. i18n_r('rates/FORM_TITLE_VALUE').'"/>';
      // Add column
        if ($type == 'category') {
            $text .= '<button type="button" title="Add price" tabindex="0"';
            $text .= 'onclick="javascript:add_price_column($(this).closest(\'.category\'), 1)">+';
            $text .= '</button>';
        } else {
            $text .= '<span class="currency">$</span>';
        }
        $text .= '</div>';
    }
    return $text;
}

function add_tools($type, $first, $last)
{
    $parent = ($type == "rate") ? '.row' : '.category';

    $text = '<div class="tools flex">';
    // Move up
    $text .= '<button type="button" class="up button-img" title="Move up" tabindex="0"';
    $text .= $first ? 'style="visibility:hidden;"' : '';
    $text .= 'onclick="javascript:move_'.$type.'($(this).closest(\''.$parent.'\'), true)">';
    $text .= '</button>';

    // Move down
    $text .= '<button type="button" class="down button-img" title="Move down" tabindex="0"';
    $text .= $last ? 'style="visibility:hidden;"' : '';
    $text .= 'onclick="javascript:move_'.$type.'($(this).closest(\''.$parent.'\'), false)">';
    $text .= '</button>';

    // Add rate
    $text .= '<button type="button" class="add button-img" tabindex="0"';
    $text .= 'title="Add '. $type .'"';
    $text .= 'onclick="javascript:add_'.$type.'($(this).closest(\''.$parent.'\'))">';
    $text .= '</button>';

    // Remove rate
    $text .= '<button type="button" class="delete button-img" tabindex="0"';
    $text .= 'title="Remove '. $type .'"';
    $text .= 'onclick="javascript:jConfirm(\'';
    $text .=  i18n_r('rates/CONFIRM_DEL_' . strtoupper($type));
    $text .= '\', \'Confirmation\',';
    $text .= 'function() { delete_'.$type.'($(this).closest(\''.$parent.'\'));}.bind($(this)))">';
    $text .= '</button>';

    $text .= '</div>';
    return $text;
}
