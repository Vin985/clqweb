<?php

namespace Clq;

function add_row_category($catId, $name, $last)
{
    $text = '<table id="category_' . $catId . '" class="category">';
    $text .= '<thead class="cat_title">';
    $text .= '<tr>';
    $text .= '<td colspan="2">';
    $text .= '<input type="text" name="catname_'. $catId . '"';
    $text .= 'title="'. i18n_r('rates/FORM_TITLE_CATEGORY') .'"';
    $text .= (!empty($name)) ? 'value="' . $name . '" ' : '';
    $text .= 'placeholder="'. i18n_r('rates/PLACEHOLDER_CATEGORY_LABEL').'"/>';
    $text .= '</td>';
    $text .= add_tools("category", $catId, $last);
    $text .= '</tr>';
    $text .= '</thead>';
    $text .= '<tbody>';
    echo $text;
}

function add_row_rate($catId, $rateId, $name, $value, $last)
{
    $text = '<tr id="rate_' . $catId . '_' . $rateId . '" class="rate">';
    $text .= '<td>';
    $text .= '<input ';
    $text .= 'type="text" name="name_' . $catId . '_' . $rateId . '"';
    $text .= 'title="' . i18n_r('rates/FORM_TITLE_NAME') .'"';
    $text .= (!empty($name)) ? 'value="' . $name . '" ' : '';
    $text .= 'placeholder="'.i18n_r('rates/PLACEHOLDER_RATE_NAME').'"/>';
    $text .= '</td>';
    $text .= '<td>';
    $text .= '<input ';
    $text .= 'type="text" name="value_' . $catId . '_' . $rateId . '"';
    $text .= 'title="' . i18n_r('rates/FORM_TITLE_VALUE') .'"';
    $text .= (!empty($value)) ? 'value="' . $value . '" ' : '';
    $text .= 'placeholder="'.i18n_r('rates/PLACEHOLDER_RATE_VALUE').'"/>';
    $text .= '</td>';
    $text .= add_tools("rate", $rateId, $last);
    $text .= '</tr>';
    if ($last) {
        $text .=  '</tbody>';
        $text .= '</table>';
    }
    echo $text;
}

function add_tools($type, $id, $last)
{

    $parent = ($type == "rate") ? 'tr' : 'table';

    $text = '<td class="tools">';
    // Move up
    $text .= '<button type="button" class="up button-img" title="Move up"';
    $text .= ($id == 0) ? 'style="visibility:hidden;"' : '';
    $text .= 'onclick="javascript:move_'.$type.'($(this).closest(\''.$parent.'\'), true)">';
    $text .= '</button>';

    // Move down
    $text .= '<button type="button" class="down button-img" title="Move down"';
    $text .= $last ? 'style="visibility:hidden;"' : '';
    $text .= 'onclick="javascript:move_'.$type.'($(this).closest(\''.$parent.'\'), false)">';
    $text .= '</button>';

    // Add rate
    $text .= '<button type="button" class="add button-img"';
    $text .= 'title="Add '. $type .'"';
    $text .= 'onclick="javascript:add_'.$type.'($(this).closest(\''.$parent.'\'))">';
    $text .= '</button>';

    // Remove rate
    $text .= '<button type="button" class="delete button-img"';
    $text .= 'title="Remove '. $type .'"';
    $text .= 'onclick="javascript:jConfirm(\'';
    $text .=  i18n_r('rates/CONFIRM_DEL_' . strtoupper($type));
    $text .= '\', \'Confirmation\',';
    $text .= 'function() { delete_'.$type.'($(this).closest(\''.$parent.'\'));}.bind($(this)))">';
    $text .= '</button>';

    $text .= '</td>';
    return $text;
}
