<?php
interface IControllerFactory {
	public function createController(Request $request, $controllerName);
	
}