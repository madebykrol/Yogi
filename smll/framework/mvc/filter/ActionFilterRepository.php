<?php
class ActionFilterRepository implements IActionFilterRepository {
	
	private $filterConfig = null;
	
	public function __construct() {
		
	}
	
	public function setFilterConfig(IActionFilterConfig $config) {
		$this->filterConfig = $config;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IActionFilterRepository::getFilterConfig()
	 */
	public function getFilterConfig() {
		return $this->filterConfig;
	}
	
}