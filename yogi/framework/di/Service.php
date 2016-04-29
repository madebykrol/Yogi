<?php
namespace yogi\framework\di;
use yogi\framework\di\interfaces\IService;

/**
 * 
 * @author Kristoffer "mbk" Olsson
 *
 */
class Service implements IService {
	protected $serviceReference;
	
	public function __construct($serviceReference) {
		$this->serviceReference = $serviceReference;
	}
 	
	public function getServiceReference() {
		return $this->serviceReference;
	}
}