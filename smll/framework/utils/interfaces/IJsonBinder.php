<?php
namespace smll\framework\utils\interfaces;

interface IJsonBinder {
	public function bind($json, \ReflectionClass $class);
}