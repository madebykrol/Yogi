<?php
namespace yogi\framework\utils;

class Object {
	/**
	 * @return \ReflectionClass;
	 */
	public function getClass() {
		return new ReflectionClass(get_class($this));
	}
}