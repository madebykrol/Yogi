<?php
namespace yogi\framework\utils;
class Regexp {
	
	private $pattern;
	private $options;
	private $delimiter;
	
	/**
	 * 
	 * regexp pattern do not use delimiters here, just expressions.
	 * @param string $pattern
	 * @param string $delimiter
	 * @return array of matches
	 */
	public function __construct($pattern, $delimiter = "#") {
		$this->pattern = $pattern;
		$this->delimiter = $delimiter;
	}
	
	public function setOption($string) {
		$this->options = $string;
	}
	
	public function find($heystack, $quote = false) {
		$matches = array();
		if($quote) {
			$heystack = preg_quote($heystack, $this->delimiter);
		}
		preg_match_all(
			$this->delimiter.str_replace($this->delimiter, "\\".$this->delimiter, $this->pattern).$this->delimiter.$this->options, 
			$heystack, 
			$matches
		);
		
		return $matches;
	}
	/**
	 * 
	 * @param unknown $heystack
	 * @return boolean
	 */
	public function match($heystack, $quote = false) {
		$matches = array();
		if($quote) {
			$heystack = preg_quote($heystack, $this->delimiter);
		}
		if(preg_match(
				$this->delimiter.str_replace($this->delimiter, "\\".$this->delimiter, $this->pattern).$this->delimiter.$this->options, 
				$heystack, 
				$matches
				) == 1) {
			return true;
		}
		
		return false;
		
	}
	
}