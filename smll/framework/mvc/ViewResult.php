<?php
class ViewResult implements IViewResult {
	
	private $viewFile;
	private $model;
	
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
	
	public function render() {
		$output = "";
		
		$model 		= $this->model;
		$layout 	= null;
		
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