<?php
namespace smll\framework\mvc\filter\interfaces;
use smll\framework\mvc\filter\interfaces\IFilterConfig;
interface IFilterRepository {
	public function addFilterConfig(IFilterConfig $config);
	
	/**
	 * @return IFilterConfig
	 */
	public function getFilterConfig();
}