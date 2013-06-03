<?php
class ViewResult implements IViewResult {
	
	private $viewFile;
	private $model;
	private $viewBag;
	private $headers;
	
	public function init() {
		$this->headers = new HashMap();
	}
	
	public function setHeader($field, $value) {
		$this->headers->add($field, $value);
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
	
	public function render() {
		$output = "";
		
		$model 		= $this->model;
		$layout 	= null;
		$viewBag = $this->viewBag;
		
		ob_start();
		include($this->viewFile);
		$content = ob_get_clean();
		
		if($layout == null) {
			$output = $content;
		} else {
			ob_start();
			include($layout);
			$output = ob_get_clean();
		}
		
		return $output;
	}
	
}