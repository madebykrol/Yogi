<?php
namespace smll\framework\utils;
class Boolean {
	public static function parseValue($string) {
		if(strtolower($string) == "true" || strtolower($string) == "yes" || $string == 1) {
			return true;
		}
		return false;
	}
	
	public static function isBoolean($val) {
		return (($val === true || $val === false) || ($val == "yes" || $val == "no") || ($val == "true" || $val == "false"));
	}
}