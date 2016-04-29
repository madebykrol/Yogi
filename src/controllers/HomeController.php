<?php
namespace src\controllers;
use yogi\framework\mvc\Controller;
class HomeController extends Controller {
	
	/**
	 * @return ViewResult
	 */
	public function index() {
		$this->viewBag['title'] = "Index | My yogi site";
		
    	return $this->view();
	}
	
}