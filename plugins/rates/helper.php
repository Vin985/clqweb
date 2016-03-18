<?php

namespace Clq;

function add_row_category($id, $label)
{
    $text = '<tr>';
    $text .= '<td colspan="3">';
    $text .= '<input type="text" name="cat_name_'. $id . '"';
    $text .= 'title="'. i18n_r('rates/FORM_TITLE_CATEGORY') .'"';
    $text .= empty($label) ?
              'placeholder="'.i18n_r('rates/PLACEHOLDER_CATEGORY_LABEL').'"' :
              'value="' . $label . '" />';
    $text .= '</td>';
    $text .= '</tr>';
    echo $text;
}

function add_row_rate($catId, $rateId, $name, $value)
{
    $text = '<tr id="rate_' . $catId . '_' . $rateId . '">';
    $text .= '<td>';
    $text .= '<input id="name_' . $catId . '_' . $rateId . '"';
    $text .= 'type="text" name="name_' . $catId . '_' . $rateId . '"';
    $text .= 'title="' . i18n_r('rates/FORM_TITLE_NAME') .'"';
    $text .= (!empty($name)) ? 'value="' . $name . '" ' : '';
    $text .= 'placeholder="'.i18n_r('rates/PLACEHOLDER_RATE_NAME').'"/>';
    $text .= '</td>';
    $text .= '<td>';
    $text .= '<input id="value_' . $catId . '_' . $rateId . '"';
    $text .= 'type="text" name="value_' . $catId . '_' . $rateId . '"';
    $text .= 'title="' . i18n_r('rates/FORM_TITLE_VALUE') .'"';
    $text .= (!empty($value)) ? 'value="' . $value . '" ' : '';
    $text .= 'placeholder="'.i18n_r('rates/PLACEHOLDER_RATE_VALUE').'"/>';
    $text .= '</td>';
    $text .= add_rate_tools($catId, $rateId);
    $text .= '</tr>';
    echo $text;
}

function add_rate_tools($catId, $rateId)
{
    $text = '<td>';
    // Add rate
    $text .= '<button type="button" ';
    $text .= 'onclick="javascript:add_rate($(this).closest(\'tr\'))">';
    $text .= 'Add';
    $text .= '</button>';

    // Remove rate
    $text .= '<button type="button" ';
    $text .= 'onclick="javascript:delete_rate($(this).closest(\'tr\'))">';
    $text .= 'Remove';
    $text .= '</button>';

    $text .= '</td>';
    return $text;
}
