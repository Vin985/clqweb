<?php

namespace Clq;

class RatesFrontend
{
    public static function displayRates()
    {
        require_once(GSPLUGINPATH . 'rates/rates.class.php');
        $text = '<div class="rates">';
        $rates = new Rates();
        $categories = $rates->getRates();
        foreach ($categories as $category) {
            $text .= self::addCategory($category);
            $nrates = count($category["rates"]);
            foreach ($category["rates"] as $rate) {
                $lastRate = !(--$nrates);
                $odd = ($nrates % 2 == 0);
                $text .= self::addRate($rate, $lastRate, $odd);
            }
        }
        $text .= '</div>';
        echo $text;
    }


    private static function addCategory($category)
    {
        $text = '<div class="category">';
        $text .= '<div class="row cat_title flex">';
        $text .= '<div class="name">'. $category['name'] . '</div>';
        foreach ($category['prices'] as $price) {
            $text .= '<div class="price">'. $price . '</div>';
        }
        $text .= '</div>';
        return $text;
    }


    private static function addRate($rate, $last, $odd)
    {
        $text = '<div class="row rate flex' . ($odd ? ' odd' : ' even') . '">';
        $text .= '<div class="name">'. $rate['name'] . '</div>';
        foreach ($rate['prices'] as $price) {
            $text .= '<div class="price">'. $price;
            if (!empty($price) && is_numeric($price)) {
                $text .= ' $';
            }
            $text .= '</div>';
        }
        $text .= '</div>';
        if ($last) {
            $text .=  '</div>';
            $text .= '<div class="spacer"></div>';
        }
        return $text;
    }
}
