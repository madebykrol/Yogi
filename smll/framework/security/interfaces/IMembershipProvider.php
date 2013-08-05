<?php
namespace smll\framework\security\interfaces;
interface IMembershipProvider {
    public function setEncrypter(ICryptographer $encrypter);
    public function validateUser($user, $password);

    /**
     * Over ridden
     * Either get user by username
     * or by Guid
     * @param mixed $user
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