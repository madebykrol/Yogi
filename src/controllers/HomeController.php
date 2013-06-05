<?php
class HomeController extends Controller {
	
	/**
	 * @return ViewResult
	 */
	public function index() {
		$this->viewBag['title'] = "Index | My smll site";
    return $this->view();
	}
	
	/**
	 * @return ViewResult
	 */
	public function about() {
		return $this->view();
	}
	
}