<?php
namespace yogi\framework\mvc\filter;
use yogi\framework\mvc\filter\interfaces\IFilterRepository;
use yogi\framework\mvc\filter\interfaces\IFilterConfig;

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