<?php
namespace yogi\framework\utils\interfaces;

interface IJsonBinder {
	public function bind($json, \ReflectionClass $class);
}