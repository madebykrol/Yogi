<?php
class MapModel {
	/**
	 * 
	 * @var ArrayList
	 */
	protected $locations;
	
	public function setLocations (ArrayList $locations) {
		$this->locations = $locations;
	}
	public function getLocations() {
		return $this->locations;
	}
	
}