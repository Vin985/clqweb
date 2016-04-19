<?php

namespace Clq;

define("TYPE_RATE", 'rate');
define("TYPE_CATEGORY", 'category');

function add_row($data, $type, $id, $first, $last)
{
    $text = '';
    if ($type == TYPE_CATEGORY) {
        $text .= '<div id="category_' . $id . '" class="category">';
    }

    $class = ($type == TYPE_RATE) ? 'rate' : 'cat_title';
    $tag = ($type == TYPE_RATE) ? 'id="' . $type . '_' . $id. '" ' : '';

    $text .= '<div '. $tag .'class="'. $class.' row flex">';
    $text .= add_name($data, $type, $id);
    $text .= add_prices($data, $type, $id);
    $text .= add_tools($type, $first, $last);

    $text .= '</div>';

    if ($type == TYPE_RATE && $last) {
        $ids = explode('_', $id);
        $text .= '<input id="nrate_'. $ids[0] .'" type="hidden" name="nrate_';
        $text .= $ids[0] .'" value="'. (intval($ids[1]) + 1).'" />';
        $text .= '<div class="spacer"></div>';
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
        $text .= '<div class="price flex start">';
        $text .= '<input type="text"';
        $text .= 'name="'. $name . '_' . $pos . '"';
        $text .= 'title="'. i18n_r('rates/FORM_TITLE_VALUE') .'"';
        $text .= 'tabindex="1"';
        $text .= 'value="' . $price . '"';
        $text .= 'placeholder="'. i18n_r('rates/FORM_TITLE_VALUE').'"/>';
      // Add column
        if ($type == TYPE_CATEGORY) {
            $text .= '<div class="stack">';
            $text .= '<button class="price" type="button" title="Add price" tabindex="0"';
            $text .= 'onclick="javascript:add_price_column($(this).closest(\'div.price\'))">+';
            //$text .= ($pos + 1) .')">+';
            $text .= '</button>';
            $text .= '<button class="price" type="button" title="Remove price" tabindex="0"';
            $text .= 'onclick="jConfirm(\'';
            $text .=  i18n_r('rates/CONFIRM_DEL_PRICE_COLUMN');
            $text .= '\', \'Confirmation\',';
            $text .= 'function() { delete_price_column($(this).closest(\'div.price\'));}.bind($(this)))">-';
            //$text .= ($pos + 1) .')">+';
            $text .= '</button>';
            $text .= '</div>';
        } else {
            $text .= '<span class="currency">$</span>';
        }
        $text .= '</div>';
    }
    return $text;
}

function add_tools($type, $first, $last)
{
    $parent = ($type == TYPE_RATE) ? '.row' : '.category';

    $text = '<div class="tools flex">';

    if ($type == TYPE_RATE) {
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
    }
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
