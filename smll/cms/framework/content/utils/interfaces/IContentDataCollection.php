<?php
namespace smll\cms\framework\content\utils\interfaces;

interface IContentDataCollection {
    
    /**
     * 
     * @param unknown $field
     * @param unknown $direction
     * @param int $datatype
     */
    public function sort($field, $direction, $datatype = 0);
    public function get($index);
    
    
}