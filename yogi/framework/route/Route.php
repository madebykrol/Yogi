<?php
namespace yogi\framework\route;
use yogi\framework\route\interfaces\IRoute;
class Route implements IRoute{
	
	private $name;
	private $url;
	private $defaults = array();
	
	public function __construct($name, $url, $defaults = array()) {
		$this->name 			= $name;
		$this->url 				= $url;
		$this->defaults 	= $defaults;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getUrl() {
		return $this->url;
	}
	
	public function getDefaults() {
		return $this->defaults;
	}
	
	const URLPARAMETER_OPTIONAL = 0;
	const URLPARAMETER_REQUIRED = 1;
}