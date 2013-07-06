<?php
namespace smll\cms\models;
use smll\framework\utils\HashMap;

class EditorTool {
	
	public $active = false;
	public $enabled = true;
	public $action = "";
	public $controller = "";
	public $extras = null;
	public $icon = "";
	
	public function __construct($action = "", $controller = "", HashMap $extras = null, $icon = "", $active = false, $enabled = true) {
		 $this->action = $action;
		 $this->controller = $controller;
		 $this->extras = $extras;
		 $this->icon = $icon;
		 $this->active = $active;
		 $this->enabled = $enabled;
	}
	
}