<?php
namespace yogi\framework\mvc;

use yogi\framework\mvc\interfaces\IActionResult;
use yogi\framework\utils\HashMap;
use yogi\framework\helpers\Html;
use yogi\framework\utils\Regexp;

class ViewResult implements IActionResult {
	
	private $viewFile;
	private $model;
	private $viewBag;
	private $headers;
	/**
	 * @var IViewEngine
	 */
	private $viewEngine;
	
	private $useView = true;
	private $regions = array();
	
	public function __construct($model = null) {
		$this->model = $model;
		$this->headers = new HashMap();
	}
	
	public function setHeader($field, $value) {
		$this->headers->add($field, $value);
	}
	
	public function setHeaders(HashMap $headers) {
		$this->headers = $headers;
	}
	
	public function getHeaders() {
		return $this->headers;
	}
	
	public function setModel($model) {
		$this->model = $model;
	}
	public function getModel(){
		return $this->model;
	}
	
	public function setViewFile($file){
		$this->viewFile = $file;
	}
	
	public function getViewFile(){
		return $this->viewFile;
	}
	
	public function setViewBag($viewBag) {
		$this->viewBag = $viewBag;
	}
	
	public function getViewBag() {
		return $this->viewBag;
	}
	
	public function render() {
		$output = "";
		
		$model 		= $this->model;
		$layout 	= null;
		$viewBag 	= $this->viewBag;
		
		ob_start();
		include($this->viewFile);
		$content = ob_get_clean();
		
		$rexp = new Regexp('@Region (.+?)\n(.+?)@Endregion');
		$rexp->setOption("is");
		
		$r = $rexp->find($content);
		$regions = array();
		if (count($r) > 0 ) {
			foreach($r as $index => $region) {
			    $name = "";
			    $complete = "";
			    $rContent = "";
			    
			    if(is_array($r)) {
    			    if(isset($r[0])) {
    			        if(isset($r[0][$index])) {
    				        $complete = $r[0][$index];
    			        }
    			    }
    			    if(isset($r[1])) {
    			        if(isset($r[1][$index])) {
    				        $name = $r[1][$index];
    			        }
    			    }
    				if(isset($r[2])) {
    				    if(isset($r[2][$index])) {
    				        $rContent = $r[2][$index];
    				    }
    				}
			    }
				
				$content = str_replace($complete, "", $content);
				$this->regions[trim($name)] = trim($rContent);
			}
			
		}
		
		 
		if($layout == null) {
			$output = $content;
		} else {
			ob_start();
			include($layout);
			$output = ob_get_clean();
		}
		
		return $output;
	}
	
	public function renderSection($section) {
		if(isset($this->regions[$section])) {
		    return $this->regions[$section];
		}
	}
	
	public function renderContent() {
		
	}
	
	public function useView($boolean = null) {
		if(is_bool($boolean)) {
			$this->useView = $boolean;
		}
		return $this->useView;
	}
}