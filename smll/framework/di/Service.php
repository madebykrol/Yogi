<?php
class Service implements IService {
	protected $serviceReference;
	
	public function __construct($serviceReference) {
		$this->serviceReference = $serviceReference;
	}
 	
	public function getServiceReference() {
		return $this->serviceReference;
	}
}