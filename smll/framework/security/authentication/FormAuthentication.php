<?php
class FormAuthentication implements IAuthenticationProvider {
	
	/**
	 * [Inject(ISettingsRepository)]
	 * @var ISettingsRepository
	 */
	private $settings;
	
	/**
	 * [Inject(ICryptographer)]
	 * @var ICryptographer
	 */
	private $encryptor;
	
	/**
	 * [Inject(IHeaderRepository)]
	 * @var IHeaderRepository
	 */
	private $headers;
	
	public function setEncryptor(ICryptographer $encryptor) {
		$this->encryptor = $encryptor;
	}
	
	public function setSettings(ISettingsRepository $settings) {
		$this->settings = $settings;
	}
	
	public function setHeaders(IHeaderRepository $headers) {
		$this->headers = $headers;
	}
	
	/**
	 * Encrypts a AuthenticationTicket
	 * @param AuthenticationTicket $ticket
	 * @return string
	 */
	public function encrypt(AuthenticationTicket $ticket) {
		
		$encryption = $this->settings->get('encryption');
		$key = $encryption['Default']['key'];
		$this->encryptor->setEncryptionKey($key);
		return $this->encryptor->encrypt($ticket, Crypt::ENCRYPTION_METHOD_AES);
		
	}
	
	/**
	 * Decrypts a string into a AuthenticationTicekt.
	 * @param unknown $string
	 * @return AuthenticationTicket
	 */
	public function decrypt($string) {
		
		$encryption = $this->settings->get('encryption');
		$key = $encryption['Default']['key'];
		$this->encryptor->setEncryptionKey($key);
		$string = $this->encryptor->decrypt($string, Crypt::ENCRYPTION_METHOD_AES);
		if($string != FALSE) {
			list($username, $valid, $issued, $data, $cookiePath, $expire) = explode(";", $string);
		
			$ticket = new AuthenticationTicket($username, Boolean::parseValue($valid), $issued, $data, $cookiePath, $expire);
		
			return $ticket;
		} 
		
		return null;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IAuthenticationProvider::signout()
	 */
	public function signout() {
		
		$this->headers->setCookie('AuthenticationTicket', '', -3600*1000, "/", null);
		
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IAuthenticationProvider::setAuthCookie()
	 */
	public function setAuthCookie($user) {
		$encryption = $this->settings->get('encryption');
		$key = $encryption['Default']['key'];
		$this->encryptor->setEncryptionKey($key);
		$string = $this->encryptor->encrypt(new AuthenticationTicket($user, true, time(), "", "", time()+250), Crypt::ENCRYPTION_METHOD_AES);
		$this->headers->setCookie('AuthenticationTicket', $string, time()+(3600*24*365), "/",null);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IAuthenticationProvider::getAuthCookie()
	 */
	public function getAuthCookie() {
		return $this->headers->getCookie('AuthenticationTicket');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IAuthenticationProvider::getPrincipal()
	 */
	public function getPrincipal() {
		$cookie = $this->getAuthCookie();
		if($cookie != null) {
			$ticket = $this->decrypt($cookie);
			
			if($ticket != null) {
				$p = new Principal();
				$p->setIdentity(new Identity($ticket->getUserName(), true, "FormAuthentication"));
				return $p;
			}
		}
		return null;
	}
}