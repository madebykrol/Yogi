<?php
namespace smll\cms\framework\content;

use smll\cms\framework\content\interfaces\IPropertyCriteria;

use smll\cms\framework\content\interfaces\IPropertyCriteriaCollection;

class PropertyCriteriaCollection implements IPropertyCriteriaCollection
{

    private $criterias = array();

    public function getIterator()
    {
        return $this->criterias;
    }
    public function add(IPropertyCriteria $criteria)
    {
        $this->criterias[] = $criteria;
    }
    public function remove($index)
    {
        unset($this->criterias[$index]);
    }

}
