<?php
namespace smll\framework\http\interfaces;
interface ISession {
	
	public function getToken();
	public function set($var, $val);
	public function add($var, $val);
	public function remove($var);
	public function get($var);
	public function destroy();
	
}