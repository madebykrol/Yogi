<?php
use yogi\framework\unittest\UnitTest;
use yogi\framework\security\Crypt;

class CryptographTest extends UnitTest { 
	
	private $_encrypter;
	
	public function setup() {
		$_encrypter = new Crypt();
		$_encrypter->setEncryptionKey("derpherp");
	}
	
	public function testAESEncrypt() {
		$this->assert(is_string($encrypter->encrypt("derp", Crypt::ENCRYPTION_METHOD_AES)));
	}
	
	public function testAESDecrypt() {
		$encryption = $encrypter->encrypt("derp", Crypt::ENCRYPTION_METHOD_AES);
		$this->assert($encrypter->decrypt($encryption, Crypt::ENCRYPTION_METHOD_AES) == "derp");
	}
		
}