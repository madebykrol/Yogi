<?php
namespace yogi\framework\security\authentication;
use yogi\framework\security\interfaces\IAuthenticationProvider;

class SessionFormAuthentication implements IAuthenticationProvider {
	
	/**
	 * [Inject(ISettingsRepository)]
	 * @var ISettingsRepository
	 */
	private $settings;
	
	/**
	 * [Inject(yogi\framework\http\interfaces\IHeaderRepository)]
	 * @var IHeaderRepository
	 */
	private $headers;
	
	/**
	 * [Inject(ISession)]
	 * @var ISession 
	 */
	private $session;
	
	public function setSession(ISession $session) {
		$this->session = $session;
	}
	
	public function setSettings(ISettingsRepository $settings) {
		$this->settings = $settings;
	}
	
	public function setHeaders(IHeaderRepository $headers) {
		$this->headers = $headers;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IAuthenticationProvider::signout()
	 */
	public function signout() {
		$this->session->remove('username');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IAuthenticationProvider::setAuthCookie()
	 */
	public function setAuthCookie($user) {
		$this->session->set('username', $user);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IAuthenticationProvider::getAuthCookie()
	 */
	public function getAuthCookie() {
		return $this->session->get('username');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IAuthenticationProvider::getPrincipal()
	 */
	public function getPrincipal() {
		$user = $this->session->get('username');
		if($user != null) {
			$p = new Principal();
			$p->setIdentity(new Identity($user, true, "SessionFormAuthentication"));
			return $p;
		}
		return null;
	}
}