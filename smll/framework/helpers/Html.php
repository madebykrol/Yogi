<?php
class Html {
	
	public static function renderAction($action, $controller = null, $extras = null) {
		global $application;
		if($controller == null) {
			$controller = $application->getCurrentExecutingController();
		}
		$request = new Request(null, array('q' => $controller."/".$action), null);
		$request->init();
		return $application->run($request);
	}
	
	public function renderPartial($view, $model) {
		
	}
	
}