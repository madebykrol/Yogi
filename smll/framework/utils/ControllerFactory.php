<?php
class ControllerFactory implements IControllerFactory {
	public function createController($controllerName) {
		return new HomeController();
	}
}