<?php

// Class to handle odds format conversion: fractional, decimal, and American
class AOC_Odds_Converter {
    // Convert fractional odds (e.g. "5/2") to decimal format
    public static function to_decimal($fraction) {
        // Split the fraction into numerator and denominator
        list($num, $den) = explode('/', $fraction);
        // Convert to decimal: (numerator / denominator) + 1
        return round(($num / $den) + 1, 2);
    }
    // Convert decimal odds (e.g. 2.5) to fractional format
    public static function to_fractional($decimal) {
        // Subtract 1 from decimal to isolate profit portion
        $decimal -= 1;
        // Convert to fractional using helper method
        $fraction = self::decimal_to_fraction($decimal);
        return $fraction;
    }
    // Convert decimal odds to American format
    public static function to_american($decimal) {
        // If odds are 2.00 or more, positive American style (profit on $100 bet)
        // If less than 2.00, negative American style (stake needed to win $100)
        return $decimal >= 2.00 ? '+' . round(($decimal - 1) * 100) : round(-100 / ($decimal - 1));
    }
   // Private helper: Convert decimal to fractional (e.g. 1.75 => "3/4")
    private static function decimal_to_fraction($decimal) {
        $tolerance = 1.0E-6;  // acceptable margin of error
        $h1 = 1; $h2 = 0;  // numerator parts
        $k1 = 0; $k2 = 1;  // denomerator parts
        $b = $decimal;

        // Continued fraction algorithm to find best rational approximation
        do {
            $a = floor($b);
            $aux = $h1; $h1 = $a * $h1 + $h2; $h2 = $aux;
            $aux = $k1; $k1 = $a * $k1 + $k2; $k2 = $aux;
            $b = 1 / ($b - $a);
        } while (abs($decimal - $h1 / $k1) > $decimal * $tolerance);
         // Return as "numerator/denominator"
        return "$h1/$k1";
    }
}
