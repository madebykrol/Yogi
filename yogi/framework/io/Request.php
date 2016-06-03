<?php
namespace yogi\framework\io;
use yogi\framework\io\interfaces\IRequest;

/**
 * Contains information the current HTTP / HTTPS request
 * @author Kristoffer "mbk" Olsson
 *
 */
class Request implements IRequest {
	
	private $requestArr = array();
	private $get;
	private $post = array();
	private $path;
	private $requestMethod = Request::METHOD_GET;
	private $contentType;
	private $rawData;
	
	public function __construct($requestArr = null, $get = null, $post = null, $files = null) {
		
		if(!isset($requestArr)) {
			$this->requestArr = $_SERVER;
		} else {
			$this->requestArr = $requestArr;	
		}
		
		$this->parseRequestArr($this->requestArr);
		
		if(!isset($get)) {
			$this->get = $_GET;
		} else {
			$this->get = $get;
		}
		
		if(!isset($post)) {
			$this->post = $_POST;
		} else {
			$this->post = $post;
		}
		
		$this->rawData = file_get_contents("php://input");
		
		if(!isset($files)) {
			$files = $_FILES;
		}
		if(isset($files)) {
			foreach($files as $name => $val) {
				$this->post[$name] = $val['name'];
			}
		}
		
		if(count($this->post) > 0) {
			//print_r($this->post);
			//die();
		}
		
		$path = "";
		
		if(isset($this->get['q'])) {
			$path = $this->get['q'];
		}
		
		$this->path = explode("/",$path);
		
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\io\interfaces\IRequest::getPath()
	 */
	public function getPath() {
		return $this->path;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\io\interfaces\IRequest::setPath()
	 */
	public function setPath(array $path) {
		$this->path = $path;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\io\interfaces\IRequest::getAccept()
	 */
	public function getAccept() {
		return "/";
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \smll\framework\io\interfaces\IRequest::getContentType()
	 */
	public function getContentType() {
		return $this->requestArr['CONTENT_TYPE'];
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\io\interfaces\IRequest::getQueryString()
	 */
	public function getQueryString($var) {
		if(isset($this->get[$var])) {
			return $this->get[$var];
		} else {
			return null;
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\io\interfaces\IRequest::getPostData()
	 */
	public function getPostData() {
		return $this->post;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\io\interfaces\IRequest::getGetData()
	 */
	public function getGetData() {
		return $this->get;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \smll\framework\io\interfaces\IRequest::getRawContent()
	 */
	public function getRawContent() {
		$content = null;
		
		if($this->getContentType() === Request::CONTENT_TYPE_APPLICATION_JSON) {
			$content = json_decode(trim($this->rawData));
		}
		
		return $content;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\io\interfaces\IRequest::getRequestMethod()
	 */
	public function getRequestMethod() {
		return $this->requestMethod;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\io\interfaces\IRequest::setRequestMethod()
	 */
	public function setRequestMethod($method) {
		$this->requestMethod = $method;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\io\interfaces\IRequest::getApplicationRoot()
	 */
	public function getApplicationRoot() {
		return str_replace('/index.php', '', $_SERVER['PHP_SELF']);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\io\interfaces\IRequest::getCurrentUri()
	 */
	public function getCurrentUri() {
		$params = $this->get;
		unset($params['q']);
		$queryString = "";
		$i = 0;
		foreach($params as $param => $value) {
			$queryString.=$param."=".$value;
			
			$i++;
			
			if(count($params) > $i) {
				$queryString .= "&";
			}
			
		}
		return $this->getApplicationRoot()."/".$this->get['q']."?".$queryString;
	}
	
	private function parseRequestArr($requestArr) {
		$this->requestMethod = strtolower($requestArr['REQUEST_METHOD']);
		//$this->parseRequestMethod($requestArr['REQUEST_METHOD']);
	}
	
	private function parseRequestMethod ($method) {
		if($method == "POST") {
			$this->requestMethod = self::METHOD_POST;
		} else if($method == "PUT") {
			$this->requestMethod = self::METHOD_PUT;
		} else if($method == "DELETE") {
			$this->requestMethod = self::METHOD_DELETE;
		} else if($method == "GET") {
			$this->requestMethod = self::METHOD_GET;
		} else if($method == "PATCH") {
			$this->requestMethod = self::METHOD_PATCH;
		} else if($method == "HEAD") {
			$this->requestMethod = self::METHOD_HEAD;
		} else if($method == "OPTIONS") {
			$this->requestMethod = self::METHOD_OPTIONS;
		} else if($method == "CONNECT") {
			$this->requestMethod = self::METHOD_CONNECT;
		} else if($method == "TRACE") {
			$this->requestMethod = self::METHOD_TRACE;
		} else {
			$this->requestMethod = $method;
		}
		
		
	}
	
	const METHOD_GET = "get";
	const METHOD_POST = "post";
	const METHOD_PUT = "put";
	const METHOD_DELETE = "delete";
	const METHOD_PATCH = "patch";
	const METHOD_HEAD = "head";
	const METHOD_OPTIONS = "options";
	const METHOD_CONNECT = "connect";
	const METHOD_TRACE = "trace";	
	
	const CONTENT_TYPE_TEXT_PLAIN 		= "text/plain";
	const CONTENT_TYPE_TEXT_CSS 		= "text/css";
	const CONTENT_TYPE_TEXT_HTML 		= "text/html";
	const CONTENT_TYPE_APPLICATION_JSON = "application/json";
	const CONTENT_TYPE_APPLICATION_XML 	= "application/xml";
	const CONTENT_TYPE_APPLICATION_X_WWW_FORM_URLENCODED = "application/x-www-form-urlencoded";
	
}