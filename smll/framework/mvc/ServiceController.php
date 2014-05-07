<?php
namespace smll\framework\mvc;

class ServiceController extends Controller {
	/**
	 * @param string $model
	 * @param string $view
	 * @return \smll\framework\mvc\ViewResult
	 */
	public function view($model = null, $view = null) {
	
		$result = new ActionResult();
		if($model != null) {
			$result->setModel($model);
		}
		
		$result->setHeaders($this->headers->getHeaders());
		return $result;
		
	}
}