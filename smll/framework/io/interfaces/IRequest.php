<?php
namespace smll\framework\io\interfaces;

/**
 *
 * @author Kristoffer "mbk" Olsson
 *
 */
interface IRequest {
    /**
     * getPath returns an array of strings representing the path the user is browsing
     * eg /my/page/1 => array('my', 'page', '1');
     * @return array
     */
    public function getPath();

    /**
     * Set path
     * @param array $path
    */
    public function setPath(array $path);

    /**
     * Get accepted response parameters
    */
    public function getAccept();

    /**
     * Get the query string
     * @param unknown $var
    */
    public function getQueryString($var);

    /**
     * Get post data
    */
    public function getPostData();

    /**
     * Get "Get" data
    */
    public function getGetData();

    /**
     * Get the application root of execution ie, if your application runs on
     * http://www.example.com/mysite
     *
     * This method will return mysite.
    */
    public function getApplicationRoot();

    /**
     * Set request method, get, post, put delete etc
     * @param unknown $method
    */
    public function setRequestMethod($method);

    /**
     * Get the request method
    */
    public function getRequestMethod();

    /**
     * Get the current uri.
    */
    public function getCurrentUri();
    
    public function getPreviousUrl();

}