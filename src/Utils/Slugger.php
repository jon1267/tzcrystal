<?php

namespace App\Utils;

/**
 * Class Slugger
 * @authors Ryan Weaver & Javier Eguiluz
 * @package App\Utils
 */
class Slugger
{
    public static function str_slug(string $string, string $replacement='-'): string
    {
        //это только для 'en'. Русские буквы остаются русскими (для slug не годится)
        return preg_replace('/\s+/', $replacement, mb_strtolower(trim(strip_tags($string)), 'UTF-8'));
    }
}