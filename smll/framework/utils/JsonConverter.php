<?php
namespace smll\framework\utils;
class JsonConverter {
	public static function serializeObject($object) {
		if(is_array($object) || $object instanceof stdClass) {
			return json_encode($object);
		} else {
			
		}
	}
}