<?php
namespace smll\cms\models;
use smll\framework\utils\HashMap;
class AdminNavModel {
	
	public $contentTypes;
	public $tools;
	
	public function __construct() {
		$this->contentTypes = new HashMap();
		$this->tools = new HashMap();
	}
	
}