<?php
class ControllerFactory implements IControllerFactory {
	public function createController(Request $request, $controllerName) {
		return "asd";
	}
}