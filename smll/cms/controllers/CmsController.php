<?php
namespace smll\cms\controllers;

use smll\framework\mvc\Controller;
/**
 * @author ksdkrol
 * [Authorize]
 */
class CmsController extends Controller 
{

    /**
     *
     * @return \smll\framework\mvc\ViewResult
     */
    public function index()
    {

        return $this->view();
    }

    /**
     *
     * [InRole(Role=Editor|Administrator)]
     */
    public function edit()
    {


        return $this->view();
    }

    /**
     *
     * [InRole(Role=Administrator)]
     */
    public function admin()
    {
        return $this->view();
    }



}