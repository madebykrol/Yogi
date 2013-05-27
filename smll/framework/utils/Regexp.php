<?php
class Regexp {
	
	private $pattern;
	
	/**
	 * 
	 * regexp pattern do not use delimiters here, just expressions.
	 * @param string $pattern
	 */
	public function __construct($pattern) {
		$this->pattern = $pattern;
	}
	
	public function find($haystack) {
		
	}
	
	public function match($heystack) {
		$matches = array();
		
		if(preg_match('/'.$this->pattern.'/', $heystack, $matches) == 1) {
			return true;
		}
		
		return false;
		
	}
	
}