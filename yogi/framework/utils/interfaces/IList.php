<?php
namespace yogi\framework\utils\interfaces;

interface IList {
	public function getIterator();
	public function find($value, $func);
}