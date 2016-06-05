<?php
namespace yogi\framework\http;
use yogi\framework\http\interfaces\ISession;

/**
 * Session handler.
 * This implementation of Session is trying it's best to prevent Session hijacking
 * by trying to invalidate the session if it's being used.
 * @author Kristoffer "mbk" Olsson
 *
 */
class Session implements ISession {
	
	protected $token = "smll_";
	protected $defaults = array(
			'authenticated' => false,
			'membershipkey' => null);
	
	public function __construct($defaults) {
		
		$this->init();
		/**
		 * @Todo fix a timestamp threshold for the sudden change of User agent and IP.
		 */
		if($_SESSION[$this->token.'USER_LOOSE_IP'] != long2ip(ip2long($_SERVER['REMOTE_ADDR'])
				& ip2long("255.255.0.0"))
				|| $_SESSION[$this->token.'USER_AGENT'] != $_SERVER['HTTP_USER_AGENT']
		) {
	
			// flag for possible session hijack.
			$this->status = SESSION::STATUS_POSSIBLE_HIJACK;
			$this->regenerateSession();
		}
	
		if(isset($defaults)){
			foreach($defaults as $var => $val) {
				if($this->get($var) === FALSE){
					$this->set($var, $val);
				}
			}
		}
	}
	
	public function getSessionID() {
		return session_id();
	}
	
	public function getToken() {
		return $this->token;
	}	
	
	public function set($var, $val) {
		$_SESSION[$this->token.$var] = $val;
	}
	
	public function add($var, $val) {
		if(!is_array($_SESSION[$this->token.$var])) {
			$tmpValue = null;
			if(isset($_SESSION[$this->token.$var])) {
				$tmpValue = $_SESSION[$this->token.$var];
			}
			$_SESSION[$this->token.$var] = array();
			if(isset($tmpValue)) {
				$_SESSION[$this->token.$var][] = $tmpValue;
			}
		}
		
		$_SESSION[$this->token.$var][] = $val;
		
	}
	
	public function remove($var) {
		unset($_SESSION[$this->token.$var]);
	}
	
	public function get($var) {
		if(isset($_SESSION[$this->token.$var]) && $_SESSION[$this->token.$var] != "") {
			return $_SESSION[$this->token.$var];
		} else {
			return null;
		}
	}
	
	public function destroy() {
		foreach($_SESSION as $var => $val) {
			if(strpos($var, $this->token)!==FALSE){
				//unset($_SESSION[$var]);
			}
		}
	}
	
	public function __destruct() {
		$this->destroy();
	}
	
	protected function init() {
		if(!isset($_SESSION[$this->token.'USER_LOOSE_IP'])){
			$_SESSION[$this->token.'USER_LOOSE_IP'] = long2ip(ip2long($_SERVER['REMOTE_ADDR']) 
                                      & ip2long("255.255.0.0"));
		}
		if(!isset($_SESSION[$this->token.'USER_AGENT'])){
			$_SESSION[$this->token.'USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
		}
	}
	
	protected function regenerateSession() {
		// Destroy and start a new session
		session_unset(); // Same as $_SESSION = array();
		session_destroy(); // Destroy session on disk
		session_start();
		session_regenerate_id(true);
		$this->init();
	}

	
	const STATUS_INITIALIZED = 1;
	const STATUS_DESTORYED = 2;
	const STATUS_POSSIBLE_HIJACK = 3;
}