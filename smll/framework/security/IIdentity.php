<?php
interface IIdentity {
	public function isAuthenticated();
	public function getName();
	public function getAuthenticationType();
}