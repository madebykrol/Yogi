<?php
namespace smll\cms\framework\content\interfaces;

use smll\framework\utils\interfaces\IList;

interface IPropertyCriteriaCollection extends IList {
	
	public function add(IPropertyCriteria $criteria);
	public function remove($index);
	
}