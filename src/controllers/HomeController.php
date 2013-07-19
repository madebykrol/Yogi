<?php
namespace src\controllers;
use smll\framework\mvc\Controller;
class HomeController extends Controller {
	
	/**
	 * @return ViewResult
	 */
	public function index() {
		$this->viewBag['title'] = "Start Gamescom";
		
    return $this->view();
	}
	
}

//698cff47-dd1c-4321-85d7-7ca6d35a8ba4