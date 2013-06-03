<?php
class HomeController extends Controller {
	
	/**
	 * @return ViewResult
	 */
	public function index() {
		
		return $this->view();
	}
	
	/**
	 * @return ViewResult
	 */
	public function about() {
	
		return $this->view();
	}

}