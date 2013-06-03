<?php
class HomeController extends Controller {
	
	public function __construct() {
	}
	
	/**
	 * @return ViewResult
	 */
	public function index() {
		$this->viewBag['title'] = "My smll site";
		return $this->view();
	}
	
	
	public function about() {
		$this->viewBag['title'] = "About | My smll site";
		return $this->view();
	}
	
}