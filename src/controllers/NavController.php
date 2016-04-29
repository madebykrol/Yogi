<?php
namespace src\controllers;
use yogi\framework\mvc\Controller;
class NavController extends Controller {
	
	public function topNav() {
		$model = (object)array(
				'authenticated' => $this->user->getIdentity()->isAuthenticated());
		return $this->view($model);
	}
	
}