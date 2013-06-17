<?php
namespace src\controllers;
use smll\framework\mvc\Controller;
class NavController extends Controller {
	public function topNav() {
		$model = (object)array('authenticated' => $this->user->getIdentity()->isAuthenticated());
		return $this->view($model);
	}
}