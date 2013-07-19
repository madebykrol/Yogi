<?php
namespace smll\framework\di;
use smll\framework\di\interfaces\IService;

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