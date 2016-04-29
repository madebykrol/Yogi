<?php
namespace yogi\framework\http\interfaces;
use yogi\framework\utils\HashMap;

/**
 * A header repository contains functionallity to add Header data to the response
 * Implementations allow you to add header fields and get and set cookies.
 * @author ksdkrol
 *
 */
interface IHeaderRepository {
	/**
	 * Add a header field to the response
	 * @param unknown $field
	 * @param unknown $value
	 */
	public function add($field, $value);
	
	/**
	 * Get all header fields thats going to be placed on the response
	 * @return HashMap
	 */
	public function getHeaders();
	
	/**
	 * Getting a cookie by it's name
	 * @param unknown $name
	 */
	public function getCookie($name);
	
	/**
	 * Setting a cookie
	 * @param string $name
	 * @param string $data
	 * @param string $expire
	 * @param string $path
	 * @param string $domain
	 */
	public function setCookie($name, $data, $expire, $path, $domain);
}