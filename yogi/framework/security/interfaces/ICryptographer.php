<?php
namespace yogi\framework\security\interfaces;
interface ICryptographer {
	
	/**
	 * Return a one-way hashed password.
	 * @param unknown $password
	 */
	public function hash($string);
	
	/**
	 * Match password against the hashed correct password.
	 * if password is correct, return true, else false
	 * @param string $password
	 * @param string $hash
	 */
	public function checkHash($string, $hash);
	
	/**
	 * Encrypt a string using a specified encryption algorithm
	 * @param unknown $string
	 * @param unknown $method
	 */
	public function encrypt($string, $method);
	
	/**
	 * Decrypt a string using a specified encryption algorithm
	 * @param unknown $string
	 * @param unknown $method
	 */
	public function decrypt($string, $method);
	
	/**
	 * Set a mandatory encryption key!
	 * This key is used by encrypt / decrypt.
	 * @param unknown $key
	 */
	public function setEncryptionKey($key);
	
	/**
	 * Generate a random string
	 */
	public function rand();
	
	/**
	 * Generate a pseudorandom salt string
	 */
	public function generateSalt();
}