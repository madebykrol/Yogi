<?php
namespace smll\framework\io;
use smll\framework\io\interfaces\IRequest;

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
    
    private $previous = null;

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
    
    public function getPreviousUrl() {
        return $this->previous;
    }

    private function parseRequestArr($requestArr) {
        if(isset($requestArr['HTTP_REFERER'])) {
            $this->previous = $requestArr['HTTP_REFERER'];
        }
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