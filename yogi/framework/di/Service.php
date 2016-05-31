<?php
namespace yogi\framework\di;
use yogi\framework\di\interfaces\IService;

/**
 * 
 * @author Kristoffer "mbk" Olsson
 *
 */
class Service implements IService {
	
	/**
	 * @var
	 */
	protected $serviceReference;

	/**
	 * Service constructor.
	 * @param $serviceReference
	 */
	public function __construct($serviceReference) {
		$this->serviceReference = $serviceReference;
	}

	/**
	 * @return mixed
	 */
	public function getServiceReference() {
		return $this->serviceReference;
	}
}