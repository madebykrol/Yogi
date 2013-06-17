<?php
namespace smll\framework\security\interfaces;
interface IMembershipProvider {
	public function setEncrypter(ICryptographer $encrypter);
	public function validateUser($user, $password);
	public function getUser($username);
	/**
	 * @return MembershipUser
	 * @param unknown $username
	 * @param unknown $password
	 * @param string $providerUserKey
	 */
	public function createUser($username, $password, $providerUserKey = null);
}