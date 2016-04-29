<?php
namespace yogi\framework\http\interfaces;

/**
 * Sessions are commonly used to track users on your site.
 * @author Kristoffer "mbk" Olsson
 *
 */
interface ISession {
	
	public function getToken();
	public function set($var, $val);
	public function add($var, $val);
	public function remove($var);
	public function get($var);
	public function destroy();
	
}