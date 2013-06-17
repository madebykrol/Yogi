<?php
namespace smll\framework\http\interfaces;
interface IHeaderRepository {
	public function getHeaders();
	public function getCookie($name);
	public function setCookie($name, $data, $expire, $path, $domain);
}