<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Transaction;

class Helper
{
    public static function convertToWords($number)
    {
        $words = array(
            0 => 'Nol', 1 => 'Satu', 2 => 'Dua', 3 => 'Tiga', 4 => 'Empat',
            5 => 'Lima', 6 => 'Enam', 7 => 'Tujuh', 8 => 'Delapan', 9 => 'Sembilan',
            10 => 'Sepuluh', 11 => 'Sebelas', 12 => 'Dua Belas', 13 => 'Tiga Belas',
            14 => 'Empat Belas', 15 => 'Lima Belas', 16 => 'Enam Belas', 17 => 'Tujuh Belas',
            18 => 'Delapan Belas', 19 => 'Sembilan Belas'
        );

        $tens = array(
            2 => 'Dua Puluh', 3 => 'Tiga Puluh', 4 => 'Empat Puluh', 5 => 'Lima Puluh',
            6 => 'Enam Puluh', 7 => 'Tujuh Puluh', 8 => 'Delapan Puluh', 9 => 'Sembilan Puluh'
        );

        $thousands = array('', 'Ribu', 'Juta', 'Miliar', 'Triliun', 'Kuadriliun');

        $result = '';

        $group = 0;
        while ($number > 0) {
            $hundreds = floor($number % 1000 / 100);
            $tens_digit = floor($number % 100 / 10);
            $ones = $number % 10;

            if ($hundreds > 0) {
                if ($group > 0 && $hundreds == 1) {
                    $result .= 'Seratus ';
                } else {
                    $result .= $words[$hundreds] . ' Ratus ';
                }
            }

            if ($tens_digit > 1) {
                $result .= $tens[$tens_digit] . ' ';
                if ($ones > 0) {
                    $result .= $words[$ones] . ' ';
                }
            } elseif ($tens_digit == 1) {
                $result .= $words[$ones + 10] . ' ';
            } else {
                if ($ones > 0) {
                    $result .= $words[$ones] . ' ';
                }
            }

            if ($hundreds > 0 || $tens_digit > 0 || $ones > 0) {
                $result .= $thousands[$group] . ' ';
            }

            $number = floor($number / 1000);
            $group++;
        }

        return rtrim($result) . ' Rupiah';
    }
}
