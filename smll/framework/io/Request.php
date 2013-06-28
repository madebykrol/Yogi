<?php
namespace smll\framework\io;
use smll\framework\io\interfaces\IRequest;
class Request implements IRequest {
	
	private $requestArr = array();
	private $get;
	private $post = array();
	private $path;
	private $requestMethod = Request::METHOD_GET;
	
	public function __construct($requestArr, $get, $post) {
		$this->requestArr = $requestArr;
		
		$this->parseRequestArr($requestArr);
		
		$this->get = $get;
		
		if($post != null) {
			$this->post = $post;
		}
	}
	
	public function init() {
		$path = "";
		
		if(isset($this->get['q'])) {
			$path = $this->get['q'];
		}
		
		$this->path = explode("/",$path);

		unset($_GET);
		unset($_POST);
	}
	
	public function getPath() {
		return $this->path;
	}
	
	public function setPath(array $path) {
		$this->path = $path;
	}
	
	public function getAccept() {
		return "/";
	}
	
	public function getQueryString($var) {
		if(isset($this->get[$var])) {
			return $this->get[$var];
		} else {
			return null;
		}
	}
	
	public function getPostData() {
		return $this->post;
	}
	
	public function getGetData() {
		return $this->get;
	}
	
	public function getRequestMethod() {
		return $this->requestMethod;
	}
	
	public function setRequestMethod($method) {
		$this->requestMethod = $method;
	}
	
	public function getApplicationRoot() {
		return str_replace('/index.php', '', $this->requestArr['PHP_SELF']);
	}
	
	private function parseRequestArr($requestArr) {
		$this->parseRequestMethod($requestArr['REQUEST_METHOD']);
	}
	
	private function parseRequestMethod ($method) {
		if($method == "POST") {
			$this->requestMethod = self::METHOD_POST;
		} else if($method == "PUT") {
			$this->requestMethod = self::METHOD_PUT;
		} else if($method == "DELETE") {
			$this->requestMethod = self::METHOD_DELETE;
		} else {
			$this->requestMethod = self::METHOD_GET;
		}
		
	}
	
	const METHOD_GET = 0;
	const METHOD_POST = 1;
	const METHOD_PUT = 2;
	const METHOD_DELETE = 3;
	
}