<?php
namespace smll\cms\framework\ui;
use smll\framework\utils\ArrayList;

use smll\cms\framework\ui\interfaces\IMenuTree;
use smll\cms\framework\ui\interfaces\IMenuItem;

class MenuTree implements IMenuTree
{

    protected $tree;

    public function __construct()
    {
        $this->tree = new ArrayList();
    }

    public function addItem(IMenuItem $item)
    {
        $this->tree->add($item);
    }
    public function removeItem($item)
    {
        $this->tree->remove($item);
    }

    public function getItems()
    {
        return $this->tree;
    }
}