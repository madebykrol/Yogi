<?php
interface IActionFilter {
	public function pass(ReflectionMethod $method);
	public function getMessage();
}