<?php
namespace smll\cms\framework\content\taxonomy;

use smll\cms\framework\content\taxonomy\interfaces\ITaxonomyTerm;

class Term implements ITaxonomyTerm
{

    private $title;
    private $description;
    private $parent;
    private $id;
    private $short;

    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }
    
    public function getDescription() 
    {
        return $this->description;
    }
    
    public function getParent()
    {
        return $this->parent;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    public function setDescription($description){
        $this->description = $description;
    }
    
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    public function setShort($short)
    {
        $this->short = $short;
    }
    
    public function getShort()
    {
        return $this->short;
    }

    public function __toString()
    {
        return $this->id;
    }
}