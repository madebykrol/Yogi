<?php
namespace smll\framework\mvc\filter;
class FilterAttribute {
	
	protected $annotations;
	
	public function setAnnotations($annotations) {
		$this->annotations = $annotations;
	}
	
	public function getAnnotations() {
		return $this->annotations;	
	}
}