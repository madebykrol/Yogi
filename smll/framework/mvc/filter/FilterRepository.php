<?php
class FilterRepository implements IFilterRepository {
	
	private $filterConfig = null;
	
	public function __construct() {
		
	}
	
	public function addFilterConfig(IFilterConfig $config) {
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