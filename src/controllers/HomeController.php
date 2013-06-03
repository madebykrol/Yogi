<?php
class HomeController extends Controller {
	
private $db = null;
	
	/**
	 * @return ViewResult
	 */
	public function index() {
		
		return $this->view();
	}

}