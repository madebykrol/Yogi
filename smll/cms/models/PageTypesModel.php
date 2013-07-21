<?php
namespace smll\cms\models;

use smll\framework\utils\ArrayList;

class PageTypesModel
{

    public $installed;
    public $uninstalled;

    public function __construct()
    {
        $this->installed = new ArrayList();
        $this->uninstalled = new ArrayList();
    }

}