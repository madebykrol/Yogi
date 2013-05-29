<?php
class Boolean {
	public static function parseValue($string) {
		if(strtolower($string) == "true" || strtolower($string) == "yes") {
			return true;
		}
		return false;
	}
}