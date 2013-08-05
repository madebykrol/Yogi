<?php
namespace smll\framework\utils;
class Boolean {
    public static function parseValue($string) {
        if(strtolower($string) == "true" || strtolower($string) == "yes" || $string == 1) {
            return true;
        }
        return false;
    }
}