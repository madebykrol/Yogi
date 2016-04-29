<?php
namespace yogi\framework\utils;
class Guid {
	
	protected $uuid = "";
	
	private function __construct($uuid) {
		$this->uuid = "".$uuid;
	}
	
	public function __toString() { 
		return $this->uuid;
	}
	
	public static function createNew() {
		return new Guid(sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand(0, 0xffff), mt_rand(0, 0xffff),
	
			// 16 bits for "time_mid"
			mt_rand(0, 0xffff),
			
			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand(0, 0x0fff) | 0x4000,
	
			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand(0, 0x3fff) | 0x8000,
	
			// 48 bits for "node"
			mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		));
	}
	
	public static function parse($uuid) {
		if(is_string($uuid) && preg_match('/^[A-Za-z0-9]{8}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{12}$/', $uuid) == 1) {
			return new Guid($uuid);
		} else {
			return null;
		}
		
	}
	
	public function getString() {
		return $this->uuid;
	}
}