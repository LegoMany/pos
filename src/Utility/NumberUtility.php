<?php

namespace Pos\Utility;

class NumberUtility
{
    public static function commaToFloat(string $value): float
    {
        $dotAsDecimal = str_replace(['.', ','], ['', '.'], $value);
        return (float)$dotAsDecimal;
    }

    public static function getEurosFromPrice(float $price)
    {
        $formattedPrice = number_format($price, 3, '.', '');
        $priceParts = explode('.', $formattedPrice);
        return $priceParts[0];
    }

    public static function getCentsFromPrice(float $price)
    {
        $formattedPrice = number_format($price, 3, '.', '');
        $priceParts = explode('.', $formattedPrice);
        if ($priceParts[1] === '000') {
            return '00';
        }
        return $priceParts[1];
    }
}