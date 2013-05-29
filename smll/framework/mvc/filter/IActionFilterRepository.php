<?php
interface IActionFilterRepository {
	public function setFilterConfig(IActionFilterConfig $config);
	
	/**
	 * @return IActionFilterConfig
	 */
	public function getFilterConfig();
}