<?php
class AOC_Odds_Converter {
    public static function to_decimal($fraction) {
        list($num, $den) = explode('/', $fraction);
        return round(($num / $den) + 1, 2);
    }

    public static function to_fractional($decimal) {
        $decimal -= 1;
        $fraction = self::decimal_to_fraction($decimal);
        return $fraction;
    }

    public static function to_american($decimal) {
        return $decimal >= 2.00 ? '+' . round(($decimal - 1) * 100) : round(-100 / ($decimal - 1));
    }

    private static function decimal_to_fraction($decimal) {
        $tolerance = 1.0E-6;
        $h1 = 1; $h2 = 0;
        $k1 = 0; $k2 = 1;
        $b = $decimal;
        do {
            $a = floor($b);
            $aux = $h1; $h1 = $a * $h1 + $h2; $h2 = $aux;
            $aux = $k1; $k1 = $a * $k1 + $k2; $k2 = $aux;
            $b = 1 / ($b - $a);
        } while (abs($decimal - $h1 / $k1) > $decimal * $tolerance);

        return "$h1/$k1";
    }
}
