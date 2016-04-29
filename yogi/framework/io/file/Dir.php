<?php
namespace yogi\framework\io\file;
use yogi\framework\utils\ArrayList;
use yogi\framework\utils\Regexp;

/**
 * Performing directory tasks on the file system, like finding files in directory
 * creating new directories or deleting them.
 * @author Kristoffer "mbk" Olsson
 *
 */
class Dir {
	
	/**
	 * @var handler
	 */
	private $dirHandle = null;
	private $path;
	
	public function __construct($dir) {
		$this->dirHandle = opendir($dir);
		$this->path = $dir;
	}
	
	public function search($search, ArrayList &$result, ArrayList &$ignore = null) {
		
		$entries = $this->read();
		
		// To make things alitle easier along the way.
		if(!isset($ignore)) {
			$ignore = new ArrayList();
		}
		
		// Go through each files in the directory
		foreach($entries->getIterator() as $entry) {
			if($search instanceof Regex) {
				
			} else if(is_string($search)) {
				if($entry == $search && !$ignore->has($entry)) {
					$result->add($entry);
				}
			}
		}
		return $result;
	}
	
	public function searchRecursive($search, ArrayList &$result, ArrayList &$ignore = null) {
		
		$entries = $this->read();
		
		// pretty much duplicated from l.29 but who cares really.
		if(!isset($ignore)) {
			$ignore = new ArrayList();
		}
		
		foreach($entries->getIterator() as $entry) {
			if($this->isDir($this->path."/".$entry)) {
				$dir = new Dir($this->path."/".$entry);
				$dir->searchRecursive($search, $result, $ignore);
			} else {
				if($search instanceof Regexp) {
					
					if($search->match($entry) &&!$ignore->has($entry)) {
						$result->add($this->path."/".$entry);
					}
					
				} else if(is_string($search)) {
					if($entry == $search && !$ignore->has($entry)) {
						$result->add($this->path."/".$entry);
					}
				}
				
			}
		}
		
	}
	
	/**
	 * This method is pretty redundant, but it makes the OOP cleaner.
	 * @return boolean
	 */
	public function isDir($dir) {
		if(is_dir($dir)) {
			return true;
		} 
		return false;
	}
	
	/**
	 * @return ArrayList
	 */
	public function read() {
		$entries = new ArrayList();
		
		while(($entry = readdir($this->dirHandle)) !== FALSE) {
			if($entry != "." && $entry != "..") {
				$entries->add($entry);
			}
		}
		
		return $entries;
	}
}