<?php
namespace smll\cms\models;

use smll\framework\utils\HashMap;

class AdminNavModel
{

    public $pageTypes;
    public $tools;

    public function __construct()
    {
        $this->pageTypes = new HashMap();
        $this->tools = new HashMap();
    }

}