<?php
namespace yogi\framework\security\interfaces;
interface IIdentity {
	public function isAuthenticated();
	public function getName();
	public function getAuthenticationType();
}