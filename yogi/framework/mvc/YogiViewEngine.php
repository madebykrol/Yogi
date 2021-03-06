<?php
namespace yogi\framework\mvc;
use yogi\framework\mvc\interfaces\IViewEngine;
use yogi\framework\mvc\interfaces\IActionResult;
use yogi\framework\utils\ArrayList;

class YogiViewEngine implements IViewEngine {
	
	private $partialViews = null;
	
	/**
	 * 
	 * @param string $paths
	 */
	public function __construct($paths = null) {
		
		if(!isset($paths)) {
			$this->partialViews = new ArrayList();
		} else if(is_array($paths)) {
			$this->partialViews = new ArrayList($paths);
		} else if($paths instanceof ArrayList){
			$this->partialViews = $paths;
		}
		$this->initPartialViews();
	}
	
	public function initPartialViews() {
		$this->partialViews->add('src/views/{0}/_default.phtml');
		$this->partialViews->add('src/views/{0}/{1}.phtml');
		$this->partialViews->add('src/Share/{1}.phtml');
	}
	
	public function addPartialViewLocation($locationString) {
		$this->partialViews->add($locationString);
	}
	
	public function getPartialViewLocations() {
		return $this->partialViews;
	}
	
	public function renderResult(IActionResult $result, $controller, $action) {
		$viewFileExists = false;
		$triedViewFiles = new ArrayList();
		
		$output = "";
		
		if($result->getHeaders() != null) {
			foreach($result->getHeaders()->getIterator() as $field => $value) {
				header($field.": ".$value);
			}
		}
		if ($result->useView()) {
			if ($result->getViewFile() != null) {
				if(is_file($result->getViewFile())) {
					$viewFileExists = true;
			
				} else {
					$triedViewFiles->add($result->getViewFile());
				}
			} else {
				// Loop through view file conventions
				foreach($this->partialViews->getIterator() as $file) {
					$file = str_replace(array("{0}", "{1}"), array($controller, $action), $file);
					if(is_file($file)) {
						$viewFileExists = true;
						$result->setViewFile($file);
						break;
					} else {
						$triedViewFiles->add($file);
					}
				}
			}
			
			if($viewFileExists) {
				$output = $result->render();
			} else {
				foreach($triedViewFiles->getIterator() as $file) {
					$output .= $file."\n";
				}
			}
		} else {
			$output = $result->render();
		}
		
		return $output;
	}
}