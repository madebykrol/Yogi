<?php
interface IAuthenticationProvider {
	
	/**
	 * Destroys the authentication cookie and invalidates it on the server.
	 */
	public function signout();
	
	/**
	 * Sets a authentication cookie that contains an encrypted AuthenticationTicket
	 */
	public function setAuthCookie($username);
	
	/**
	 * Get the authentication cookie for the current request.
	 */
	public function getAuthCookie();
	
	/**
	 * @return IPrincipal
	 */
	public function getPrincipal();
}