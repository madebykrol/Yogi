<?php
namespace smll\cms\framework\content\utils;

use smll\cms\framework\content\utils\interfaces\IContentDataCollection;

class ContentDataCollection implements IContentDataCollection
{
    protected $contentData = array();
    
    private $position;
    
    
    public function __construct(array $data)
    {
        $this->contentData = $data;
    }
    
    public function sort($field, $direction = 'desc', $datatype = 0) {
        $this->contentData = $this->mergeSort($this->contentData, $field, $datatype, $direction);
        
        return $this->contentData;
    }
    
    public function mergeSort($arr, $field, $datatype = 0, $direction)
    {
        if(count($arr) <= 1) {
            return $arr;
        }
        $left = array();
        $right = array();
        
        $middle = (int)count($arr) / 2;
        
        for ($i = 0; $i < $middle; $i++) {
            $left[] = $arr[$i];
        }
        
        for ($i = $middle; $i < count($arr); $i++) {
            
            $right[] = $arr[$i];
        }
        
        
        $left = $this->mergeSort($left, $field, $datatype, $direction);
        $right = $this->mergeSort($right, $field, $datatype, $direction);
        
        
        
        return $this->merge($left, $right, $field, $datatype, $direction);
    }
    
    public function get($index)
    {
        return $this->contentData[$index];
    }
    
    
    private function merge($left, $right, $field, $datatype, $direction)
    {
        $result = array();
        
        while (count($left) > 0 || count($right) > 0) {
            if(count($left) > 0 && count($right) > 0) {
                $l = current($left);
                $r = current($right);
                
                // Do this, but consider datatype and field
                $lClass = new \ReflectionClass(get_class($l));
                $rClass = new \ReflectionClass(get_class($r));
                
                $l = $lClass->getProperty($field)->getValue($l);
                $r = $rClass->getProperty($field)->getValue($r);
                
                if($this->checkFieldCondition($l, $r, $datatype, $direction)) {
                    $result[] = array_shift($left);
                } else {
                    $result[] = array_shift($right);
                }
            } else if(count($left) > 0) {
                $result[] = array_shift($left);
            } else if(count($right) > 0) {
                $result[] = array_shift($right);
            }
        }
        
        return $result;
    }
    
    private function checkFieldCondition($f1, $f2, $datatype, $direction)
    {
        
        switch($datatype) {
            case ContentDataCollection::SORT_DATATYPE_DATE :
                if($direction == ContentDataCollection::SORT_DIRECTION_DESC) {
                    return (strtotime($f1) >= strtotime($f2));
                } else if($direction == ContentDataCollection::SORT_DIRECTION_ASC) {
                    return (strtotime($f1) <= strtotime($f2));
                }
                break;
        }
        
    }
    
    function rewind()
    {
        $this->position = 0;
    }
    
    function current()
    {
        return $this->contentData[$this->position];
    }
    
    function key()
    {
        return $this->position;
    }
    
    function next()
    {
        ++$this->position;
    }
    
    function valid()
    {
        return isset($this->contentData[$this->position]);
    }
    
    const SORT_DATATYPE_INT = 0;
    const SORT_DATATYPE_STRING = 1;
    const SORT_DATATYPE_DATE = 2;
    
    const SORT_DIRECTION_DESC = 'desc';
    const SORT_DIRECTION_ASC = 'asc';
}