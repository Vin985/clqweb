<?php


namespace Clq;

require_once(GSPLUGINPATH . 'rates/rates.class.php');

class RatesFrontend
{

    public static function displayRates()
    {
        $text = '<div class="rates">';
        $rates = new Rates();
        $categories = $rates->getRates();
        foreach ($categories as $category) {
            $text .= self::add_category($category['label']);
            $nrates = count($category["rates"]);
            foreach ($category["rates"] as $rate) {
                $lastRate = !(--$nrates);
                $odd = ($nrates % 2 == 0);
                $text .= self::add_rate($rate['name'], $rate['value'], $lastRate, $odd);
            }
        }
        $text .= '</div>';
        echo $text;
    }


    private static function add_category($label)
    {
        $text = '<table class="category">';
        $text .= '<thead class="cat_title">';
        $text .= '<tr>';
        $text .= '<td colspan="2">';
        $text .= '<span class="title">'. $label . '</span>';
        $text .= '</td>';
        $text .= '</tr>';
        $text .= '</thead>';
        $text .= '<tbody>';
        return $text;
    }


    private static function add_rate($name, $value, $last, $odd)
    {
        $text = '<tr class="rate' . ($odd ? ' odd' : ' even') . '">';
        $text .= '<td class="rate_name">';
        $text .= '<span>'. $name . '</span>';
        $text .= '</td>';
        $text .= '<td class="rate_value">';
        $text .= '<span>'. $value . ' $</span>';
        $text .= '</td>';
        $text .= '</tr>';
        if ($last) {
            $text .=  '</tbody>';
            $text .= '</table>';
        }
        return $text;
    }
}
