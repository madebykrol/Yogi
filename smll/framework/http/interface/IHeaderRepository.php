<?php
interface IHeaderRepository {
	public function getHeaders();
	public function getCookie($name);
	public function setCookie($name, $data, $expire, $path, $domain);
}