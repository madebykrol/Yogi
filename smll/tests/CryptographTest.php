<?php
namespace smll\tests;
use smll\framework\security\Crypt;
use smll\framework\unittest\UnitTest;
class CryptographTest extends UnitTest {
    public function setup() {

    }

    public function testAESEncrypt() {
        $encrypter = new Crypt();
        $encrypter->setEncryptionKey("derpherp");
        $this->assert(is_string($encrypter->encrypt("derp", Crypt::ENCRYPTION_METHOD_AES)));
    }

    public function testAESDecrypt() {
        $encrypter = new Crypt();
        $encrypter->setEncryptionKey("derpherp");
        $encryption = $encrypter->encrypt("derp", Crypt::ENCRYPTION_METHOD_AES);
        $this->assert($encrypter->decrypt($encryption, Crypt::ENCRYPTION_METHOD_AES) == "derp");
    }


}