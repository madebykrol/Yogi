<?php
namespace smll\framework\mvc\filter\interfaces;
use smll\framework\mvc\filter\interfaces\IFilterConfig;

/**
 * 
 * @author Kristoffer "mbk" Olsson
 * @deprecated
 *
 */
interface IFilterRepository {
	public function addFilterConfig(IFilterConfig $config);
	
	/**
	 * @return IFilterConfig
	 */
	public function getFilterConfig();
}