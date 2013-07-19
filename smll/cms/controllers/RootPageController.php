<?php
namespace smll\cms\controllers;

use smll\framework\mvc\Controller;

class RootPageController extends Controller {
	public function index() {
		
		return $this->view();
	}
}