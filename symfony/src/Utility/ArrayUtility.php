<?php

namespace Pos\Utility;

class ArrayUtility
{
    public static function ksortRecursive(&$array, $sort_flags = SORT_REGULAR)
    {
        ksort($array, $sort_flags);
        foreach ($array as &$arr) {
            if (!is_array($arr)) {
                return false;
            } else {
                self::ksortRecursive($arr, $sort_flags);
            }
        }
        return true;
    }
}