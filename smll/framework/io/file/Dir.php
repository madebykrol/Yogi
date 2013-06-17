<?php
namespace smll\framework\io\file;
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
	
	public function search($search, ArrayList &$result) {
		
		if($search instanceof Regex) {
			
		} else if(is_string($search)) {
			
		}
		
		return $result;
	}
	
	public function searchRecursive($search, ArrayList &$result, ArrayList &$ignore = null) {
		
		$entries = $this->read();
		
		foreach($entries->getIterator() as $entry) {
			if($this->isDir($this->path."/".$entry)) {
				$dir = new Dir($this->path."/".$entry);
				$dir->searchRecursive($search, $result, $ignore);
			} else {
				if($search instanceof Regexp) {
						
					if($search->match($entry)) {
						$result->add($this->path."/".$entry);
					}
					
				} else if(is_string($search)) {
						if($entry == $search) {
							
							$result->add($this->path."/".$entry);
						}
				}
				
			}
		}
		
	}
	
	/**
	 * @return boolean
	 */
	private function isDir($dir) {
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