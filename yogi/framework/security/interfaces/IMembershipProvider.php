<?php
namespace yogi\framework\security\interfaces;
use yogi\framework\security\MembershipUser;

interface IMembershipProvider {
	public function setEncrypter(ICryptographer $encrypter);
	public function validateUser($user, $password);
	
	/**
	 * Over ridden
	 * Either get user by username
	 * or by Guid
	 * @param mixed $user
	 * @return MembershipUser
	 */
	public function getUser($user);
	public function getAllUsers();
	/**
	 * @return MembershipUser
	 * @param unknown $username
	 * @param unknown $password
	 * @param string $providerUserKey
	 */
	public function createUser($username, $password, $providerUserKey = null);
}