<?php
namespace smll\cms\framework\content\interfaces;

use smll\cms\framework\content\PropertyCriteria;

interface IPropertyCriteria
{

    /**
     * Set criteria condition.
     * @see PropertyCriteria for a list conditions.
     * @param int $condition
     */
    public function setCondition($condition);

    /**
     * Get criteria condition
    */
    public function getCondition();

    /**
     * Set the property name
     * @param unknown $propertyName
    */
    public function setName($propertyName);

    /**
     * Get property name
    */
    public function getName();

    /**
     * Set the data type of this datatype
     * @param unknown $propertyType
    */
    public function setType($propertyType);

    /**
     * Get data type
    */
    public function getType();

    public function getValue();
    
    public function setValue($value);
    
    /**
     * Sets and / or gets if this property is required when filtering.
     * @param string $boolean
    */
    public function isRequired($boolean = null);


}