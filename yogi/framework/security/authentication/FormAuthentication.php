<?php
namespace yogi\framework\security\authentication;
use yogi\framework\security\interfaces\IAuthenticationProvider;
use yogi\framework\security\Principal;
use yogi\framework\security\Identity;
use yogi\framework\utils\ArrayList;
use yogi\framework\settings\interfaces\ISettingsRepository;
use yogi\framework\security\interfaces\ICryptographer;
use yogi\framework\http\interfaces\IHeaderRepository;
use yogi\framework\security\interfaces\IRoleProvider;
use yogi\framework\security\Crypt;
use yogi\framework\utils\Boolean;

class FormAuthentication implements IAuthenticationProvider {
	
	/**
	 * [Inject(yogi\framework\settings\interfaces\ISettingsRepository)]
	 * @var ISettingsRepository
	 */
	private $settings;
	
	/**
	 * [Inject(yogi\framework\security\interfaces\ICryptographer)]
	 * @var ICryptographer
	 */
	private $encryptor;
	
	/**
	 * [Inject(yogi\framework\http\interfaces\IHeaderRepository)]
	 * @var IHeaderRepository
	 */
	private $headers;
	
	/**
	 * [Inject(yogi\framework\security\interfaces\IRoleProvider)]
	 * @var unknown
	 */
	private $roleProvider;
	
	
	public function setRoleProvider(IRoleProvider $provider) {
		$this->roleProvider = $provider;
	}
	
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
	private function encrypt(AuthenticationTicket $ticket) {
		
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
	private function decrypt($string) {
		
		$encryption = $this->settings->get('encryption');
		$key = $encryption['Default']['key'];
		$this->encryptor->setEncryptionKey($key);
		$string = $this->encryptor->decrypt($string, Crypt::ENCRYPTION_METHOD_AES);
		if($string != FALSE) {
			list($username, $valid, $issued, $roles, $cookiePath, $expire) = explode(";", $string);
		
			$ticket = new AuthenticationTicket($username, Boolean::parseValue($valid), $issued, explode(',', $roles), $cookiePath, $expire);
		
			return $ticket;
		} 
		
		return null;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IAuthenticationProvider::signout()
	 */
	public function signout() {
		$webSettings = $this->settings->get('web');
		$path = $webSettings['cookiePath']['path'];
		$domain = $webSettings['cookiePath']['domain'];

		
		$this->headers->setCookie('AuthenticationTicket', '', -3600*1000, $path."/", null);
		
	}
	
	public function signin($user, $updateLastLogin = false) {
		$this->setAuthCookie($user);
	}
	
	/**
	 * Sets a authentication cookie that contains an encrypted AuthenticationTicket
	 */
	private function setAuthCookie($user) {
		$encryption = $this->settings->get('encryption');
		$key = $encryption['Default']['key'];
		$this->encryptor->setEncryptionKey($key);
		
		$webSettings = $this->settings->get('web');
		
		$path = $webSettings['cookiePath']['path'];
		$domain = $webSettings['cookiePath']['domain'];
		
		
		$string = $this->encryptor->encrypt(new AuthenticationTicket($user, true, time(), $this->roleProvider->getRolesForUser($user), "", time()+250), Crypt::ENCRYPTION_METHOD_AES);
		$this->headers->setCookie('AuthenticationTicket', $string, time()+(3600*24*365), $path."/",null);
	}
	
	/**
	 * Get the authentication cookie for the current request.
	 */
	private function getAuthCookie() {
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
				
				$roles = $ticket->getRoles();
				$p->setRoles(new ArrayList($roles));
				
				$p->setIdentity(new Identity($ticket->getUserName(), true, "FormAuthentication"));
				return $p;
			}
		}
		return null;
	}
}