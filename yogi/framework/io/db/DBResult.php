<?php 
namespace yogi\framework\io\db;
// $Id;
/**
 * Base class for DB Results
 * More of a wrapper arround a array
 * @author Kristoffer "Krol" Olsson - kristoffer.olsson@madebykrol.com
 * @version 1.0 - beta
 * @license 
 * @example  
 *
 */
abstract class DBResult implements Iterator, Countable {
	
	/**
	 * The raw Database result
	 * @var array
	 */
	protected /* array */ $result;
	
	/**
	 * Row count
	 * @var int
	 */
	protected /* int */ $count;
	
	/**
	 * Current itterator position
	 * @var int
	 */
	protected /* int */ $position = 0;
	
	
	/**
	 * Basic constructor
	 */
	public function __construct() {
	}
	
	/**
	 * Set the result
	 * @param array $result
	 */
	public function setResult($result) {
		$this->result 	= $result; // Result
		$this->count 		= count($result); // count the result
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator::current()
	 */
	public function current () {
		return $this->result[$this->position];
	}

	/**
	 * (non-PHPdoc)
	 * @see Iterator::next()
	 */
	public function next () {
		++$this->position;
	}

	/**
	 * (non-PHPdoc)
	 * @see Iterator::key()
	 */
	public function key () {
		return $this->position;
	}

	/**
	 * (non-PHPdoc)
	 * @see Iterator::valid()
	 */
	public function valid () {
		return isset($this->result[$this->position]);
	}

	/**
	 * (non-PHPdoc)
	 * @see Iterator::rewind()
	 */
	public function rewind () {
		$this->position = 0;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Countable::count()
	 */
	public function count() {
		return $count;
	}
	
}