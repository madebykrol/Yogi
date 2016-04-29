<?php
namespace yogi\framework\security;
/**
 * 
 * Yogi default crypthography class.
 * This class is wrapping two other hasing and cryptographic libraries
 * phpass 0.3
 * @link http://www.openwall.com/phpass/
 * 
 * phpseclib 0.3.1
 * @link http://phpseclib.sourceforge.net/
 * 
 * @author Kristoffer "mbk" Olsson
 * 
 *
 *
 *
 */
require('yogi/lib/phpseclib/Crypt/Rijndael.php');
require('yogi/lib/phpseclib/Crypt/AES.php');
require('yogi/lib/phpass/PasswordHash.php');

use \PasswordHash;
use \Crypt_AES;
use \Crypt_Rijndael;

use yogi\framework\security\interfaces\ICryptographer;

class Crypt implements ICryptographer {
	
	private $encryptKey = null;
	
	public function hash($string) {
		$hasher = new PasswordHash(8, false);
		return $hasher->HashPassword($string);
	}
	
	public function checkHash($string, $hash) {
		$hasher = new PasswordHash(8, false);
		return $hasher->CheckPassword($string, $hash);
	}
	
	public function encrypt($string, $method) {
		if($this->testEncryptionKey()) {
			switch($method) {
				case self::ENCRYPTION_METHOD_AES : 
						return $this->encryptAES($string);
					break;
				
				case self::ENCRYPTION_METHOD_RIJNDAEL : 
						return $this->encryptRIJNDAEL($string);
					break;
					
				case self::ENCRYPTION_METHOD_RSA : 
						return $this->encryptRSA($string);
					break;
					
				case self::ENCRYPTION_METHOD_RC4 : 
						return $this->encryptRC4($string);
					break;
				
			}
		}
	}
	
	public function decrypt($string, $method) {
		if($this->testEncryptionKey()) {
			switch($method) {
				case self::ENCRYPTION_METHOD_AES :
						return $this->decryptAES($string);
					break;
						
				case self::ENCRYPTION_METHOD_RIJNDAEL :
						return $this->decryptRIJNDAEL($string);
					break;
			
				case self::ENCRYPTION_METHOD_RSA :
						return $this->decryptRSA($string);
					break;
			
				case self::ENCRYPTION_METHOD_RC4 :
						return $this->decryptRC4($string);
					break;
			}
		}
	}
	
	public function setEncryptionKey($string) {
		$this->encryptKey = $string;
	}
	
	public function rand() {
		
	}
	
	public function generateSalt() {
		
	}
	
	private function encryptAES($string) {
		$crypter = new Crypt_AES();
		if($this->testEncryptionKey()) {
			$crypter->setKey($this->encryptKey);
			return $crypter->encrypt($string);
		}
	}
	
	private function decryptAES($string) {
		$crypter = new Crypt_AES();
		if($this->testEncryptionKey()) {
				$crypter->setKey($this->encryptKey);
				return $crypter->decrypt($string);
		}
	}
	
	private function testEncryptionKey() {
		if(isset($this->encryptKey)) {
			return true;
		}
		throw new Exception();

	}
	
	const ENCRYPTION_METHOD_AES = 0;
	const ENCRYPTION_METHOD_RIJNDAEL = 1;
	const ENCRYPTION_METHOD_RSA = 2;
	const ENCRYPTION_METHOD_DES= 3;
	const ENCRYPTION_METHOD_PC4 = 4;
	const ENCRYPTION_METHOD_TRIPLE_DES = 5;
}