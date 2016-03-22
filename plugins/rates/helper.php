<?php

namespace Clq;

function add_row_category($catId, $name, $last)
{
    $text = '<table id="category_' . $catId . '">';
    $text .= '<thead>';
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
    $text = '<tr id="rate_' . $catId . '_' . $rateId . '">';
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

    $text = '<td>';
    // Move up
    $text .= '<button type="button" class="up"';
    $text .= ($id == 0) ? 'style="visibility:hidden;"' : '';
    $text .= 'onclick="javascript:move_'.$type.'($(this).closest(\''.$parent.'\'), true)">';
    $text .= 'Up';
    $text .= '</button>';

    // Move down
    $text .= '<button type="button" class="down"';
    $text .= $last ? 'style="visibility:hidden;"' : '';
    $text .= 'onclick="javascript:move_'.$type.'($(this).closest(\''.$parent.'\'), false)">';
    $text .= 'Down';
    $text .= '</button>';

    // Add rate
    $text .= '<button type="button" class="add" ';
    $text .= 'onclick="javascript:add_'.$type.'($(this).closest(\''.$parent.'\'))">';
    $text .= 'Add';
    $text .= '</button>';

    // Remove rate
    $text .= '<button type="button" class="delete"';
    $text .= 'onclick="javascript:delete_'.$type.'($(this).closest(\''.$parent.'\'))">';
    $text .= 'Remove';
    $text .= '</button>';

    $text .= '</td>';
    return $text;
}
