<?php
namespace yogi\framework\security\interfaces;
interface IAuthenticationProvider {
	
	/**
	 * Destroys the AuthenticationTicket and invalidates it on the server.
	 */
	public function signout();
	
	/**
	 * Sets the AuthenticationTicket for this request.
	 */
	public function signin($user, $updateLastLogin = false);
	
	/**
	 * @return IPrincipal
	 */
	public function getPrincipal();
}